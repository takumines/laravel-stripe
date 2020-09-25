<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\User;

class SubscriptionController extends Controller
{
    public function index(User $user)
    {
        return view('user.subscription.index', ['intent' => $user->createSetupIntent()]);
    }
}
