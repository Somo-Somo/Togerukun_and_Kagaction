<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HypothesisController;
use App\Http\Controllers\CurrentGoalController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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


Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/auth_status', [LoginController::class, 'authStatus']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    // プロジェクト
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/project', [ProjectController::class, 'store']);
    Route::put('/project/{projectId}', [ProjectController::class, 'update']);
    Route::delete('/project/{projectId}', [ProjectController::class, 'destroy']);

    // 仮説
    Route::get('/hypotheses', [HypothesisController::class, 'index']);
    Route::get('/hypothesis/:hypothesisId', [HypothesisController::class, 'show']);
    Route::post('/hypothesis/:hypothesisId', [HypothesisController::class, 'store']);
    Route::put('/hypothesis/:hypothesisId', [HypothesisController::class, 'update']);
    Route::delete('/hypothesis/{hypothesisId}', [HypothesisController::class, 'destroy']);

    // ゴール
    Route::post('/hypothesis/goal', [GoalController::class, 'store']);
    Route::delete('/hypothesis/goal/:hypothesisId', [GoalController::class, 'destroy']);

    // 現在の目標
    Route::get('/current_goals', [HypothesisController::class, 'index']);
    Route::post('/current_goal/{hypothesisId}', [HypothesisController::class, 'store']);
    Route::delete('/current_goal/{hypothesisId}', [HypothesisController::class, 'destroy']);
});
