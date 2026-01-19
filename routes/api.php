<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ManageVisitsController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('/clients',ClientController::class);

Route::resource('/workers', WorkerController::class);

Route::resource('/visits', VisitController::class);

Route::resource('/services', ServiceController::class);

Route::resource('/service-categories', ServiceCategoryController::class);

Route::post('/manage-visits', [ManageVisitsController::class, 'manageVisits']);

Route::post('/change-visits-services', [ManageVisitsController::class, 'changeVisitsServices']);
