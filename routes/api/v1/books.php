<?php

use App\Http\Controllers\Api\V1\Book\AddBookToUserListController;
use App\Http\Controllers\Api\V1\Book\Comment\DeleteBookCommentController;
use App\Http\Controllers\Api\V1\Book\Comment\IndexBookCommentController;
use App\Http\Controllers\Api\V1\Book\Comment\StoreBookCommentController;
use App\Http\Controllers\Api\V1\Book\DeleteBookController;
use App\Http\Controllers\Api\V1\Book\DeleteBookImageController;
use App\Http\Controllers\Api\V1\Book\IndexBookController;
use App\Http\Controllers\Api\V1\Book\IndexBookStatusController;
use App\Http\Controllers\Api\V1\Book\ShowBookController;
use App\Http\Controllers\Api\V1\Book\ShowBookScoreController;
use App\Http\Controllers\Api\V1\Book\StoreBookController;
use App\Http\Controllers\Api\V1\Book\UpdateBookController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/books'], function () {
    Route::get('/', IndexBookController::class);
    Route::get('/{book}', ShowBookController::class);
    Route::get('/{book}/scores', ShowBookScoreController::class);
    Route::get('/{book}/statuses', IndexBookStatusController::class);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/{book}/lists', AddBookToUserListController::class);
        Route::group(['middleware' => 'can:role,'.User::class], function () {
            Route::post('/', StoreBookController::class);
            Route::match(['put', 'patch'], '/{book}', UpdateBookController::class);
            Route::delete('/{book}', DeleteBookController::class);
            Route::delete('/{book}/image', DeleteBookImageController::class);
        });
    });

    Route::group(['prefix' => '{book}/comments'], function () {
        Route::get('/', IndexBookCommentController::class);
        Route::group(['middleware' => 'auth:sanctum'], function () {
            Route::post('/', StoreBookCommentController::class);
            Route::delete('/{comment}', DeleteBookCommentController::class)
                ->can('delete', 'comment');
        });
    });
});
