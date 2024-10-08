<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\restapi\AuthApi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('index');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('/login', [AuthController::class, 'processLogin'])->name('auth.web.processLogin');
    Route::post('/handle-login', [AuthController::class, 'handleLogin'])->name('auth.web.handleLogin');
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.web.logout');
    Route::post('/login', [AuthApi::class, 'login'])->name('auth.login');
    Route::post('/register', [AuthApi::class, 'register'])->name('auth.register');
    Route::post('/logout', [AuthApi::class, 'logout'])->name('auth.logout');
});

Route::group(['prefix' => 'error'], function () {
    Route::get('/not-found', [ErrorController::class, 'notFound'])->name('error.not.found');
    Route::get('/forbidden', [ErrorController::class, 'forbidden'])->name('error.forbidden');
    Route::get('/unauthorized', [ErrorController::class, 'unauthorized'])->name('error.unauthorized');
});


/* Web routes */
Route::group(['prefix' => 'admin', 'middleware' => ['admin.web']], function () {
    require_once __DIR__ . '/web/admin.php';

});
/* Restapi api */
Route::group(['prefix' => 'api'], function () {
    require_once __DIR__ . '/api/restapi.php';
});

/* Auth api */
Route::group(['prefix' => 'api/auth', 'middleware' => ['auth.api']], function () {
    require_once __DIR__ . '/api/auth.php';
});

/* Partner api */
Route::group(['prefix' => 'api/partner', 'middleware' => ['auth.partner']], function () {
    require_once __DIR__ . '/api/partner.php';
});

/* Admin api */
Route::group(['prefix' => 'api/admin', 'middleware' => ['admin.api']], function () {
    require_once __DIR__ . '/api/admin.php';
});
