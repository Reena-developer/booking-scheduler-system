<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ServiceController;
use App\Http\Controllers\Api\V1\SpecialDayController;
use App\Http\Controllers\Api\V1\WorkingHoursController;
use App\Http\Controllers\Api\V1\AvailableSlotsController;
use App\Http\Controllers\Api\V1\AppointmentController;

Route::prefix('v1')->group(function () {
    
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        // Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('services')->controller(ServiceController::class)->group(function () {
            Route::get('/', 'index')->withoutMiddleware(['auth:sanctum']);
            Route::post('/', 'store');
            Route::get('/{service}', 'show')->withoutMiddleware(['auth:sanctum']);
            Route::put('/{service}', 'update');
            Route::patch('/{service}', 'update');
            Route::delete('/{service}', 'destroy');
        });

        Route::prefix('working-hours')->group(function () {

            Route::controller(WorkingHoursController::class)->group(function () {
                Route::post('configure-week', 'configureWeek');
                Route::post('configure-day', 'configureSingleDay');
                Route::get('/', 'getWeeklySchedule');
            });

            Route::apiResource('special-days', SpecialDayController::class);
        });
        
        // Route::get('appointments', [AppointmentController::class, 'index']);
        
    
    });
    Route::post('/available-slots', [AvailableSlotsController::class, 'index']);
    Route::post('appointments/book', [AppointmentController::class, 'store']);

});
