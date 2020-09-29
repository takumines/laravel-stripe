<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('stripe/webhook', 'Stripe\WebhookController@handleWebhook');

Route::prefix('user')->middleware(['auth'])->group(function() {
    Route::get('subscription', 'Stripe\SubscriptionController@index')->name('stripe');
    Route::get('ajax/subscription/status', 'Stripe\Ajax\SubscriptionController@status');
    Route::post('ajax/subscription/subscribe', 'Stripe\Ajax\SubscriptionController@subscribe')->name('create');
    Route::post('ajax/subscription/cancel', 'Stripe\Ajax\SubscriptionController@cancel')->name('cancel');
    Route::post('ajax/subscription/resume', 'Stripe\Ajax\SubscriptionController@resume');
    Route::post('ajax/subscription/change_plan', 'Stripe\Ajax\SubscriptionController@change_plan');
    Route::post('ajax/subscription/update_card', 'Stripe\Ajax\SubscriptionController@update_card');

    Route::get('/card', 'Stripe\StripeController@index')->name('card');
    Route::post('/subscription/create', 'Stripe\StripeController@subscribe')->name('stripeCreate');
    Route::get('/subscription/cancel', 'Stripe\StripeController@cancel')->name('stripeCancel');
    Route::get('/charge', 'Stripe\StripeController@chargeView')->name('chargeView');
    Route::post('/subscription/charge', 'Stripe\StripeController@charge')->name('charge');

});
