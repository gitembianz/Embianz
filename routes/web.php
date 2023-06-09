<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
  Route::get('/dashboard', function () {
    return view('dashboard');
  })->name('dashboard');

  //Strore Image


  //Category routes
  route::get('/category', [CategoryController::class, 'category'])->name('category');
  route::post('/add_category', [CategoryController::class, 'add_category']);
  route::get('/edit_category/{id}/', [CategoryController::class, 'edit']);
  route::get('/show_category/{id}/', [CategoryController::class, 'show'])->name('show_category');
  route::post('/delete_category', [CategoryController::class, 'delete']);
  route::post('/category_update/{id}', [CategoryController::class, 'update_category'])->name('category_update');
  route::post('/add_media/{id}', [CategoryController::class, 'add_media'])->name('add_media');
  route::get('/new_categories', [CategoryController::class, 'new'])->name('newcategory');
  route::get('/media/{id}/', [CategoryController::class, 'media'])->name('media');
  route::get('/filesd/{id}/', [CategoryController::class, 'deleteMedia']);
  route::get('/prodd/{id}/', [CategoryController::class, 'deleteProduct']);
  Route::get('/get_all_products', [CategoryController::class, 'getAllProducts']);
  route::post('/delete_selected_products', [CategoryController::class, 'deleteSelectedProducts'])->name('deleteSelectedProducts');
  route::post('/add_selected_products/{id}/', [CategoryController::class, 'addSelectedProducts'])->name('addSelectedProducts');
  route::post('/delete_selected_media', [CategoryController::class, 'deleteSelectedMedia'])->name('deleteSelectedMedia');


  //Products routes
  route::get('/products', [ProductController::class, 'products'])->name('products');
  route::get('/add_products', [ProductController::class, 'add'])->name('add_products');
  route::post('/new_products', [ProductController::class, 'new'])->name('new_products');
  route::get('/show_product/{id}/', [ProductController::class, 'show'])->name('show_product');

  //todolist routes
  route::post('/new', [TodolistController::class, 'store'])->name('store');
  route::delete('/{todolist:id}', [TodolistController::class, 'destroy'])->name('destroy');
});


route::get('/redirect', [HomeController::class, 'redirect'])->middleware('auth', 'verified')->name('redirect');


//Clear Cache

Route::get('/cleareverything', function () {
  $clearcache = Artisan::call('cache:clear');
  echo "Cache cleared<br>";

  $clearview = Artisan::call('view:clear');
  echo "View cleared<br>";

  $cacheconfig = Artisan::call('config:cache');
  echo "Config cache<br>";

  $cacheclear = Artisan::call('config:clear');
  echo "Config clear<br>";

  $optimize = Artisan::call('optimize:clear');
  echo "Optimize clear<br>";
});

//Update app

Route::get('/updateapp', function () {
  exec('composer dump-autoload');
  echo 'composer dump-autoload complete';
});
