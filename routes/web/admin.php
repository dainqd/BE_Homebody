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
use App\Http\Controllers\admin\AdminUserController;

Route::get('/dashboard', [AdminHomeController::class, 'dashboard'])->name('admin.home');

Route::group(['prefix' => 'partner-register'], function () {
    Route::get('/list', [AdminPartnerRegisterController::class, 'list'])->name('admin.partner.register.list');
    Route::get('/detail/{id}', [AdminPartnerRegisterController::class, 'detail'])->name('admin.partner.register.detail');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/list', [AdminUserController::class, 'list'])->name('admin.users.list');
    Route::get('/detail/{id}', [AdminUserController::class, 'detail'])->name('admin.users.detail');
    Route::get('/create', [AdminUserController::class, 'create'])->name('admin.users.create');
});
