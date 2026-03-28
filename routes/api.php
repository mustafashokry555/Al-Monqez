<?php

use App\Http\Controllers\Api\Main\AuthController;
use App\Http\Controllers\Api\Main\MainController;
use App\Http\Controllers\Api\Main\ProfileController;


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








Route::middleware('change_language')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'signup'])->name('api.auth.signup');

    Route::post('/forgetPassword', [AuthController::class, 'forgetPassword']);
    Route::post('/checkResetPasswordCode', [AuthController::class, 'checkResetPasswordCode']);
    Route::post('/resetPassword', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/resendVerifyingOTP', [AuthController::class, 'resendVerifyingOTP']);
        Route::post('/verifyAccount', [AuthController::class, 'verifyAccount']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    Route::get('/site-settings', [MainController::class, 'siteSettings']);
    Route::get('/terms', [MainController::class, 'terms']);
    Route::get('/about-us', [MainController::class, 'abouts']);
    Route::post('/contact-us', [MainController::class, 'contactUs']);

    Route::middleware('auth:sanctum', 'is_verified')->group(function () {
        Route::post('/update-activity-log', [ProfileController::class, 'updateActivityLog']);

        Route::group(['prefix' => '/profile', 'as' => 'api.profile.'], function () {
            Route::get('/', [ProfileController::class, 'index'])->withoutMiddleware('is_verified')->name('index');
            Route::post('/update', [ProfileController::class, 'update'])->name('update');
            Route::post('/delete-image', [ProfileController::class, 'DeleteVehicleEquipmentImage']);
            Route::post('/delete', [ProfileController::class, 'delete']);
        });

        Route::group(['prefix' => '/notifications'], function () {
            Route::get('/', [ProfileController::class, 'notifications']);
        });
    });
});
