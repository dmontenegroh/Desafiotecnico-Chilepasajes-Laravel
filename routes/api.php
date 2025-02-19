<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\ActivityController;

Route::get('/v1/dev/instruments', [InstrumentController::class, 'getInstruments']);
Route::get('/v1/dev/instruments/usage', [InstrumentController::class, 'getInstrumentUsagePercentage']);
Route::get('/v1/dev/instruments/instrument', [InstrumentController::class, 'getInstrumentUsagePercentageByName']);
Route::get('/v1/dev/activities', [ActivityController::class, 'getActivityIDs']);
