<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PortTrackerController;

/*

|--------------------------------------------------------------------------
| API Routes: PT. Digestex Global Intelligence v2.0
|--------------------------------------------------------------------------
*/

// Laravel secara otomatis membungkus rute ini dengan prefix '/api'
Route::prefix('v2')->group(function () {
    
    // Jalur pipa penerima resmi kiriman data kontainer logistik dari skrip Python
    Route::post('/port-tracker/stream-input', [PortTrackerController::class, 'storeFeedData']);
    
});