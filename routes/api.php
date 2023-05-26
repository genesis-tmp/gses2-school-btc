<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RateController;
use Illuminate\Http\Request;

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

Route::get('/rate', [RateController::class, 'index']);
Route::post('/subscribe', [NotificationController::class, 'subscribe']);
Route::post('/sendEmails', [NotificationController::class, 'notificateAll']);
