<?php

use App\Http\Controllers\Api\V1\Post\Comment\DeletePostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\IndexPostCommentController;
use App\Http\Controllers\Api\V1\Post\Comment\StorePostCommentController;
use App\Http\Controllers\Api\V1\Post\DeletePostController;
use App\Http\Controllers\Api\V1\Post\DeletePostImageController;
use App\Http\Controllers\Api\V1\Post\IndexPostController;
use App\Http\Controllers\Api\V1\Post\ShowPostController;
use App\Http\Controllers\Api\V1\Post\StorePostController;
use App\Http\Controllers\Api\V1\Post\UpdatePostController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/posts'], function () {
    Route::get('/', IndexPostController::class);
    Route::get('/{postId}', ShowPostController::class);
    Route::group(['middleware' => ['auth:sanctum', 'can:role,'.User::class]], function () {
        Route::post('/', StorePostController::class);
        Route::match(['put', 'patch'], '/{post}', UpdatePostController::class);
        Route::delete('/{post}', DeletePostController::class);
        Route::delete('{post}/image', DeletePostImageController::class);
    });
    Route::group(['prefix' => '{post}/comments'], function () {
        Route::get('/', IndexPostCommentController::class);
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/', StorePostCommentController::class);
            Route::delete('/{comment}', DeletePostCommentController::class)
                ->can('delete', 'comment');
        });
    });
});
