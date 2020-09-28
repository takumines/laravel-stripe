<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;


class User extends Authenticatable
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function status()
    {
        $status = 'unsubscribed';
        $user = auth()->user();
        $details = [];
        if($user->subscribed('main')) { // 課金履歴あり
            if ($user->onTrial('main')) { // トライアル期間中
                $status = 'trial';
                $subscription = $user->subscriptions->first(function ($value) {
                    return ($value->name === 'main');
                })->only('trial_ends_at', 'stripe_plan');
                $details = [
                    'trial_end_date' => ($subscription['trial_ends_at']) ? $subscription['trial_ends_at']->format('Y年m月d日') : null,
                    'plan' => \Arr::get(config('services.stripe.plans'), $subscription['stripe_plan']),
                    'card_last_four' => '登録なし',
                ];
            } else {
                if($user->subscription('main')->cancelled()) {  // キャンセル済み
                    $status = 'cancelled';
                } else {    // 課金中
                    $status = 'subscribed';
                }
                $subscription = $user->subscriptions->first(function ($value) {
                    return ($value->name === 'main');
                })->only('ends_at', 'stripe_plan');
                $details = [
                    'end_date' => ($subscription['ends_at']) ? $subscription['ends_at']->format('Y年m月d日') : null,
                    'plan' => \Arr::get(config('services.stripe.plans'), $subscription['stripe_plan']),
                    'card_last_four' => $user->card_last_four
                ];
            }

        }

        return [
            'status' => $status,
            'details' => $details
        ];
    }

}
