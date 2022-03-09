<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\HypothesisController;
use App\Http\Controllers\HypothesisStatusController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\TodaysGoalController;
use App\Http\Controllers\Initialize;


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
    Route::get('/initialize', Initialize::class);

    // プロジェクト
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/project', [ProjectController::class, 'store']);
    Route::put('/project/{projectId}', [ProjectController::class, 'update']);
    Route::delete('/project/{projectId}', [ProjectController::class, 'destroy']);

    // 仮説
    Route::get('/project', [HypothesisController::class, 'index']);
    Route::get('/hypothesis/:hypothesisId', [HypothesisController::class, 'show']);
    Route::post('/hypothesis', [HypothesisController::class, 'store']);
    Route::put('/hypothesis/{hypothesisId}', [HypothesisController::class, 'update']);
    Route::delete('/hypothesis/{hypothesisId}', [HypothesisController::class, 'destroy']);

    // ゴール
    Route::get('/goal', [GoalController::class, 'index']);
    Route::post('/goal', [GoalController::class, 'store']);
    Route::delete('/goal/:hypothesisId', [GoalController::class, 'destroy']);

    // 仮説のステータス
    Route::put('/hypothesis/{hypothesisId}/status', [HypothesisStatusController::class, 'update']);
    Route::delete('/hypothesis/{hypothesisId}/status', [HypothesisStatusController::class, 'destroy']);

    // 今日の目標
    Route::put('/hypothesis/{hypothesisId}/todays_goal', [TodaysGoalController::class, 'update']);
    Route::delete('/hypothesis/{hypothesisId}/todays_goal', [TodaysGoalController::class, 'destroy']);
});
