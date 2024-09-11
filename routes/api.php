<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    AuthController,
    CityController,
    ProductController,
    SearchController,
    TagController,
    SizeController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
    Route::post('logout', 'logout')->name('auth.logout')->middleware('auth:api');
    Route::get('user', 'user')->name('auth.user')->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::get('tags/popular', [TagController::class, 'popular'])->name('tags.popular');
    Route::apiResource('tags', TagController::class)->only(['index']);
    Route::apiResource('cities', CityController::class)->only('index');
    Route::apiResource('sizes', SizeController::class)->only('index');

    Route::prefix('search')->controller(SearchController::class)->group(function () {
        Route::get('global', 'global')->name('search.global');
        Route::get('product', 'product')->name('search.product');
    });
});
