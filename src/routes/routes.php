<?php

Route::group(['middleware' => ['web', 'auth', 'tenant', 'service.accounting']], function() {

	Route::prefix('goods-delivered')->group(function () {

        //Route::get('summary', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@summary');
        Route::post('export-to-excel', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@exportToExcel');
        Route::post('{id}/approve', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@approve');
        //Route::post('contact-estimates', 'Rutatiina\GoodsDelivered\Http\Controllers\Sales\ReceiptController@estimates');
        Route::get('{id}/copy', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@copy');

    });

    Route::resource('goods-delivered/settings', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredSettingsController');
    Route::resource('goods-delivered', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController');

});
