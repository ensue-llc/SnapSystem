<?php

use Ensue\Snap\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post('/upload-resources', [
    'use'=>'FileController@uploadResources',
    'as' =>'upload-resources',
    'middleware' => 'auth:api']);

Route::post('/upload-resources/multiple', [
    'use'=>'FileController@uploadMultipleResources',
    'as' =>'upload-resources',
    'middleware' => 'auth:api']);

