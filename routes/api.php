<?php

use App\Http\Controllers\Api\PortApiController;
use Illuminate\Support\Facades\Route;

// Kumpulan Endpoint REST API Manajemen Risiko Pelabuhan Dunia
Route::get('/ports', [PortApiController::class, 'index']);          // 1. Get All
Route::get('/ports/{id}', [PortApiController::class, 'show']);      // 2. Get Detail + AI Sentiment Analysis
Route::post('/ports', [PortApiController::class, 'store']);         // 3. Create
Route::put('/ports/{id}', [PortApiController::class, 'update']);     // 4. Update
Route::delete('/ports/{id}', [PortApiController::class, 'destroy']); // 5. Delete