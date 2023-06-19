<?php

use Ensue\Snap\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('/upload-resources', [FileController::class,'uploadResources'])->middleware('auth:api');
Route::post('/upload-resources/multiple', [FileController::class,'uploadMultipleResources'])->middleware('auth:api');

