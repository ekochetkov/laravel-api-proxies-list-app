<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login',        [AuthController::class, 'login']);
    Route::post('registration', [AuthController::class, 'registration']);
    Route::post('logout',       [AuthController::class, 'logout']);
    Route::post('refresh',      [AuthController::class, 'refresh']);
    Route::get ('status',       [AuthController::class, 'status']);
  });
