<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SpecsController;
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

  //Category routes
  route::get('/category', [CategoryController::class, 'category'])->name('category');
  route::post('/add_category', [CategoryController::class, 'add_category']);
  route::get('/show_category/{id}/', [CategoryController::class, 'show'])->name('show_category');
  route::get('/new_categories', [CategoryController::class, 'new'])->name('newcategory');

  //Products routes
  route::get('/products', [ProductController::class, 'products'])->name('products');
  route::get('/add_product', [ProductController::class, 'add'])->name('add_product');
  route::post('/new_products', [ProductController::class, 'new'])->name('new_products');
  route::get('/show_product/{id}/', [ProductController::class, 'show'])->name('show_product');

  //todolist routes
  route::post('/new', [TodolistController::class, 'store'])->name('store');
  route::delete('/{todolist:id}', [TodolistController::class, 'destroy'])->name('destroy');
});

//specs route
route::get('/specs', [SpecsController::class, 'index'])->name('specs');

route::get('/dashboard', [HomeController::class, 'redirect'])->middleware('auth', 'verified')->name('dashboard');


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
  $eventclear = Artisan::call('event:clear');
  echo "event clear<br>";
  $queueclear = Artisan::call('queue:clear');
  echo "queue clear<br>";

  $optimize = Artisan::call('optimize:clear');
  echo "Optimize clear<br>";
});

//Update app

Route::get('/updateapp', function () {
  exec('composer dump-autoload');
  exec('composer update -W');
  echo 'composer dump-autoload and update complete';
});
