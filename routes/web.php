<?php

use App\Http\Controllers\BooksController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriesController;

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
    return view('welcome');
});

Auth::routes();
Route::get('users',  [UserController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () { 
    // Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('users',  [UserController::class, 'index'])->name('users');

        Route::get('books',  [BooksController::class, 'index'])->name('books');
        Route::get('add-book',  [BooksController::class, 'add'])->name('add-book');
        Route::post('store-book',  [BooksController::class, 'store'])->name('store-book');
        Route::get('edit-book/{id}', [BooksController::class, 'edit'])->name('edit-book');
        Route::post('update-book/{id}', [BooksController::class, 'update'])->name('update-book');
        Route::get('delete-book/{id}', [BooksController::class, 'delete'])->name('delete-book');

        Route::get('categories',  [CategoriesController::class, 'index'])->name('categories');
        Route::get('add-category',  [CategoriesController::class, 'add'])->name('add-category');
        Route::post('store-category',  [CategoriesController::class, 'store'])->name('store-category');
        Route::get('edit-category/{id}', [CategoriesController::class, 'edit'])->name('edit-category');
        Route::post('update-category/{id}', [CategoriesController::class, 'update'])->name('update-category');
        Route::get('delete-category/{id}', [CategoriesController::class, 'delete'])->name('delete-category');
     });

     Route::group(['middleware' => ['role:user']], function () {
        Route::get('profile',  [UserController::class, 'profile'])->name('profile');
        Route::get('all-books',  [BooksController::class, 'list'])->name('all-books');
        Route::get('cart',  [CartController::class, 'index'])->name('cart');
        Route::post('store-cart',  [CartController::class, 'store'])->name('store-cart');
     });

 });
