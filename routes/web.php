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
        | Dashboard
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

        /*
        |--------------------------------------------------------------------------
        | Feature 3
        | Clear Configuration Cache
        |--------------------------------------------------------------------------
        */

        Route::post('/clear-config-cache', [
            AppKeySecurityController::class,
            'clearConfigCache'
        ])->name('clear-config-cache');

        /*
        |--------------------------------------------------------------------------
        | Feature 4
        | Clear Application Cache
        |--------------------------------------------------------------------------
        */

        Route::post('/clear-application-cache', [
            AppKeySecurityController::class,
            'clearApplicationCache'
        ])->name('clear-application-cache');

        /*
        |--------------------------------------------------------------------------
        | Feature 6
        | Export Security Audit
        |--------------------------------------------------------------------------
        */

        Route::get('/export-audit', [
            AppKeySecurityController::class,
            'exportAudit'
        ])->name('export-audit');
    });
