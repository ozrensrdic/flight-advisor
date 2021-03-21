<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;

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

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// routes.php
Route::group(array('prefix' => 'cities'), function() {
    Route::get('search', [CityController::class, 'search'])->name('cities.search');
    Route::get('search/results', [CityController::class, 'results'])->name('cities.search.results');
    Route::get('route', [CityController::class, 'route'])->name('cities.route');
    Route::get('route/details', [CityController::class, 'flightDetails'])->name('cities.route.details');
});

Route::resource('cities', CityController::class);

Route::resource('comments', CommentController::class);

