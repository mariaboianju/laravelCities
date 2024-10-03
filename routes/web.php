<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShowCitiesController;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/my', 'ShowCitiesController@showCities');

//Route::get('/you', function () {
 //   return view('welcome');
//});

Route::get('/cities', [ShowCitiesController::class, 'showCities']);
