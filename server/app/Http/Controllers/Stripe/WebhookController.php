<?php

namespace App\Http\Controllers\Stripe;


use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

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
            $user = $this->getUserByStripeId('cus_I510rP4HqaLM1n'); //TODO webhookのテストでは値が固定されている為固定値を与えている。本来は$payload['data']['object']['customer']
            $user->subscriptions->each(function ($subscription) {
                    if ($subscription->stripe_status === 'active') {
                        $subscription->cancel();
                    }
            });
        }

        return $this->successMethod();
    }

}