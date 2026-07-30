<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\PortTrackerController;
use App\Http\Controllers\Api\RecommendationController;

/*
|--------------------------------------------------------------------------
| API Routes: PT. Digestex Global Intelligence v2.0
|--------------------------------------------------------------------------
|
| Laravel automatically applies the /api prefix.
|
| Canonical namespace:
|
| /api/v2/*
|
|--------------------------------------------------------------------------
*/

Route::prefix('v2')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Port Tracker
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/port-tracker/stream-input',
        [PortTrackerController::class, 'storeFeedData']
    );

    /*
    |--------------------------------------------------------------------------
    | Early Warning System
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/ews/domestic-status',
        [PortTrackerController::class, 'getLiveEwsStatus']
    );

    /*
    |--------------------------------------------------------------------------
    | DIGESTEX Recommendation Intelligence
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/recommendations/companies/{company}',
        [RecommendationController::class, 'index']
    );
});