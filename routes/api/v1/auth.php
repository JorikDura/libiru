<?php

use App\Http\Controllers\Api\V1\Auth\DeleteUserImageController;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\Notification\DeleteUserNotificationController;
use App\Http\Controllers\Api\V1\Auth\Notification\IndexUserNotificationController;
use App\Http\Controllers\Api\V1\Auth\Notification\NotificationMarkAsReadController;
use App\Http\Controllers\Api\V1\Auth\RegistrationController;
use App\Http\Controllers\Api\V1\Auth\SendEmailVerificationController;
use App\Http\Controllers\Api\V1\Auth\UploadUserImageController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailByCodeController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/auth'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::post('/login', LoginController::class);
        Route::post('/registration', RegistrationController::class);
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/logout', LogoutController::class);

        Route::group(['prefix' => 'notifications'], function () {
            Route::get('/', IndexUserNotificationController::class);
            Route::post('/mark-as-read', NotificationMarkAsReadController::class);
            Route::delete('/', DeleteUserNotificationController::class);
        });

        Route::group(['prefix' => 'image'], function () {
            Route::post('/', UploadUserImageController::class);
            Route::delete('/', DeleteUserImageController::class);
        });

        Route::group(['prefix' => 'email'], function () {
            Route::post('/verification-code', SendEmailVerificationController::class)
                ->name('verification.send');

            Route::post('/verify-email', VerifyEmailByCodeController::class);
        });
    });
});
