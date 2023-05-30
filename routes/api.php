<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PersonController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('insert',[UserController::class,'insert']);

Route::post('login',[UserController::class,'loginUser']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();

});


Route::group(['middleware' => 'auth:sanctum'],function(){

    Route::get('person',[PersonController::class,'index']);
    Route::post('person',[PersonController::class,'store']);
    Route::get('person/{person}',[PersonController::class,'show']);
    Route::post('person/{person}',[PersonController::class,'update']);
    Route::delete('person/{person}',[PersonController::class,'destroy']);

    Route::get('user',[UserController::class,'select']);
    Route::post('logout',[UserController::class,'logout']);

});


