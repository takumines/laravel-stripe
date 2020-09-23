<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    /**
     * サブスク登録画面
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $user = auth()->user();

        return view('user.subscription.card', ['intent' => $user->createSetupIntent()]);
    }

    /**
     * 単発決済画面
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function chargeView()
    {
        return view('user.subscription.charge');
    }

    /**
     * サブスク登録処理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function subscribe(Request $request)
    {
        $user = auth()->user();
        if (!$user->subscribed('main')) {
            $paymentMethod = $request->payment_method;
            $user->newSubscription('main', $request->plan)->create($paymentMethod);
        }

        return redirect()->route('home');
    }

    /**
     * サブスクキャンセル
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        $user = auth()->user();
        $user->subscription('main')->cancelNow();

        return redirect()->route('home');
    }

    /**
     * 単発決済処理
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function charge(Request $request)
    {
        $user = auth()->user();
        $user->charge($request->amount, $request->payment_method, [
            'currency' => 'jpy',
        ]);

        return redirect()->route('home');
    }
}
