<?php

use Illuminate\Support\Facades\Route;


Route::get('/{any}', function () {
    return view('welcome'); // or 'index', 'app', etc.
})->where('any', '.*');
