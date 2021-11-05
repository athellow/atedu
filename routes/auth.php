<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController as BackendAuthenticatedSessionController;
use App\Http\Controllers\Backend\Auth\NewPasswordController as BackendNewPasswordController;
use App\Http\Controllers\Backend\Auth\PasswordResetLinkController as BackendPasswordResetLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware('guest')
                ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store'])
                ->middleware('guest');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
                ->middleware('guest')
                ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
                ->middleware('guest');

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->middleware('guest')
                ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->middleware('guest')
                ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->middleware('guest')
                ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->middleware('guest')
                ->name('password.update');

Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
                ->middleware('auth')
                ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
                ->middleware(['auth', 'signed', 'throttle:6,1'])
                ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware(['auth', 'throttle:6,1'])
                ->name('verification.send');

Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->middleware('auth')
                ->name('password.confirm');

Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
                ->middleware('auth');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');

Route::group(['prefix' => 'admin'], function () {
    // Authentication...
    Route::get('/login', [BackendAuthenticatedSessionController::class, 'create'])
        ->middleware(['guest:admin'])
        ->name('admin.login');
    
    Route::post('/login', [BackendAuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:admin',
            // 'throttle:login',
        ]));

    Route::post('/logout', [BackendAuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:admin')
        ->name('admin.logout');

    // Password Reset...
    Route::get('/forgot-password', [BackendPasswordResetLinkController::class, 'create'])
        ->middleware(['guest:admin'])
        ->name('admin.password.request');

    Route::post('/forgot-password', [BackendPasswordResetLinkController::class, 'store'])
        ->middleware(['guest:admin'])
        ->name('admin.password.email');
    
    Route::get('/reset-password/{token}', [BackendNewPasswordController::class, 'create'])
        ->middleware(['guest:admin'])
        ->name('admin.password.reset');
    
    Route::post('/reset-password', [BackendNewPasswordController::class, 'store'])
        ->middleware(['guest:admin'])
        ->name('admin.password.update');

    $twoFactorLimiter = config('fortify.limiters.two-factor');
    $verificationLimiter = config('fortify.limiters.verification', '6,1');

    // // Password Confirmation...
    // if ($enableViews) {
    //     Route::get('/user/confirm-password', [ConfirmablePasswordController::class, 'show'])
    //         ->middleware(['auth:'.config('fortify.guard')])
    //         ->name('password.confirm');
    // }

    // Route::get('/user/confirmed-password-status', [ConfirmedPasswordStatusController::class, 'show'])
    //     ->middleware(['auth:'.config('fortify.guard')])
    //     ->name('password.confirmation');

    // Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store'])
    //     ->middleware(['auth:'.config('fortify.guard')]);

    // // Two Factor Authentication...
    // if (Features::enabled(Features::twoFactorAuthentication())) {
    //     if ($enableViews) {
    //         Route::get('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'create'])
    //             ->middleware(['guest:'.config('fortify.guard')])
    //             ->name('two-factor.login');
    //     }

    //     Route::post('/two-factor-challenge', [TwoFactorAuthenticatedSessionController::class, 'store'])
    //         ->middleware(array_filter([
    //             'guest:'.config('fortify.guard'),
    //             $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
    //         ]));

    //     $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
    //         ? ['auth:'.config('fortify.guard'), 'password.confirm']
    //         : ['auth:'.config('fortify.guard')];

    //     Route::post('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'store'])
    //         ->middleware($twoFactorMiddleware)
    //         ->name('two-factor.enable');

    //     Route::delete('/user/two-factor-authentication', [TwoFactorAuthenticationController::class, 'destroy'])
    //         ->middleware($twoFactorMiddleware)
    //         ->name('two-factor.disable');

    //     Route::get('/user/two-factor-qr-code', [TwoFactorQrCodeController::class, 'show'])
    //         ->middleware($twoFactorMiddleware)
    //         ->name('two-factor.qr-code');

    //     Route::get('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'index'])
    //         ->middleware($twoFactorMiddleware)
    //         ->name('two-factor.recovery-codes');

    //     Route::post('/user/two-factor-recovery-codes', [RecoveryCodeController::class, 'store'])
    //         ->middleware($twoFactorMiddleware);
    // }
});