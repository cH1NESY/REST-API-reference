<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\ActivityController;

Route::middleware('api.key')->group(function () {
    // Organizations
    Route::get('/organizations', [OrganizationController::class, 'index']);
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show']);


});
