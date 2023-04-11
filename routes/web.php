<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TodolistController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified'])->group(function () {
    Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    //Category routes
    route::get('/category', [CategoryController::class, 'category'])->name('category');
    route::post('/add_category', [CategoryController::class, 'add_category']);
    route::get('/edit_category/{id}/', [CategoryController::class, 'edit']);
    route::get('/show_category/{id}/', [CategoryController::class, 'show'])->name('show_category');
    route::post('/delete_category', [CategoryController::class, 'delete']);
    route::post('/category_update/{id}', [CategoryController::class, 'update_category'])->name('category_update');
    route::get('/new_categories', [CategoryController::class, 'new'])->name('newcategory');


    // browse image
    route::get('/get-images', [CategoryController::class, 'browse']);

    //Products routes
    route::get('/products', [ProductController::class, 'products'])->name('products');
    route::get('/add_products', [ProductController::class, 'add'])->name('add_products');
    route::post('/new_products', [ProductController::class, 'new'])->name('new_products');

    //todolist routes
    route::post('/new', [TodolistController::class, 'store'])->name('store');
    route::delete('/{todolist:id}', [TodolistController::class, 'destroy'])->name('destroy');

});
route::get('/redirect', [HomeController::class, 'redirect'])->middleware('auth','verified')->name('redirect');


