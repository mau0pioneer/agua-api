<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Collector\CollectorAPIController;
use App\Http\Controllers\Contribution\ContributionAPIController;
use App\Http\Controllers\Dwelling\DwellingAPIController;
use App\Http\Controllers\DwellingNeighbor\DwellingNeighborAPIController;
use App\Http\Controllers\Map\MapAPIController;
use App\Http\Controllers\Neighbor\NeighborAPIController;
use App\Http\Controllers\Signature\SignatureAPIController;
use App\Http\Controllers\Street\StreetAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/test', [DwellingAPIController::class, 'getDwellings2']);
Route::get('/send', [DwellingAPIController::class, 'send']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::group(['middleware' => 'jwt'], function () {
        Route::get('/logout', [LogoutController::class, 'logout']);
        Route::get('/user', [LoginController::class, 'getUserByToken']);
    });
});

Route::group([], function () {
    // contributions
    Route::group(['prefix' => 'contributions'], function () {
        Route::get('/', [ContributionAPIController::class, 'index']);
        Route::get('/{uuid}', [ContributionAPIController::class, 'show']);
        Route::get('/folio/{folio}', [ContributionAPIController::class, 'showByFolio']);
    });

    Route::group(['prefix' => 'streets'], function () {
        Route::get('/', [StreetAPIController::class, 'index']);
        Route::get('/search', [StreetAPIController::class, 'search']);
        Route::get('/{uuid}', [StreetAPIController::class, 'show']);
        Route::get('/{uuid}/street-numbers', [StreetAPIController::class, 'getStreeNumbers']);
        Route::get('/{uuid}/dwellings', [StreetAPIController::class, 'getDwellings']);
    });

    // dwelings routes group
    Route::group(['prefix' => 'dwellings'], function () {
        Route::get('/print', [DwellingAPIController::class, 'getDwellings']);
        Route::post('/address', [DwellingAPIController::class, 'findFromAddress']);
        Route::get('/', [DwellingAPIController::class, 'index']);
        Route::get('/{uuid}', [DwellingAPIController::class, 'show']);
        Route::put('/{uuid}', [DwellingAPIController::class, 'update']);
        Route::get('/{uuid}/title', [DwellingAPIController::class, 'getTitle']);
        Route::get('/{uuid}/periods', [DwellingAPIController::class, 'getPeriods']);
        Route::get('/{uuid}/pending-periods', [DwellingAPIController::class, 'getPendingPeriods']);
        Route::get('/{uuid}/contributions', [DwellingAPIController::class, 'getContributions']);
        Route::get('/{uuid}/neighbors', [DwellingAPIController::class, 'getNeighbors']);
        Route::post('/{uuid}/period', [DwellingAPIController::class, 'storePeriod']);
        Route::get('/{uuid}/contributions/neighbors', [DwellingAPIController::class, 'getNeighborsFromContributions']);
        Route::patch('/{uuid}/inhabited', [DwellingAPIController::class, 'changeInhabited']);
        Route::get('/{uuid}/neighbors-signatures', [DwellingAPIController::class, 'getNeigborsBySignatures']);
        Route::post('/{uuid}/contributions', [DwellingAPIController::class, 'storeContribution']);
        Route::get('/{uuid}/last-contribution', [DwellingAPIController::class, 'getLastContribution']);

        Route::post('/{uuid}/neighbors', [DwellingAPIController::class, 'storeNeighbor']);
    });

    // neighbors routes group
    Route::group(['prefix' => 'neighbors'], function () {
        Route::get('/access', [NeighborAPIController::class, 'getAccessCode']);
        Route::get('/', [NeighborAPIController::class, 'index']);
        Route::post('/', [NeighborAPIController::class, 'store']);
        Route::get('/{uuid}', [NeighborAPIController::class, 'show']);
        Route::get('/{uuid}/fullname', [NeighborAPIController::class, 'getFullname']);
        Route::get('/{uuid}/phone-number', [NeighborAPIController::class, 'getPhoneNumber']);
        Route::post('/{uuid}/phone-number', [NeighborAPIController::class, 'updatePhoneNumber']);
        
        Route::put('/{uuid}', [NeighborAPIController::class, 'update']);
        Route::delete('/{uuid}', [NeighborAPIController::class, 'destroy']);
    });

    Route::group(['prefix' => 'collectors'], function () {
        Route::get('/', [CollectorAPIController::class, 'index']);
        Route::post('/contribution', [CollectorAPIController::class, 'storeContribution']);
    });

    Route::group(['prefix' => 'dwelling-neighbors'], function () {
        Route::post('/', [DwellingNeighborAPIController::class, 'store']);
        Route::put('/{uuid}', [DwellingNeighborAPIController::class, 'update']);
    });

    Route::group(['prefix' => 'map'], function () {
        Route::get('/inhabiteds', [MapAPIController::class, 'getInhabiteds']);
        Route::get('/contributions', [MapAPIController::class, 'getContributions']);
        Route::get('/contributions-none', [MapAPIController::class, 'getNoneContributions']);
    });

    Route::group(['prefix' => 'signatures'], function () {
        Route::get('/', [SignatureAPIController::class, 'index']);
    });
});


Route::post('/access-code', [DwellingAPIController::class, 'getDataByAccessCode']);
