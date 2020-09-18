<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function index()
    {
        return view('user.subscription.card');
    }

    public function subscribe(Request $request)
    {
        $user = auth()->user();
        if (!$user->subscribed('main')) {
            $paymentMethod = $request->payment_method;
            $user->newSubscription('main', $request->plan)->create($paymentMethod);
        }

        return redirect()->route('home');
    }

    public function cancel()
    {
        $user = auth()->user();
        $user->subscription('main')->cancelNow();

        return redirect()->route('home');
    }


}
