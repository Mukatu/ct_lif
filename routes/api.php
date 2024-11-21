<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\DocumentController;
use App\Http\Controllers\API\TrainingController;
use App\Http\Controllers\API\LeaveRequestController;

// Routes publiques
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Users
    Route::apiResource('users', UserController::class);

    // Posts
    Route::apiResource('posts', PostController::class);
    Route::post('posts/{post}/like', [PostController::class, 'like']);
    Route::apiResource('posts.comments', CommentController::class)->shallow();

    // Documents
    Route::apiResource('documents', DocumentController::class);
    Route::get('documents/{document}/download', [DocumentController::class, 'download']);

    // Trainings
    Route::apiResource('trainings', TrainingController::class);

    // Leave Requests
    Route::apiResource('leave-requests', LeaveRequestController::class);
    Route::patch('leave-requests/{leaveRequest}/status', [LeaveRequestController::class, 'updateStatus']);
    Route::get('leave-requests/statistics', [LeaveRequestController::class, 'statistics']);
});