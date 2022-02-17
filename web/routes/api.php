<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HypothesisController;
use App\Http\Controllers\CurrentGoalController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    // return $request->user();
    return 'auth';
})->name('login');

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

// プロジェクト
Route::get('/projects', [ProjectController::class, 'index']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::put('/projects/{projectId}', [ProjectController::class, 'update']);
Route::delete('/projects/{projectId}', [ProjectController::class, 'destroy']);

// 仮説
Route::get('/projects/:id', [HypothesisController::class, 'index']);
Route::get('/hypothesis/:hypothesisId', [HypothesisController::class, 'show']);
Route::post('/hypothesis/{hypothesisId}', [HypothesisController::class, 'store']);
Route::put('/hypothesis/{hypothesisId}', [HypothesisController::class, 'update']);
Route::delete('/hypothesis/{hypothesisId}', [HypothesisController::class, 'destroy']);

// 現在の目標
Route::get('/current_goals', [HypothesisController::class, 'index']);
Route::post('/current_goal/{hypothesisId}', [HypothesisController::class, 'store']);
Route::delete('/current_goal/{hypothesisId}', [HypothesisController::class, 'destroy']);