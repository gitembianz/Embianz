<?php

use Illuminate\Support\Facades\Route;

// Test page - cacheable, no sessions
Route::get('/test3', function () {
    return view('test3');
});
