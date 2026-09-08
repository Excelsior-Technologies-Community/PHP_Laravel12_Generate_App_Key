<?php

use App\Http\Controllers\AppKeySecurityController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('app-key-security')
    ->name('app-key-security.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Security Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [
            AppKeySecurityController::class,
            'index'
        ])->name('index');

        /*
        |--------------------------------------------------------------------------
        | Encryption Test
        |--------------------------------------------------------------------------
        */

        Route::post('/test-encryption', [
            AppKeySecurityController::class,
            'testEncryption'
        ])->name('test-encryption');

        /*
        |--------------------------------------------------------------------------
        | APP_KEY Rotation
        |--------------------------------------------------------------------------
        */

        Route::post('/rotate', [
            AppKeySecurityController::class,
            'rotate'
        ])->name('rotate');

        /*
        |--------------------------------------------------------------------------
        | Security Audit
        |--------------------------------------------------------------------------
        */

        Route::get('/security-audit', [
            AppKeySecurityController::class,
            'securityAudit'
        ])->name('security-audit');
    });