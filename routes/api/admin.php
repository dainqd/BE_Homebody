<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\restapi\admin\AdminBookingApi;
use App\Http\Controllers\restapi\admin\AdminCategoryApi;
use App\Http\Controllers\restapi\admin\AdminCouponApi;
use App\Http\Controllers\restapi\admin\AdminPartnerRegisterApi;
use App\Http\Controllers\restapi\admin\AdminReviewApi;
use App\Http\Controllers\restapi\admin\AdminUserApi;

Route::group(['prefix' => ''], function () {

});

Route::group(['prefix' => 'partner-register'], function () {
    Route::get('/list', [AdminPartnerRegisterApi::class, 'list'])->name('api.admin.partner.register.list');
    Route::get('/detail/{id}', [AdminPartnerRegisterApi::class, 'detail'])->name('api.admin.partner.register.detail');
    Route::post('/update/{id}', [AdminPartnerRegisterApi::class, 'update'])->name('api.admin.partner.register.update');
    Route::delete('/delete/{id}', [AdminPartnerRegisterApi::class, 'delete'])->name('api.admin.partner.register.delete');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/list', [AdminCategoryApi::class, 'list'])->name('api.admin.categories.list');
    Route::get('/detail/{id}', [AdminCategoryApi::class, 'detail'])->name('api.admin.categories.detail');
    Route::post('/create', [AdminCategoryApi::class, 'create'])->name('api.admin.categories.create');
    Route::post('/update/{id}', [AdminCategoryApi::class, 'update'])->name('api.admin.categories.update');
    Route::delete('/delete/{id}', [AdminCategoryApi::class, 'delete'])->name('api.admin.categories.delete');
});

Route::group(['prefix' => 'bookings'], function () {
    Route::get('/list', [AdminBookingApi::class, 'list'])->name('api.admin.bookings.list');
    Route::get('/detail/{id}', [AdminBookingApi::class, 'detail'])->name('api.admin.bookings.detail');
    Route::post('/update/{id}', [AdminBookingApi::class, 'update'])->name('api.admin.bookings.update');
    Route::delete('/delete/{id}', [AdminBookingApi::class, 'delete'])->name('api.admin.bookings.delete');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/list', [AdminUserApi::class, 'list'])->name('api.admin.users.list');
    Route::get('/detail/{id}', [AdminUserApi::class, 'detail'])->name('api.admin.users.detail');
    Route::post('/create', [AdminUserApi::class, 'create'])->name('api.admin.users.create');
    Route::post('/update/{id}', [AdminUserApi::class, 'update'])->name('api.admin.users.update');
    Route::delete('/delete/{id}', [AdminUserApi::class, 'delete'])->name('api.admin.users.delete');
});

Route::group(['prefix' => 'coupons'], function () {
    Route::get('list', [AdminCouponApi::class, 'list'])->name('api.admin.coupons.list');
    Route::get('detail/{id}', [AdminCouponApi::class, 'detail'])->name('api.admin.coupons.detail');
    Route::post('create', [AdminCouponApi::class, 'create'])->name('api.admin.coupons.create');
    Route::post('update/{id}', [AdminCouponApi::class, 'update'])->name('api.admin.coupons.update');
    Route::delete('delete/{id}', [AdminCouponApi::class, 'delete'])->name('api.admin.coupons.delete');
});

Route::group(['prefix' => 'reviews'], function () {
    Route::get('list', [AdminReviewApi::class, 'list'])->name('api.admin.reviews.list');
    Route::get('detail', [AdminReviewApi::class, 'detail'])->name('api.admin.reviews.detail');
    Route::post('update', [AdminReviewApi::class, 'update'])->name('api.admin.reviews.update');
    Route::delete('delete', [AdminReviewApi::class, 'delete'])->name('api.admin.reviews.delete');
});
