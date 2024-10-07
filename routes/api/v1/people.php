<?php

use App\Http\Controllers\Api\V1\Person\Comment\DeletePersonCommentController;
use App\Http\Controllers\Api\V1\Person\Comment\IndexPersonCommentController;
use App\Http\Controllers\Api\V1\Person\Comment\StorePersonCommentController;
use App\Http\Controllers\Api\V1\Person\DeletePersonController;
use App\Http\Controllers\Api\V1\Person\DeletePersonImageController;
use App\Http\Controllers\Api\V1\Person\IndexPersonController;
use App\Http\Controllers\Api\V1\Person\ShowPersonController;
use App\Http\Controllers\Api\V1\Person\StorePersonController;
use App\Http\Controllers\Api\V1\Person\SubscribePersonController;
use App\Http\Controllers\Api\V1\Person\UpdatePersonController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/people'], function () {
    Route::get('/', IndexPersonController::class);
    Route::get('/{person}', ShowPersonController::class);
    Route::group(['middleware' => ['auth:sanctum', 'can:role,'.User::class]], function () {
        Route::post('/', StorePersonController::class);
        Route::post('/{person}/subscribe', SubscribePersonController::class);
        Route::match(['put', 'patch'], '/{person}', UpdatePersonController::class);
        Route::delete('/{person}', DeletePersonController::class);
        Route::delete('{person}/image', DeletePersonImageController::class);
    });

    Route::group(['prefix' => '{person}/comments'], function () {
        Route::get('/', IndexPersonCommentController::class);
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/', StorePersonCommentController::class);
            Route::delete('/{comment}', DeletePersonCommentController::class)
                ->can('delete', 'comment');
        });
    });
});
