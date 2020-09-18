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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('stripe/webhook', 'User\StripeWebhookController@index');

Route::prefix('user')->middleware(['auth'])->group(function() {
    Route::get('subscription', 'User\SubscriptionController@index');
    Route::get('ajax/subscription/status', 'User\Ajax\SubscriptionController@status');
    Route::post('ajax/subscription/subscribe', 'User\Ajax\SubscriptionController@subscribe');
    Route::post('ajax/subscription/cancel', 'User\Ajax\SubscriptionController@cancel');
    Route::post('ajax/subscription/resume', 'User\Ajax\SubscriptionController@resume');
    Route::post('ajax/subscription/change_plan', 'User\Ajax\SubscriptionController@change_plan');
    Route::post('ajax/subscription/update_card', 'User\Ajax\SubscriptionController@update_card');

    Route::get('/card', 'User\StripeController@index')->name('card');
    Route::post('/create/subscription', 'User\StripeController@createSubscription')->name('create');
});
