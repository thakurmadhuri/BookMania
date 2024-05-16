<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CategoriesController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/token', [AuthController::class, 'token']);
    Route::get('profile', [UserController::class, 'profile']);

    Route::get('book/all', [BooksController::class, 'getAll']);
    Route::get('category/all', [CategoriesController::class, 'getAll']);

    Route::post('store-cart', [CartController::class, 'store']);
    Route::get('cart/count', [CartController::class, 'cartCount']);
    Route::post('remove-item', [CartController::class, 'removeItem']);
    Route::get('my-cart', [CartController::class, 'myCart']);

    Route::post('add-address', [CartController::class, 'addAddress']);
    Route::post('place-order', [OrdersController::class, 'placeOrder']);
    Route::get('my-orders', [OrdersController::class, 'getMyOrders']);
    Route::get('get-last-order', [OrdersController::class, 'getLastOrder']);

    Route::post('/logout', [AuthController::class, 'logout']);
});