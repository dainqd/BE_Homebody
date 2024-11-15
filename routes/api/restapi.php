<?php

/*
|--------------------------------------------------------------------------
| RestApi Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\restapi\AuthApi;
use App\Http\Controllers\restapi\CategoryApi;
use App\Http\Controllers\restapi\CurrencyApi;
use App\Http\Controllers\restapi\LocationApi;
use App\Http\Controllers\restapi\PartnerRegisterApi;
use App\Http\Controllers\restapi\SearchApi;
use App\Http\Controllers\restapi\ServiceApi;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthApi::class, 'login'])->name('restapi.auth.login');
    Route::post('/register', [AuthApi::class, 'register'])->name('restapi.auth.register');
    Route::post('/logout', [AuthApi::class, 'logout'])->name('restapi.auth.logout');
});

Route::group(['prefix' => 'partner'], function () {
    Route::post('register', [PartnerRegisterApi::class, 'create'])->name('restapi.partner.register');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('list', [CategoryApi::class, 'list'])->name('restapi.categories.list');
    Route::get('detail/{id}', [CategoryApi::class, 'detail'])->name('restapi.categories.detail');
});

Route::group(['prefix' => 'services'], function () {
    Route::get('list', [ServiceApi::class, 'list'])->name('restapi.services.list');
    Route::get('detail/{id}', [ServiceApi::class, 'detail'])->name('restapi.services.detail');
});

Route::group(['prefix' => 'search'], function () {
    Route::get('user', [SearchApi::class, 'searchUser'])->name('restapi.search.users.list');
    Route::get('partner', [SearchApi::class, 'getPartner'])->name('restapi.search.partner.list');
});

Route::group(['prefix' => 'locations'], function () {
    Route::get('get-location', [LocationApi::class, 'getLocation'])->name('restapi.locations.get');
    Route::get('get-by-name', [LocationApi::class, 'getLongAndLatFromAddress'])->name('restapi.locations.get.by.name');
});

Route::group(['prefix' => 'currencies'], function () {
    Route::get('convert', [CurrencyApi::class, 'convert'])->name('restapi.currencies.convert');
});

