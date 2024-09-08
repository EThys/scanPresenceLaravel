<?php

use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CsvController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register',[UserController::class, 'register']);
Route::post('/login',[UserController::class, 'login']);
Route::post('/test',[TicketController::class, 'store']);
Route::post('/scan',[TicketController::class, 'scanBest']);
Route::post('/uploadCsv', [CsvController::class, 'upload']);
Route::post('/changePassword', [PasswordChangeController::class, 'changePassword']);

Route::group(['middleware'=>["auth:sanctum"]],function(){
    Route::get('/logout',[UserController::class, 'logout']);
    Route::post('/changePassword',[PasswordChangeController::class, 'changePassword']);
});
