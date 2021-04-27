<?php

// $middleware = config('payuz')['control_panel']['middleware'];
// if ($middleware == '' || is_null($middleware))
//     $middleware = 'web';

Route::middleware('web')->name('payment.')->prefix('payment')->namespace('Goodoneuz\PayUz\Http\Controllers')->group(function() {
    Route::any('dashboard','PageController@dashboard')->name('dashboard');
    Route::any('editors','PageController@editors')->name('editors');
    Route::any('blank','PageController@blank')->name('blank');
    Route::any('settings','PageController@settings')->name('settings');
    Route::get('payment_params/delete/{param_id}','PaymentSystemController@deleteParam')->name('payment_systems.delete_param');
    Route::get('payment_systems/edit/status/{payment_system}','PaymentSystemController@editStatus')->name('payment_systems.edit_status');

    // --editable functions
    Route::any('/api/editable/update','ApiController@file_put')->name('api.file_put');
    // end --editable functions

    Route::resource('transactions','TransactionController');
    Route::resource('projects','ProjectController');
    Route::resource('payment_systems','PaymentSystemController');
    Route::resource('transactions','TransactionController');
});

//handle requests from payment system
Route::any('/handle/{paysys}',function($paysys){
    (new Goodoneuz\PayUz\PayUz)->driver($paysys)->handle();
});

//redirect to payment system or payment form
Route::any('/pay/{paysys}/{key}/{amount}',function($paysys, $key, $amount){
    $model = Goodoneuz\PayUz\Services\PaymentService::convertKeyToModel($key);
    $url = request('redirect_url','/'); // redirect url after payment completed
    $pay_uz = new Goodoneuz\PayUz\PayUz;
    $pay_uz
        ->driver($paysys)
        ->redirect($model, $amount, 860, $url);
});
