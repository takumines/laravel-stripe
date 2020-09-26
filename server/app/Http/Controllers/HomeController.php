<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $result = $user->status();
        // subscriptionのstripe_statusがactiveなinvoiceにプラン名と定期支払いの締め日を含めて返す
        $invoices = $user->invoices()->filter(function($value) use ($user) {
            $subscription = $user->subscriptions()->where('stripe_id', $value->subscription)->first();

            return $subscription->stripe_status  === 'active';
        })->each(function ($value) use ($user) {
            // プラン名を取得
            $subscription = $user->subscriptions()->where('stripe_id', $value->subscription)->first();
            $plan = \Arr::get(config('services.stripe.plans'), $subscription->stripe_plan);
            $value->plan_name = $plan;
            // サブスク更新日時を取得
            $stopped = array_shift($value->lines->data)->period->end;
            $value->stopped = Carbon::createFromTimestamp($stopped, 'Asia/Tokyo')->format('Y年m月d日');
        });

        $status = $result['status'];
        $details = $result['details'];

        return view('home', [
            'status' => $status,
            'details' => $details,
            'invoices' => $invoices,
        ]);
    }
}
