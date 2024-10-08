<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['cors'])->group(function () {
    Route::apiResource('courses', \App\Http\Controllers\API\CourseController::class);
    Route::get('/courses/{slug}/lessons', [\App\Http\Controllers\API\CourseController::class, 'getLessons']);
    Route::apiResource('lessons', \App\Http\Controllers\API\LessonController::class);
    Route::get('/lessons/{id}/lectures', [\App\Http\Controllers\API\LessonController::class, 'getLectures']);
    Route::apiResource('lectures', \App\Http\Controllers\API\LectureController::class);
    Route::post('/videos', [\App\Http\Controllers\API\VideoController::class, 'store']);
});
