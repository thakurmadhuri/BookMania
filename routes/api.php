<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoriesController;

// Route::get('book/all', [BooksController::class, 'getAll']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/token', [AuthController::class, 'token']);
    Route::get('book/all', [BooksController::class, 'getAll']);
    Route::get('category/all', [CategoriesController::class, 'getAll']);
});