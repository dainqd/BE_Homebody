<?php

use App\Http\Controllers\restapi\PartnerApi;
use App\Http\Controllers\restapi\user\BookingApi;
use App\Http\Controllers\restapi\user\CheckoutApi;
use App\Http\Controllers\restapi\user\MyCouponApi;
use App\Http\Controllers\restapi\user\UserApi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'users'], function () {
    Route::get('/get-info', [UserApi::class, 'getUserFromToken'])->name('api.users.get.info');
    Route::post('/update-info', [UserApi::class, 'updateInfo'])->name('api.users.update.info');
    Route::post('/change-avatar', [UserApi::class, 'changeAvatar'])->name('api.users.change.avatar');
    Route::post('/change_password', [UserApi::class, 'changePassword'])->name('api.users.change.password');
});

//Route::group(['prefix' => 'carts'], function () {
//    Route::get('list', [CartApi::class, 'list'])->name('api.auth.carts.list');
//    Route::post('add', [CartApi::class, 'addToCart'])->name('api.auth.carts.add');
//    Route::post('change-quantity/{id}', [CartApi::class, 'changeQuantity'])->name('api.auth.carts.change');
//    Route::post('remove/{id}', [CartApi::class, 'removeCart'])->name('api.auth.carts.remove');
//    Route::post('clear', [CartApi::class, 'clearCart'])->name('api.auth.carts.clear');
//});

Route::group(['prefix' => 'partner'], function () {
    Route::get('get-time', [PartnerApi::class, 'getHourlyIntervals'])->name('api.auth.partner.time.interval');
});

Route::group(['prefix' => 'bookings'], function () {
    Route::get('/list', [BookingApi::class, 'list'])->name('api.auth.bookings.list');
    Route::get('/detail/{id}', [BookingApi::class, 'detail'])->name('api.auth.bookings.detail');
    Route::post('/create', [BookingApi::class, 'create'])->name('api.auth.bookings.create');
    Route::post('/update/{id}', [BookingApi::class, 'update'])->name('api.auth.bookings.update');
    Route::post('/cancel/{id}', [BookingApi::class, 'cancel'])->name('api.auth.bookings.cancel');
});

Route::group(['prefix' => 'checkout'], function () {
    Route::post('/create', [CheckoutApi::class, 'processStripe'])->name('api.auth.checkout.create');
});

Route::group(['prefix' => 'my-coupons'], function () {
    Route::get('list', [MyCouponApi::class, 'list'])->name('api.auth.my.coupons.list');
    Route::get('detail', [MyCouponApi::class, 'detail'])->name('api.auth.my.coupons.detail');
    Route::get('search', [MyCouponApi::class, 'search'])->name('api.auth.my.coupons.search');
    Route::post('save', [MyCouponApi::class, 'saveCoupon'])->name('api.auth.my.coupons.save');
    Route::delete('delete', [MyCouponApi::class, 'delete'])->name('api.auth.my.coupons.delete');
});
