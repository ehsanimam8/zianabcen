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
        $endpointSecret = Setting::where('key', 'stripe_webhook_secret')->value('value');

        try {
            if ($endpointSecret) {
                $event = Webhook::constructEvent(
                    $payload, $sigHeader, $endpointSecret
                );
            } else {
                $event = \Stripe\Event::constructFrom(
                    json_decode($payload, true)
                );
            }
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

        $termId = \App\Models\SIS\Term::first()->id ?? null;

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
