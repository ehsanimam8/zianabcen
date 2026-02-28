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
        $studentAssignments = isset($session->metadata->student_assignments) 
            ? json_decode($session->metadata->student_assignments, true) 
            : [];

        if (!$userId || empty($programIds)) {
            Log::warning('Stripe webhook received checkout.session.completed but missing metadata', [
                'session_id' => $session->id,
            ]);
            return;
        }

        $term = \App\Models\SIS\Term::where('is_current', true)->first() 
             ?? \App\Models\SIS\Term::latest()->first();
        $termId = $term ? $term->id : null;
        $amountPaidPerProgram = ($session->amount_total / 100) / count($programIds);

        foreach ($programIds as $programId) {
            $enrolledUserId = $userId;
            
            // Handle family billing / specific student assignment
            if (!empty($studentAssignments[$programId])) {
                $studentName = $studentAssignments[$programId];
                $parentUser = \App\Models\User::find($userId);
                
                // If the specified name is not the parent's own name, create a child account
                if ($parentUser && strtolower(trim($studentName)) !== strtolower(trim($parentUser->name))) {
                    $childEmail = strtolower(str_replace(' ', '.', trim($studentName))) . '.' . $parentUser->id . '@student.local';
                    
                    $childUser = \App\Models\User::firstOrCreate(
                        ['name' => trim($studentName)],
                        [
                            'email' => $childEmail,
                            'password' => bcrypt(\Illuminate\Support\Str::random(12)),
                            'role' => 'Student'
                        ]
                    );
                    
                    if (!$childUser->hasRole('Student')) {
                        $childUser->assignRole('Student');
                    }
                    
                    $enrolledUserId = $childUser->id;
                }
            }

            Enrollment::updateOrCreate(
                [
                    'user_id' => $enrolledUserId,
                    'program_id' => $programId,
                    'term_id' => $termId,
                ],
                [
                    'status' => 'Enrolled',
                    'enrolled_at' => now(),
                    'payment_method' => 'Stripe',
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'amount_paid' => $amountPaidPerProgram,
                ]
            );
        }
    }
}
