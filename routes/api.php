<?php

use App\Http\Controllers\Api\Admin;
use App\Http\Controllers\Api\Auth;
use App\Http\Controllers\Api\User;
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
    Route::post('register', Auth\RegisterController::class);

    // Login
    Route::post('login', [Auth\LoginController::class, 'login']);

    // Forgot Password Flow
    Route::post('forgot-password', [Auth\ForgotPasswordController::class, 'sendCode']);
    Route::post('check-reset-code', [Auth\ForgotPasswordController::class, 'checkCode']);
    Route::post('reset-password', [Auth\ForgotPasswordController::class, 'resetPassword']);

    // Email Verification Flow
    Route::prefix('verify')->group(function () {
        Route::post('send-code', [Auth\EmailVerificationController::class, 'sendCode']);
        Route::post('check-code', [Auth\EmailVerificationController::class, 'checkCode']);
        Route::post('resend-code', [Auth\EmailVerificationController::class, 'reSendCode']);
    });
});

// ========================================
// Protected User Routes
// ========================================
Route::prefix('user')->middleware('auth:sanctum')->group(function () {

    // Auth Operations
    Route::delete('logout', [Auth\LoginController::class, 'logout']);
    Route::delete('logout-all-devices', [Auth\LoginController::class, 'logoutAllDevices']);
    Route::post('change-password', [Auth\UpdatePasswordController::class, 'updatePassword']);

    // User Profile
    Route::get('profile', [User\UserProfileController::class, 'view']);
    Route::put('profile', [User\UserProfileController::class, 'update']);

    // User Phones
    Route::prefix('phones')->group(function () {
        Route::get('/', [User\UserPhoneController::class, 'index']);
        Route::post('/', [User\UserPhoneController::class, 'store']);
        Route::delete('{id}', [User\UserPhoneController::class, 'destroy']);
    });

    // User Addresses
    Route::prefix('addresses')->group(function () {
        Route::get('/', [User\UserAddressController::class, 'index']);
        Route::post('/', [User\UserAddressController::class, 'store']);
        Route::put('{id}', [User\UserAddressController::class, 'update']);
        Route::delete('{id}', [User\UserAddressController::class, 'destroy']);
    });

    // User Cars
    Route::prefix('cars')->group(function () {
        Route::get('/', [User\UserCarController::class, 'index']);
        Route::post('/', [User\UserCarController::class, 'store']);
        Route::put('{id}', [User\UserCarController::class, 'update']);
        Route::delete('{id}', [User\UserCarController::class, 'destroy']);
    });

    // User Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [User\NotificationController::class, 'index']);
        Route::get('unread-count', [User\NotificationController::class, 'unreadCount']);
        Route::post('{id}/read', [User\NotificationController::class, 'markAsRead']);
        Route::post('read-all', [User\NotificationController::class, 'markAllAsRead']);
        Route::delete('{id}', [User\NotificationController::class, 'destroy']);
        Route::delete('clear-read', [User\NotificationController::class, 'deleteAllRead']);
    });
});

// ========================================
// Admin Routes
// ========================================
Route::prefix('admin')->middleware(['auth:sanctum', 'isAdmin'])->group(function () {

    // Dashboard & Statistics
    Route::get('dashboard/stats', [Admin\DashboardController::class, 'index']);

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [Admin\UserManagementController::class, 'index']);
        Route::get('{id}', [Admin\UserManagementController::class, 'show']);
        Route::put('{id}', [Admin\UserManagementController::class, 'update']);
        Route::delete('{id}', [Admin\UserManagementController::class, 'destroy']);
        Route::post('{id}/restore', [Admin\UserManagementController::class, 'restore']);
        Route::patch('{id}/toggle-block', [Admin\UserManagementController::class, 'toggleBlock']);
    });

    // Cars Management
    Route::prefix('cars')->group(function () {
        Route::get('/', [Admin\CarController::class, 'index']);
        Route::get('{id}', [Admin\CarController::class, 'show']);
        Route::delete('{id}', [Admin\CarController::class, 'destroy']);
    });

    // Services Management
    Route::prefix('services')->group(function () {
        Route::get('/', [Admin\ServiceController::class, 'index']);
        Route::post('/', [Admin\ServiceController::class, 'store']);
        Route::get('{id}', [Admin\ServiceController::class, 'show']);
        Route::put('{id}', [Admin\ServiceController::class, 'update']);
        Route::delete('{id}', [Admin\ServiceController::class, 'destroy']);
        Route::patch('{id}/toggle-status', [Admin\ServiceController::class, 'toggleStatus']);
    });

    // Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [Admin\OrdersManagement::class, 'index']);
        Route::get('{id}', [Admin\OrdersManagement::class, 'show']);
        Route::patch('{id}/status', [Admin\OrdersManagement::class, 'updateStatus']);
        Route::patch('{id}/assign', [Admin\OrdersManagement::class, 'assignOrder']);
        Route::post('{id}/cancel', [Admin\OrdersManagement::class, 'cancelOrder']);

        // Inspections (nested under order)
        Route::prefix('{order}/inspections')->group(function () {
            Route::get('/', [Admin\InspectionController::class, 'index']);
            Route::post('/', [Admin\InspectionController::class, 'store']);
            Route::get('{id}', [Admin\InspectionController::class, 'show']);
            Route::put('{id}', [Admin\InspectionController::class, 'update']);
        });

        // Work Progress (nested under order)
        Route::prefix('{order}/progress')->group(function () {
            Route::get('/', [Admin\WorkProgressController::class, 'index']);
            Route::post('/', [Admin\WorkProgressController::class, 'store']);
            Route::put('{id}', [Admin\WorkProgressController::class, 'update']);
        });
    });

    // Notifications Management
    Route::prefix('notifications')->group(function () {
        Route::get('/', [Admin\NotificationController::class, 'index']);
        Route::post('send', [Admin\NotificationController::class, 'sendToUser']);
        Route::post('broadcast', [Admin\NotificationController::class, 'broadcast']);
        Route::delete('{id}', [Admin\NotificationController::class, 'destroy']);
    });
});
