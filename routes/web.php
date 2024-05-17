<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\OrdersController;
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
Route::get('users', [UserController::class, 'index']);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth:web']], function () {

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('users', [UserController::class, 'index'])->name('users');
        Route::get('delete-user/{id}', [UserController::class, 'delete'])->name('delete-user');

        Route::get('books', [BooksController::class, 'index'])->name('books');
        Route::get('add-book', [BooksController::class, 'add'])->name('add-book');
        Route::post('store-book', [BooksController::class, 'store'])->name('store-book');
        Route::get('edit-book/{id}', [BooksController::class, 'edit'])->name('edit-book');
        Route::post('update-book/{id}', [BooksController::class, 'update'])->name('update-book');
        Route::get('delete-book/{id}', [BooksController::class, 'delete'])->name('delete-book');

        Route::get('categories', [CategoriesController::class, 'index'])->name('categories');
        Route::get('add-category', [CategoriesController::class, 'add'])->name('add-category');
        Route::post('store-category', [CategoriesController::class, 'store'])->name('store-category');
        Route::get('edit-category/{id}', [CategoriesController::class, 'edit'])->name('edit-category');
        Route::post('update-category/{id}', [CategoriesController::class, 'update'])->name('update-category');
        Route::get('delete-category/{id}', [CategoriesController::class, 'delete'])->name('delete-category');

        Route::get('orders', [OrdersController::class, 'allOrders'])->name('orders');
        Route::get('view-order/{id}', [OrdersController::class, 'viewOrder'])->name('view-order');
    });

    Route::group(['middleware' => ['role:user']], function () {
        Route::get('profile', [UserController::class, 'edit'])->name('profile');
        Route::get('all-books', [BooksController::class, 'list'])->name('all-books');
        Route::get('cart', [CartController::class, 'index'])->name('cart');
        Route::post('store-cart', [CartController::class, 'store'])->name('store-cart');
        Route::post('remove-item', [CartController::class, 'removeItem'])->name('remove-item');
        Route::get('checkout', [CartController::class, 'checkout'])->name('checkout');
        Route::post('add-address', [CartController::class, 'addAddress'])->name('add-address');
        Route::post('place-order', [OrdersController::class, 'placeOrder'])->name('place-order');
        Route::get('complete-order', [OrdersController::class, 'completeOrder'])->name('complete-order');
        Route::get('cart/count', [CartController::class, 'cartCount'])->name('cart.count');
        Route::get('my-orders', [OrdersController::class, 'myOrders'])->name('my-orders');
    });

});
