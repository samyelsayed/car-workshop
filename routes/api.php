<?php

use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\UpdatePasswordController;
use App\Http\Controllers\Api\Auth\UserPhoneController;
use App\Http\Controllers\Api\Auth\UserProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');






Route::group(['prefix' => 'users'],function () {
    Route::post('register',RegisterController::class);
    Route::post('login', [LoginController::class,'login']);
    Route::post('forgot-password', [ForgotPasswordController::class,'sendCode']);
    // Route::post('send-code',[EmailVerificationController::class,'sendCode']);
    Route::post('update-password', [UpdatePasswordController::class,'updatePassword']);
});



Route::middleware(['auth:sanctum'])->prefix('auth')->group(function () {
    Route::delete('logout', [LoginController::class,'logout']);
    Route::delete('logout-all-devices', [LoginController::class,'logoutAllDevices']);


});

Route::controller(EmailVerificationController::class)->prefix('verify')->as('verify.')->group(function () {
    Route::post('/send-code', 'sendCode')->name('send');
    Route::post('/check-code', 'checkCode')->name('check');
    Route::post('/resend-code', 'reSendCode')->name('resend');
});


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
Route::middleware('auth:sanctum')->group(function () {

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
        Route::delete('/{phone}', [UserPhoneController::class, 'destroy']);
    });

});
