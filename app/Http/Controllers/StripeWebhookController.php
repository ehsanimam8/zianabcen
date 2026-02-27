<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\CMS\Setting;
use App\Models\SIS\Enrollment;
use Illuminate\Support\Facades\Log;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = Setting::getDecryptedValue('stripe_webhook_secret');

        if (!$endpointSecret) {
            Log::error('Stripe webhook secret is not configured.');
            return response()->json(['error' => 'Webhook secret not configured'], 500);
        }

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $this->handleCheckoutSession($session);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handleCheckoutSession($session)
    {
        $userId = $session->metadata->user_id ?? null;
        $programIds = array_filter(explode(',', $session->metadata->program_ids ?? ''));

        if (!$userId || empty($programIds)) {
            Log::warning('Stripe webhook received checkout.session.completed but missing metadata', [
                'session_id' => $session->id,
            ]);
            return;
        }

        $term = \App\Models\SIS\Term::where('is_current', true)->first() 
             ?? \App\Models\SIS\Term::latest()->first();
        $termId = $term ? $term->id : null;

        foreach ($programIds as $programId) {
            Enrollment::updateOrCreate(
                [
                    'user_id' => $userId,
                    'program_id' => $programId,
                    'term_id' => $termId,
                ],
                [
                    'status' => 'active',
                    'enrolled_at' => now(),
                    'payment_method' => 'stripe',
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'amount_paid' => $session->amount_total / 100,
                ]
            );
        }
    }
}
