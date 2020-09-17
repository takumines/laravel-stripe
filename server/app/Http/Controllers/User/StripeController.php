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

    public function createSubscription(Request $request)
    {
        $user = auth()->user();
        $paymentMethod = $request->payment_method;
        $user->newSubscription('main', $request->plan)->create($paymentMethod);
    }
}
