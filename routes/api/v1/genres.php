<?php

use App\Http\Controllers\Api\V1\Genre\IndexGenreController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/genres'], function () {
    Route::get('/', IndexGenreController::class);
});
