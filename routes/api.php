<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get( '/unauthenticated', function () { return ['message' => 'Unauthenticated']; })->name('unauthenticated');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register',[UserController::class,'register']);

Route::post('login',[UserController::class,'loginUser']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});





