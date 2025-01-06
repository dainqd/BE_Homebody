<?php

/*
|--------------------------------------------------------------------------
| Partner Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\restapi\partner\PartnerBookingApi;
use App\Http\Controllers\restapi\partner\PartnerInfoApi;
use App\Http\Controllers\restapi\partner\PartnerServiceApi;

Route::group(['prefix' => 'update'], function () {
    Route::get('show', [PartnerInfoApi::class, 'showInfo'])->name('api.partner.update.show');
    Route::post('info', [PartnerInfoApi::class, 'saveInfo'])->name('api.partner.update.info');
});

Route::group(['prefix' => 'services'], function () {
    Route::get('/list', [PartnerServiceApi::class, 'list'])->name('api.partner.services.list');
    Route::get('/detail/{id}', [PartnerServiceApi::class, 'detail'])->name('api.partner.services.detail');
    Route::post('/create', [PartnerServiceApi::class, 'create'])->name('api.partner.services.update');
    Route::post('/update/{id}', [PartnerServiceApi::class, 'update'])->name('api.partner.services.update');
    Route::delete('/delete/{id}', [PartnerServiceApi::class, 'delete'])->name('api.partner.services.delete');
});

Route::group(['prefix' => 'bookings'], function () {
    Route::get('/list', [PartnerBookingApi::class, 'list'])->name('api.admin.bookings.list');
    Route::get('/detail/{id}', [PartnerBookingApi::class, 'detail'])->name('api.admin.bookings.detail');
    Route::post('/update/{id}', [PartnerBookingApi::class, 'update'])->name('api.admin.bookings.update');
});
