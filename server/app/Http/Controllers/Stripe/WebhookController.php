<?php

namespace App\Http\Controllers\Stripe;


use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

class WebhookController extends CashierController
{
    public function handleCreditNoteVoided($payload)
    {
    }

    public function handleCustomerSourceExpiring($payload)
    {
        Log::info($payload);
    }

    public function handleInvoicePaymentFailed($payload)
    {
        if ($payload['data']['object']['customer']) {
            $user = $this->getUserByStripeId('cus_I510rP4HqaLM1n');// $payload['data']['object']['customer']
            $user->subscriptions->each(function ($subscription) {
                    if ($subscription->stripe_status === 'active') {
                        $subscription->cancelNow();
                    }
            });
//            if ($payload['data']['object']['lines']['data']) {
//                Log::info($payload['data']['object']['lines']['data']);
//                $filtered = $user->subscriptions->filter(function ($subscription) use ($payload) {
//                    return $subscription->stripe_id === 'sub_I4wsM7yhS89HPm';
//                })->each(function ($subscription) {
//
//                });
//            };
        }

        return $this->successMethod();
    }

}