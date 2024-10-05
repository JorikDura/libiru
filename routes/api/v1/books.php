<?php

use App\Http\Controllers\Api\V1\Book\Comment\IndexBookCommentController;
use App\Http\Controllers\Api\V1\Book\Comment\StoreBookCommentController;
use App\Http\Controllers\Api\V1\Book\DeleteBookController;
use App\Http\Controllers\Api\V1\Book\DeleteBookImageController;
use App\Http\Controllers\Api\V1\Book\IndexBookController;
use App\Http\Controllers\Api\V1\Book\ShowBookController;
use App\Http\Controllers\Api\V1\Book\ShowBookScoreController;
use App\Http\Controllers\Api\V1\Book\StoreBookController;
use App\Http\Controllers\Api\V1\Book\UpdateBookController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/books'], function () {
    Route::get('/', IndexBookController::class);
    Route::get('/{book}', ShowBookController::class);
    Route::get('/{book}/scores', ShowBookScoreController::class);
    Route::post('/', StoreBookController::class);
    Route::match(['put', 'patch'], '/{book}', UpdateBookController::class);
    Route::delete('/{book}', DeleteBookController::class);
    Route::delete('/{book}/image', DeleteBookImageController::class);

    Route::group(['prefix' => '{book}/comments'], function () {
        Route::get('/', IndexBookCommentController::class);
        Route::post('/', StoreBookCommentController::class)->middleware('auth:sanctum');
    });
});
