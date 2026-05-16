<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RankingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [CommentController::class, 'index']);
Route::get('/game/{num}', [CommentController::class, 'game']);
Route::get('/inequality-game', [CommentController::class, 'inequality_game']);
Route::post('/inequality-game', [CommentController::class, 'inequality_store']);
Route::get('/ranking', [CommentController::class, 'ranking']);
Route::post('/ranking/update', [RankingController::class, 'update']);
Route::get('/ranking/get', [RankingController::class, 'get']);
