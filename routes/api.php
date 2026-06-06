<?php

use App\Http\Controllers\Api\Admin\Cars\CarController;
use App\Http\Controllers\Api\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Api\Admin\Notifications\NotificationController as AdminNotificationController;
use App\Http\Controllers\Api\Admin\Orders\InspectionController;
use App\Http\Controllers\Api\Admin\Orders\OrdersManagement;
use App\Http\Controllers\Api\Admin\Orders\WorkProgressController;
use App\Http\Controllers\Api\Admin\Services\ServiceController;
use App\Http\Controllers\Api\Admin\Users\UserManagementController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\UpdatePasswordController;
use App\Http\Controllers\Api\User\Addresses\UserAddressController;
use App\Http\Controllers\Api\User\Cars\UserCarController;
use App\Http\Controllers\Api\User\Notifications\NotificationController as UserNotificationController;
use App\Http\Controllers\Api\User\Orders\OrderController;
use App\Http\Controllers\Api\User\Phones\UserPhoneController;
use App\Http\Controllers\Api\User\Profile\UserProfileController;
use App\Http\Controllers\Api\User\Services\ServicesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// ========================================
// Test Route
// ========================================
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ========================================
// Public Auth Routes
// ========================================
Route::prefix('auth')->group(function () {

    // Registration
    Route::post('register', RegisterController::class);

    // Login
    Route::post('login', [LoginController::class, 'login']);

    // Forgot Password Flow
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendCode']);
    Route::post('check-reset-code', [ForgotPasswordController::class, 'checkCode']);
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);

    // Email Verification Flow
    Route::prefix('verify')->group(function () {
        Route::post('send-code', [EmailVerificationController::class, 'sendCode']);
        Route::post('check-code', [EmailVerificationController::class, 'checkCode']);
        Route::post('resend-code', [EmailVerificationController::class, 'reSendCode']);
    });
});

// ========================================
// Protected User Routes
// ========================================
Route::prefix('user')->middleware('auth:sanctum')->group(function () {

    // Auth Operations
    Route::delete('logout', [LoginController::class, 'logout']);
    Route::delete('logout-all-devices', [LoginController::class, 'logoutAllDevices']);
    Route::post('update-password', UpdatePasswordController::class);

    // User Profile
    Route::get('profile', [UserProfileController::class, 'view']);
    Route::put('profile', [UserProfileController::class, 'update']);

    // User Phones
    Route::prefix('phones')->group(function () {
        Route::get('/', [UserPhoneController::class, 'index']);
        Route::post('/', [UserPhoneController::class, 'store']);
        Route::delete('{id}', [UserPhoneController::class, 'destroy']);
    });

    // User Addresses
    Route::prefix('addresses')->group(function () {
        Route::get('/', [UserAddressController::class, 'index']);
        Route::post('/', [UserAddressController::class, 'store']);
        Route::put('{id}', [UserAddressController::class, 'update']);
        Route::delete('{id}', [UserAddressController::class, 'destroy']);
    });

    // User Cars
    Route::prefix('cars')->group(function () {
        Route::get('/', [UserCarController::class, 'index']);
        Route::post('/', [UserCarController::class, 'store']);
        Route::put('{id}', [UserCarController::class, 'update']);
        Route::delete('{id}', [UserCarController::class, 'destroy']);
    });

    // User Services (Read Only)
    Route::prefix('services')->group(function () {
        Route::get('/', [ServicesController::class, 'index']);
        Route::get('{id}', [ServicesController::class, 'show']);
    });

    // User Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('{id}', [OrderController::class, 'show']);
        Route::put('{id}', [OrderController::class, 'update']);
        Route::patch('{id}/cancel', [OrderController::class, 'destroy']);
    });

    // User Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [UserNotificationController::class, 'index']);
        Route::get('unread-count', [UserNotificationController::class, 'unreadCount']);
        Route::post('{id}/read', [UserNotificationController::class, 'markAsRead']);
        Route::post('read-all', [UserNotificationController::class, 'markAllAsRead']);
        Route::delete('clear-read', [UserNotificationController::class, 'deleteAllRead']);
        Route::delete('{id}', [UserNotificationController::class, 'destroy']);
    });
});

// ========================================
// Admin Routes
// ========================================
Route::prefix('admin')->middleware(['auth:sanctum', 'isAdmin'])->group(function () {

    // Dashboard & Statistics
    Route::get('dashboard/stats', [DashboardController::class, 'index']);

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index']);
        Route::get('{id}', [UserManagementController::class, 'show']);
        Route::put('{id}', [UserManagementController::class, 'update']);
        Route::delete('{id}', [UserManagementController::class, 'destroy']);
        Route::post('{id}/restore', [UserManagementController::class, 'restore']);
        Route::patch('{id}/toggle-block', [UserManagementController::class, 'toggleBlock']);
    });

    // Cars Management
    Route::prefix('cars')->group(function () {
        Route::get('/', [CarController::class, 'index']);
        Route::get('{id}', [CarController::class, 'show']);
        Route::delete('{id}', [CarController::class, 'destroy']);
    });

    // Services Management
    Route::prefix('services')->group(function () {
        Route::get('/', [ServiceController::class, 'index']);
        Route::post('/', [ServiceController::class, 'store']);
        Route::get('{id}', [ServiceController::class, 'show']);
        Route::put('{id}', [ServiceController::class, 'update']);
        Route::delete('{id}', [ServiceController::class, 'destroy']);
        Route::patch('{id}/toggle-status', [ServiceController::class, 'toggleStatus']);
    });

    // Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrdersManagement::class, 'index']);
        Route::get('{id}', [OrdersManagement::class, 'show']);
        Route::patch('{id}/status', [OrdersManagement::class, 'updateStatus']);
        Route::patch('{id}/assign', [OrdersManagement::class, 'assignOrder']);
        Route::post('{id}/cancel', [OrdersManagement::class, 'cancelOrder']);

        // Inspections (nested under order)
        Route::prefix('{order}/inspections')->group(function () {
            Route::get('/', [InspectionController::class, 'index']);
            Route::post('/', [InspectionController::class, 'store']);
            Route::get('{id}', [InspectionController::class, 'show']);
            Route::put('{id}', [InspectionController::class, 'update']);
        });

        // Work Progress (nested under order)
        Route::prefix('{order}/progress')->group(function () {
            Route::get('/', [WorkProgressController::class, 'index']);
            Route::post('/', [WorkProgressController::class, 'store']);
            Route::put('{id}', [WorkProgressController::class, 'update']);
        });
    });

    // Notifications Management
    Route::prefix('notifications')->group(function () {
        Route::get('/', [AdminNotificationController::class, 'index']);
        Route::post('send', [AdminNotificationController::class, 'sendToUser']);
        Route::post('broadcast', [AdminNotificationController::class, 'broadcast']);
        Route::delete('{id}', [AdminNotificationController::class, 'destroy']);
    });
});
