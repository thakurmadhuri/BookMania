<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\CategoriesController;


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/books', [BooksController::class, 'getAll']);
    Route::get('/categories', [CategoriesController::class, 'getAll']);
});