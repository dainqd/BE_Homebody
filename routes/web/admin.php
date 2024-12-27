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

use App\Http\Controllers\admin\AdminAnswerController;
use App\Http\Controllers\admin\AdminCategoryController;
use App\Http\Controllers\admin\AdminContactController;
use App\Http\Controllers\admin\AdminCouponController;
use App\Http\Controllers\admin\AdminHomeController;
use App\Http\Controllers\admin\AdminPartnerRegisterController;
use App\Http\Controllers\admin\AdminQuestionController;
use App\Http\Controllers\admin\AdminRevenueController;
use App\Http\Controllers\admin\AdminSettingController;
use App\Http\Controllers\admin\AdminTermsAndPolicyController;
use App\Http\Controllers\admin\AdminUserController;

Route::get('/dashboard', [AdminHomeController::class, 'dashboard'])->name('admin.home');

Route::get('/revenues/show', [AdminRevenueController::class, 'index'])->name('admin.revenues.show');

Route::group(['prefix' => 'partner-register'], function () {
    Route::get('/list', [AdminPartnerRegisterController::class, 'list'])->name('admin.partner.register.list');
    Route::get('/detail/{id}', [AdminPartnerRegisterController::class, 'detail'])->name('admin.partner.register.detail');
});

Route::group(['prefix' => 'categories'], function () {
    Route::get('/list', [AdminCategoryController::class, 'list'])->name('admin.categories.list');
    Route::get('/detail/{id}', [AdminCategoryController::class, 'detail'])->name('admin.categories.detail');
    Route::get('/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
});

Route::group(['prefix' => 'users'], function () {
    Route::get('/list', [AdminUserController::class, 'list'])->name('admin.users.list');
    Route::get('/detail/{id}', [AdminUserController::class, 'detail'])->name('admin.users.detail');
    Route::get('/create', [AdminUserController::class, 'create'])->name('admin.users.create');
});

Route::group(['prefix' => 'coupons'], function () {
    Route::get('list', [AdminCouponController::class, 'list'])->name('admin.coupons.list');
    Route::get('detail/{id}', [AdminCouponController::class, 'detail'])->name('admin.coupons.detail');
    Route::get('create', [AdminCouponController::class, 'create'])->name('admin.coupons.create');
});

Route::group(['prefix' => 'contacts'], function () {
    Route::get('list', [AdminContactController::class, 'list'])->name('admin.contacts.list');
    Route::get('detail/{id}', [AdminContactController::class, 'detail'])->name('admin.contacts.detail');
    Route::post('update/{id}', [AdminContactController::class, 'update'])->name('admin.contacts.update');
});

Route::group(['prefix' => 'app'], function () {
    Route::get('/setting', [AdminHomeController::class, 'setting'])->name('admin.app.setting');
    Route::post('/setting', [AdminSettingController::class, 'appSetting'])->name('admin.save.setting');
    Route::get('/term-and-policies/list', [AdminTermsAndPolicyController::class, 'list'])->name('admin.app.term.and.policies.list');
    Route::get('/term-and-policies/create', [AdminTermsAndPolicyController::class, 'create'])->name('admin.app.term.and.policies.create');
    Route::post('/term-and-policies/store', [AdminTermsAndPolicyController::class, 'store'])->name('admin.app.term.and.policies.store');
    Route::get('/term-and-policies/detail/{id}', [AdminTermsAndPolicyController::class, 'detail'])->name('admin.app.term.and.policies.detail');
    Route::put('/term-and-policies/update/{id}', [AdminTermsAndPolicyController::class, 'update'])->name('admin.app.term.and.policies.update');
    Route::delete('/term-and-policies/delete/{id}', [AdminTermsAndPolicyController::class, 'delete'])->name('admin.app.term.and.policies.delete');
});

Route::group(['prefix' => 'questions'], function () {
    Route::get('/list', [AdminQuestionController::class, 'list'])->name('admin.qna.questions.list');
    Route::get('/detail/{id}', [AdminQuestionController::class, 'detail'])->name('admin.qna.questions.detail');
    Route::get('/create', [AdminQuestionController::class, 'create'])->name('admin.qna.questions.create');
    Route::post('/store', [AdminQuestionController::class, 'store'])->name('admin.qna.questions.store');
    Route::put('/update/{id}', [AdminQuestionController::class, 'update'])->name('admin.qna.questions.update');
    Route::delete('/delete/{id}', [AdminQuestionController::class, 'delete'])->name('admin.qna.questions.delete');
});

Route::group(['prefix' => 'answers'], function () {
    Route::get('/list', [AdminAnswerController::class, 'list'])->name('admin.qna.answers.list');
    Route::get('/detail/{id}', [AdminAnswerController::class, 'detail'])->name('admin.qna.answers.detail');
    Route::get('/create', [AdminAnswerController::class, 'create'])->name('admin.qna.answers.create');
    Route::post('/store', [AdminAnswerController::class, 'store'])->name('admin.qna.answers.store');
    Route::put('/update/{id}', [AdminAnswerController::class, 'update'])->name('admin.qna.answers.update');
    Route::delete('/delete/{id}', [AdminAnswerController::class, 'delete'])->name('admin.qna.answers.delete');
});
