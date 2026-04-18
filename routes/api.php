<?php

use App\Http\Controllers\Api\Admin\CarController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\InspectionController;
use App\Http\Controllers\Api\Admin\NotifyController;
use App\Http\Controllers\Api\Admin\OrdersManagement;
use App\Http\Controllers\Api\Admin\ServiceController;
use App\Http\Controllers\Api\Admin\UserManagementController;
use App\Http\Controllers\Api\Admin\WorkProgressController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\UpdatePasswordController;
use App\Http\Controllers\Api\Profile\UserAddressController;
use App\Http\Controllers\Api\Profile\UserCarController;
use App\Http\Controllers\Api\Profile\UserPhoneController;
use App\Http\Controllers\Api\Profile\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');






// Route::group(['prefix' => 'users'],function () {
//     Route::post('register',RegisterController::class);
//     Route::post('login', [LoginController::class,'login']);
//     Route::post('forgot-password', [ForgotPasswordController::class,'sendCode']);
//     // Route::post('send-code',[EmailVerificationController::class,'sendCode']);
//     Route::post('update-password', [UpdatePasswordController::class,'updatePassword']);
// });



// Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {
//     Route::delete('logout', [LoginController::class,'logout']);
//     Route::delete('logout-all-devices', [LoginController::class,'logoutAllDevices']);


// });

// Route::controller(EmailVerificationController::class)->prefix('verify')->as('verify.')->group(function () {
//     Route::post('/send-code', 'sendCode')->name('send');
//     Route::post('/check-code', 'checkCode')->name('check');
//     Route::post('/resend-code', 'reSendCode')->name('resend');
// });


/////////////////////////////////////
// ========================================
// Public Auth Routes
// ========================================
Route::prefix('auth')->group(function () {

    // Registration
    Route::post('/register', RegisterController::class);

    // Login
    Route::post('/login', [LoginController::class, 'login']);

    // Forgot Password
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendCode']);
    Route::post('/check-code', [ForgotPasswordController::class, 'checkCode']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

    // Email Verification
    Route::prefix('verify')->group(function () {
        Route::post('/send-code', [EmailVerificationController::class, 'sendCode']);
        Route::post('/check-code', [EmailVerificationController::class, 'checkCode']);
        Route::post('/resend-code', [EmailVerificationController::class, 'reSendCode']);
    });
});

// ========================================
// Protected Auth Routes
// ========================================
Route::middleware('auth:sanctum')->prefix('user')->group(function () {

    // Logout
    Route::delete('/logout', [LoginController::class, 'logout']);
    Route::delete('/logout-all-devices', [LoginController::class, 'logoutAllDevices']);

    //user profile
     Route::get('/profile', [UserProfileController::class, 'view']);
     Route::put('/update-profile', [UserProfileController::class, 'update']);
    // Change Password
    Route::post('/change-password', [UpdatePasswordController::class, 'updatePassword']);

   // User Phones
    Route::prefix('phones')->group(function () {
        Route::get('/index', [UserPhoneController::class, 'index']);
        Route::post('/create', [UserPhoneController::class, 'store']);
        Route::delete('/{id}', [UserPhoneController::class, 'destroy']);
    });


 // User Addresses
     Route::prefix('addresses')->group(function () {
        Route::get('/index', [UserAddressController::class, 'index']);
        Route::post('/create', [UserAddressController::class, 'store']);
        Route::put('/{id}', [UserAddressController::class, 'update']);
        Route::delete('/{id}', [UserAddressController::class, 'destroy']);

    });


     // User Cars
     Route::prefix('cars')->group(function () {
        Route::get('/index', [UserCarController::class, 'index']);
        Route::post('/create', [UserCarController::class, 'store']);
        Route::put('/{id}', [UserCarController::class, 'update']);
        Route::delete('/{id}', [UserCarController::class, 'destroy']);

    });
    });




// ========================================
// Admin Module Routes
// ========================================
Route::middleware(['auth:sanctum', 'isAdmin'])->prefix('admin')->group(function () {

    // 📊 9. Dashboard & Statistics
    Route::get('/dashboard/stats', [DashboardController::class, 'index']);

    // 👥 2. Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserManagementController::class, 'index']);          // Get All with filters
        Route::get('/{id}', [UserManagementController::class, 'show']);       // Details
        Route::put('/{id}', [UserManagementController::class, 'update']);     // Update role/info
        Route::delete('/{id}', [UserManagementController::class, 'destroy']);  // Soft Delete
        Route::post('/{id}/restore', [UserManagementController::class, 'restore']);
        Route::patch('/{id}/toggle-block', [UserManagementController::class, 'toggleBlock']);
    });

    // 🚗 3. Cars Management
    Route::prefix('cars')->group(function () {
        Route::get('/', [CarController::class, 'index']);
        Route::get('/{id}', [CarController::class, 'show']);
        Route::delete('/{id}', [CarController::class, 'destroy']);
    });

    // 🛠️ 4. Services Management (Full CRUD)
    Route::apiResource('services', ServiceController::class);
    Route::patch('services/{id}/toggle-status', [ServiceController::class, 'toggleStatus']);
    // 📋 5. Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrdersManagement::class, 'index']);
        Route::get('/{id}', [OrdersManagement::class, 'show']);
        Route::patch('/{id}/status', [OrdersManagement::class, 'updateStatus']);
        Route::patch('/{id}/assign', [OrdersManagement::class, 'assignOrder']);
        Route::post('/{id}/cancel', [OrdersManagement::class, 'cancelOrder']);

        // 🔍 6 & 7. Inspections & Work Progress (Nested under Order)
        Route::prefix('{order}/')->group(function () {
            Route::apiResource('inspections', InspectionController::class)->except(['destroy']);
            Route::apiResource('progress', WorkProgressController::class)->only(['index', 'store', 'update']);
        });
    });

    // 🔔 8. Notifications
    Route::prefix('notifications')->group(function () {
        Route::post('/send-to-user', [NotifyController::class, 'sendToUser']);
        Route::post('/broadcast', [NotifyController::class, 'broadcast']);
        Route::get('/', [NotifyController::class, 'index']);
        Route::delete('/{id}', [NotifyController::class, 'destroy']);
    });

});
