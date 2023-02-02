<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;


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
    route::get('/show_category/{id}/', [CategoryController::class, 'show']);
    route::post('/delete_category', [CategoryController::class, 'delete']);
    route::post('/category_update/{id}', [CategoryController::class, 'update_category'])->name('category_update');
    
    // browse image
    route::get('/get-images', [CategoryController::class, 'browse']);

    
});
route::get('/redirect', [HomeController::class, 'redirect'])->middleware('auth','verified')->name('redirect');


