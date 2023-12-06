<?php

use Illuminate\Support\Facades\Route;

Route::get('/velodome', function () {
    return 'Welcome to Velodome';
});
Route::get('/velodome/api-generator', 'Velodome\Velodome\Controllers\VelodomeAPIGenerator@index');
Route::post('/velodome/api-generator/analize', 'Velodome\Velodome\Controllers\VelodomeAPIGenerator@analize');
Route::post('/velodome/api-generator/generate', 'Velodome\Velodome\Controllers\VelodomeAPIGenerator@generate');

// Do not delete this comment
