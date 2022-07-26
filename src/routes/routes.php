<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'tenant', 'service.accounting']], function() {

	Route::prefix('goods-delivered')->group(function () {

        Route::post('routes', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@routes')->name('goods-delivered.routes');
        //Route::get('summary', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@summary');
        Route::post('export-to-excel', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@exportToExcel');
        Route::post('{id}/approve', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@approve')->name('goods-delivered.approve');
        //Route::post('contact-estimates', 'Rutatiina\GoodsDelivered\Http\Controllers\Sales\ReceiptController@estimates');
        Route::get('{id}/copy', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@copy');
        Route::delete('delete', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@delete')->name('goods-delivered.delete');
        Route::patch('cancel', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController@cancel')->name('goods-delivered.cancel');

    });

    Route::resource('goods-delivered/settings', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredSettingsController');
    Route::resource('goods-delivered', 'Rutatiina\GoodsDelivered\Http\Controllers\GoodsDeliveredController');

});
