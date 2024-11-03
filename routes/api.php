<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController; // Correct namespace
use Illuminate\Http\Request;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/articles/search/{title}', [ArticleController::class, 'search']); // Corrected parameter

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/articles', [ArticleController::class, 'store']);
    Route::put('/articles/{id}',[ArticleController::class,'update']);
    Route::delete('/articles/{id}',[ArticleController::class,'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);



   
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
