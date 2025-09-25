<?php

use App\Http\Controllers\Auth\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotController;

route::middleware('guest')->group(function(){
    route::post('register', [AuthenticationController::class, 'register']);
    route::post('login', [AuthenticationController::class, 'login']);
});

// midlleware untuk user yg sudah login
Route::middleware('auth:sanctum')->group(function (){
    route::post('logout', [AuthenticationController::class,'logout']);
    Route::resource('spot',SpotController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
Route::get('/spots', [SpotController::class, 'index']);
})->middleware('auth:sanctum');


