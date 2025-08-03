<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\ActivityController;

Route::middleware('api.key')->group(function () {
    // Organizations
    Route::get('/organizations/search', [OrganizationController::class, 'searchByName']);
    Route::get('/organizations/radius', [OrganizationController::class, 'getByRadius']);
    Route::get('/organizations/area', [OrganizationController::class, 'getByArea']);
    Route::get('/organizations/building/{buildingId}', [OrganizationController::class, 'getByBuilding']);
    Route::get('/organizations/activity/{activityId}', [OrganizationController::class, 'getByActivity']);
    Route::get('/organizations/activity/{activityId}/descendants', [OrganizationController::class, 'getByActivityWithDescendants']);
    Route::get('/organizations/{id}', [OrganizationController::class, 'show']);

    // Buildings
    Route::get('/buildings', [BuildingController::class, 'index']);
    Route::get('/buildings/{id}', [BuildingController::class, 'show']);

    // Activities
    Route::get('/activities/tree', [ActivityController::class, 'tree']);
    Route::get('/activities', [ActivityController::class, 'index']);
    Route::get('/activities/{id}', [ActivityController::class, 'show']);
}); 