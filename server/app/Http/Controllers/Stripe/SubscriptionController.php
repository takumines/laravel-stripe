<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\User;

class SubscriptionController extends Controller
{
    /**
     * @param User $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(User $user)
    {
        return view('user.subscription.index', ['intent' => $user->createSetupIntent()]);
    }
}
