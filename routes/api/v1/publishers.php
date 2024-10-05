<?php

use App\Http\Controllers\Api\V1\Publisher\DeletePublisherController;
use App\Http\Controllers\Api\V1\Publisher\DeletePublisherImageController;
use App\Http\Controllers\Api\V1\Publisher\IndexPublisherController;
use App\Http\Controllers\Api\V1\Publisher\ShowPublisherController;
use App\Http\Controllers\Api\V1\Publisher\StorePublisherController;
use App\Http\Controllers\Api\V1\Publisher\UpdatePublisherController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1/publishers'], function () {
    Route::get('/', IndexPublisherController::class);
    Route::get('/{publisher}', ShowPublisherController::class);
    Route::post('/', StorePublisherController::class);
    Route::match(['put', 'patch'], '{publisher}', UpdatePublisherController::class);
    Route::delete('/{publisher}', DeletePublisherController::class);
    Route::delete('/{publisher}/image', DeletePublisherImageController::class);
});
