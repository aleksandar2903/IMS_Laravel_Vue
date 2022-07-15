<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/users', function (Request $request) {
        return User::all();
    });
});

Route::get('/tokens/create', function (Request $request) {
    $token = User::first()->createToken('MyToken');

    return ['token' => $token->plainTextToken];
});

Route::get('/search', [App\Http\Controllers\Api\ProductController::class, 'search'])->name('api.products.search');
Route::get('/products/newest', [App\Http\Controllers\Api\ProductController::class, 'newest'])->name('api.products.newest');
Route::get('/products/popular', [App\Http\Controllers\Api\ProductController::class, 'popular'])->name('api.products.popular');
Route::get('/products/gaming', [App\Http\Controllers\Api\ProductController::class, 'gaming'])->name('api.products.gaming');
Route::get('/autocomplete', [App\Http\Controllers\Api\ProductController::class, 'autocomplete'])->name('api.products.autocomplete');
Route::name('api.')->group(function () {
    Route::apiResource('products', App\Http\Controllers\Api\ProductController::class);
});
