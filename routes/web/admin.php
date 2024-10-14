<?php

/*
|--------------------------------------------------------------------------
| Web Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\AdminPartnerRegisterController;

Route::get('/dashboard', [AdminHomeController::class, 'dashboard'])->name('admin.home');

Route::group(['prefix' => 'partner-register'], function () {
    Route::get('/list', [AdminPartnerRegisterController::class, 'list'])->name('admin.partner.register.list');
    Route::get('/detail/{id}', [AdminPartnerRegisterController::class, 'detail'])->name('admin.partner.register.detail');
});
