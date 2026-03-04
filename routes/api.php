<?php

use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
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
