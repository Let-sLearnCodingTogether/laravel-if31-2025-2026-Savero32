<?php

use App\Http\Controllers\Auth\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

route::middleware('guest')->group(function(){
    route::post('register', [AuthenticationController::class, 'register']);
    route::post('login', [AuthenticationController::class, 'login']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
