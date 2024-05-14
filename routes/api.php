<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoriesController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::get('/token', [AuthController::class, 'token']);
    Route::get('book/all', [BooksController::class, 'getAll']);
    Route::get('category/all', [CategoriesController::class, 'getAll']);
    Route::get('my-cart', [CartController::class, 'myCart']);
    Route::get('profile', [UserController::class, 'profile']);
    Route::get('cart/count', [CartController::class, 'cartCount']);
    Route::post('remove-item', [CartController::class, 'removeItem']);
});