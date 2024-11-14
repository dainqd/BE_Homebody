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

use App\Http\Controllers\admin\AdminUserController;
use App\Http\Controllers\restapi\admin\AdminCategoryApi;
use App\Http\Controllers\restapi\admin\AdminPartnerRegisterApi;
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

Route::group(['prefix' => 'users'], function () {
    Route::get('/list', [AdminUserApi::class, 'list'])->name('api.admin.users.list');
    Route::get('/detail/{id}', [AdminUserApi::class, 'detail'])->name('api.admin.users.detail');
    Route::post('/create', [AdminUserApi::class, 'create'])->name('api.admin.users.create');
    Route::post('/update/{id}', [AdminUserApi::class, 'update'])->name('api.admin.users.update');
    Route::delete('/delete/{id}', [AdminUserApi::class, 'delete'])->name('api.admin.users.delete');
});
