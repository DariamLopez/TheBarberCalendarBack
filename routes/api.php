<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource('/clients',ClientController::class);

Route::resource('/workers', WorkerController::class);

route::resource('/visits', VisitController::class);

route::resource('/services', ServiceController::class);
