<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Dwelling\DwellingAPIController;
use App\Http\Controllers\Neighbor\NeighborAPIController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

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
    return "Hello World";
});

Route::get('/home', function () {
    $folios = json_decode(File::get(database_path('jsons/db.json')), true);

    return view('home', compact('folios'));
})->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/contributions', [AdminController::class, 'contributions'])->name('admin.contributions');
    Route::get('/contributions/{uuid}', [AdminController::class, 'showContribution'])->name('admin.contributions.show');
    Route::get('/dwellings', [AdminController::class, 'dwellings'])->name('admin.dwellings');
    Route::get('/dwellings/{uuid}', [AdminController::class, 'showDwelling'])->name('admin.dwellings.show');
    Route::get('/neighbors', [AdminController::class, 'neighbors'])->name('admin.neighbors');
    Route::get('/neighbors/{uuid}', [AdminController::class, 'showNeighbor'])->name('admin.neighbors.show');
});

Route::get('/profe', [DwellingAPIController::class, 'profe']);
Route::get('/acuse', [DwellingAPIController::class, 'acuse']);

Route::get('/profet', [DwellingAPIController::class, 'profet']);
Route::get('/vecinos', [NeighborAPIController::class, 'getNeighbors']);
