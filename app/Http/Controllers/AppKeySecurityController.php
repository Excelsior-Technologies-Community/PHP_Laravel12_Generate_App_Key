<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

class AppKeySecurityController extends Controller
{
    /**
     * APP_KEY Security Dashboard
     */
    public function index()
    {
        $health = $this->getHealthChecks();

        return view('app-key-security.index', compact('health'));
    }

    /**
     * Secure APP_KEY Rotation
     */
    public function rotate(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'in:ROTATE'],
        ]);

        try {
            $oldKeyExists = !empty(config('app.key'));

            if (!$oldKeyExists) {
                return redirect()->route('app-key-security.index', [
                    'status' => 'missing-key',
                ]);
            }

            Artisan::call('key:generate', [
                '--force' => true,
            ]);

            Artisan::call('config:clear');

            Log::warning(
                'Laravel APP_KEY was rotated from the APP Key Security Dashboard.',
                [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'environment' => app()->environment(),
                ]
            );

            return redirect()->route('app-key-security.index', [
                'status' => 'rotated',
            ]);
        } catch (Throwable $e) {
            Log::error('APP_KEY rotation failed.', [
                'message' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return redirect()->route('app-key-security.index', [
                'status' => 'failed',
            ]);
        }
    }

    /**
     * Test Laravel encryption.
     */
    public function testEncryption()
    {
        try {
            $originalText = 'Laravel APP_KEY security test';

            $encrypted = Crypt::encryptString($originalText);
            $decrypted = Crypt::decryptString($encrypted);

            if ($originalText !== $decrypted) {
                return redirect()
                    ->route('app-key-security.index')
                    ->with(
                        'error',
                        'Encryption test failed: decrypted data does not match the original data.'
                    );
            }

            return redirect()
                ->route('app-key-security.index')
                ->with(
                    'success',
                    'Encryption test passed successfully. Laravel can encrypt and decrypt data correctly.'
                );
        } catch (Throwable $e) {
            Log::error('Laravel encryption health test failed.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('app-key-security.index')
                ->with(
                    'error',
                    'Encryption test failed. Your APP_KEY or encryption configuration may be invalid.'
                );
        }
    }

    /**
     * Run APP_KEY security audit.
     */
    public function securityAudit()
    {
        try {
            $audit = $this->runSecurityAudit();

            return view('app-key-security.audit', compact('audit'));
        } catch (Throwable $e) {
            Log::error('APP_KEY security audit failed.', [
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->route('app-key-security.index')
                ->with(
                    'error',
                    'Security audit could not be completed. Check the Laravel log for details.'
                );
        }
    }

    /**
     * Existing health checks.
     */
    private function getHealthChecks(): array
    {
        $appKey = config('app.key');

        $keyExists = !empty($appKey);

        $keyFormatValid = false;

        if ($keyExists) {
            $keyFormatValid = str_starts_with($appKey, 'base64:');

            if ($keyFormatValid) {
                $encodedKey = substr($appKey, 7);

                $decodedKey = base64_decode($encodedKey, true);

                $keyFormatValid =
                    $decodedKey !== false &&
                    strlen($decodedKey) === 32;
            }
        }

        $envFileExists = file_exists(base_path('.env'));

        $configKeyMatchesEnvironment = false;

        if ($envFileExists) {
            $envContent = file_get_contents(base_path('.env'));

            if ($envContent !== false) {
                preg_match(
                    '/^APP_KEY=(.*)$/m',
                    $envContent,
                    $matches
                );

                $envKey = isset($matches[1])
                    ? trim($matches[1])
                    : '';

                $configKeyMatchesEnvironment =
                    $envKey !== '' &&
                    $appKey === $envKey;
            }
        }

        $configCached = app()->configurationIsCached();

        $encryptionWorking = false;

        try {
            $testValue = 'APP_KEY_HEALTH_CHECK';

            $encrypted = Crypt::encryptString($testValue);
            $decrypted = Crypt::decryptString($encrypted);

            $encryptionWorking = $testValue === $decrypted;
        } catch (Throwable) {
            $encryptionWorking = false;
        }

        $cipher = config('app.cipher', 'AES-256-CBC');

        $environment = app()->environment();

        $debugEnabled = (bool) config('app.debug');

        $previousKeysConfigured =
            !empty(config('app.previous_keys', []));

        return [
            'key_exists' => [
                'label' => 'APP_KEY Available',
                'status' => $keyExists,
                'message' => $keyExists
                    ? 'A Laravel application key is configured.'
                    : 'APP_KEY is missing.',
            ],

            'key_format' => [
                'label' => 'APP_KEY Format',
                'status' => $keyFormatValid,
                'message' => $keyFormatValid
                    ? 'The APP_KEY uses a valid Laravel base64 format.'
                    : 'The APP_KEY format is invalid or incomplete.',
            ],

            'env_file' => [
                'label' => '.env File',
                'status' => $envFileExists,
                'message' => $envFileExists
                    ? 'The .env file is available.'
                    : 'The .env file could not be found.',
            ],

            'config_sync' => [
                'label' => 'Configuration Sync',
                'status' => $configKeyMatchesEnvironment,
                'message' => $configKeyMatchesEnvironment
                    ? 'The configured APP_KEY matches the .env value.'
                    : 'The application configuration may be using a stale or missing APP_KEY.',
            ],

            'encryption' => [
                'label' => 'Encryption System',
                'status' => $encryptionWorking,
                'message' => $encryptionWorking
                    ? 'Encryption and decryption are working correctly.'
                    : 'Laravel encryption could not complete successfully.',
            ],

            'environment' => [
                'label' => 'Application Environment',
                'status' => true,
                'message' => 'Application is running in ' . $environment . ' environment.',
            ],

            'debug' => [
                'label' => 'Debug Mode',
                'status' => $environment === 'local' || !$debugEnabled,
                'message' => $debugEnabled
                    ? 'APP_DEBUG is currently enabled.'
                    : 'APP_DEBUG is disabled.',
            ],

            'previous_keys' => [
                'label' => 'Previous Keys',
                'status' => true,
                'message' => $previousKeysConfigured
                    ? 'Previous encryption keys are configured.'
                    : 'No previous encryption keys are configured.',
            ],

            'cipher' => [
                'label' => 'Encryption Cipher',
                'status' => true,
                'message' => $cipher,
            ],

            'config_cache' => [
                'label' => 'Configuration Cache',
                'status' => true,
                'message' => $configCached
                    ? 'Configuration is currently cached.'
                    : 'Configuration cache is currently cleared.',
            ],
        ];
    }

    /**
     * Complete APP_KEY security audit.
     */
    private function runSecurityAudit(): array
    {
        $checks = [];

        /*
        |--------------------------------------------------------------------------
        | 1. APP_KEY existence
        |--------------------------------------------------------------------------
        */

        $appKey = config('app.key');

        $checks[] = $this->auditCheck(
            'APP_KEY exists',
            !empty($appKey),
            'critical',
            'A valid application key is configured.',
            'APP_KEY is missing. Run php artisan key:generate.'
        );

        /*
        |--------------------------------------------------------------------------
        | 2. APP_KEY format
        |--------------------------------------------------------------------------
        */

        $keyFormatValid = false;

        if (!empty($appKey)) {
            if (str_starts_with($appKey, 'base64:')) {
                $encodedKey = substr($appKey, 7);

                $decodedKey = base64_decode($encodedKey, true);

                $keyFormatValid =
                    $decodedKey !== false &&
                    strlen($decodedKey) === 32;
            }
        }

        $checks[] = $this->auditCheck(
            'APP_KEY format',
            $keyFormatValid,
            'critical',
            'APP_KEY uses the expected Laravel base64 format.',
            'APP_KEY does not appear to contain a valid 32-byte base64 key.'
        );

        /*
        |--------------------------------------------------------------------------
        | 3. .env exists
        |--------------------------------------------------------------------------
        */

        $envExists = file_exists(base_path('.env'));

        $checks[] = $this->auditCheck(
            '.env file availability',
            $envExists,
            'critical',
            'The application .env file exists.',
            'The .env file could not be found.'
        );

        /*
        |--------------------------------------------------------------------------
        | 4. .env protected by .gitignore
        |--------------------------------------------------------------------------
        */

        $gitignorePath = base_path('.gitignore');

        $gitignoreExists = file_exists($gitignorePath);

        $envIgnored = false;

        if ($gitignoreExists) {
            $gitignore = file_get_contents($gitignorePath);

            if ($gitignore !== false) {
                $lines = preg_split('/\r\n|\r|\n/', $gitignore);

                foreach ($lines as $line) {
                    $line = trim($line);

                    if (
                        $line === '.env' ||
                        $line === '.env*' ||
                        $line === '.env.*' ||
                        $line === '/.env' ||
                        $line === '/.env*'
                    ) {
                        $envIgnored = true;
                        break;
                    }
                }
            }
        }

        $checks[] = $this->auditCheck(
            '.env protected by .gitignore',
            $envIgnored,
            'critical',
            '.env is listed in .gitignore.',
            '.env does not appear to be protected by .gitignore.'
        );

        /*
        |--------------------------------------------------------------------------
        | 5. Public .env exposure
        |--------------------------------------------------------------------------
        */

        $publicEnvExists = file_exists(public_path('.env'));

        $checks[] = $this->auditCheck(
            'Public .env exposure',
            !$publicEnvExists,
            'critical',
            'No public/.env file was detected.',
            'A public/.env file exists and may expose sensitive configuration.'
        );

        /*
        |--------------------------------------------------------------------------
        | 6. APP_DEBUG configuration
        |--------------------------------------------------------------------------
        */

        $environment = app()->environment();

        $debugEnabled = (bool) config('app.debug');

        $debugSafe =
            $environment === 'local' ||
            !$debugEnabled;

        $checks[] = $this->auditCheck(
            'Production debug protection',
            $debugSafe,
            'warning',
            $debugEnabled
                ? 'Debug mode is enabled only in the local environment.'
                : 'APP_DEBUG is disabled.',
            'APP_DEBUG is enabled outside the local environment.'
        );

        /*
        |--------------------------------------------------------------------------
        | 7. Encryption test
        |--------------------------------------------------------------------------
        */

        $encryptionWorking = false;

        try {
            $testValue = 'SECURITY_AUDIT_TEST';

            $encrypted = Crypt::encryptString($testValue);

            $decrypted = Crypt::decryptString($encrypted);

            $encryptionWorking =
                $testValue === $decrypted;
        } catch (Throwable) {
            $encryptionWorking = false;
        }

        $checks[] = $this->auditCheck(
            'Encryption/decryption test',
            $encryptionWorking,
            'critical',
            'Laravel encryption and decryption are working correctly.',
            'Laravel could not successfully encrypt and decrypt test data.'
        );

        /*
        |--------------------------------------------------------------------------
        | 8. APP_KEY synchronization
        |--------------------------------------------------------------------------
        */

        $configMatchesEnv = false;

        if ($envExists) {
            $envContent = file_get_contents(base_path('.env'));

            if ($envContent !== false) {
                preg_match(
                    '/^APP_KEY=(.*)$/m',
                    $envContent,
                    $matches
                );

                $envKey = isset($matches[1])
                    ? trim($matches[1])
                    : '';

                $configMatchesEnv =
                    $envKey !== '' &&
                    $appKey === $envKey;
            }
        }

        $checks[] = $this->auditCheck(
            'APP_KEY configuration synchronization',
            $configMatchesEnv,
            'warning',
            'The loaded APP_KEY matches the value stored in .env.',
            'The loaded APP_KEY does not match the .env value. Configuration cache may be stale.'
        );

        /*
        |--------------------------------------------------------------------------
        | 9. Configuration cache
        |--------------------------------------------------------------------------
        */

        $configCached = app()->configurationIsCached();

        $checks[] = $this->auditCheck(
            'Configuration cache state',
            true,
            'info',
            $configCached
                ? 'Laravel configuration is currently cached.'
                : 'Laravel configuration cache is currently cleared.',
            'No configuration cache information is available.'
        );

        /*
        |--------------------------------------------------------------------------
        | 10. APP_KEY exposure in environment/config files
        |--------------------------------------------------------------------------
        */

        $appKeyExposed = false;

        $filesToInspect = [
            base_path('public/.env'),
            base_path('public/env'),
            base_path('storage/logs/laravel.log'),
        ];

        foreach ($filesToInspect as $file) {
            if (file_exists($file) && is_readable($file)) {
                $content = @file_get_contents($file);

                if (
                    $content !== false &&
                    !empty($appKey) &&
                    str_contains($content, $appKey)
                ) {
                    $appKeyExposed = true;
                    break;
                }
            }
        }

        $checks[] = $this->auditCheck(
            'APP_KEY exposure scan',
            !$appKeyExposed,
            'critical',
            'The configured APP_KEY was not detected in publicly risky files.',
            'The actual APP_KEY was detected in a potentially unsafe file.'
        );

        /*
        |--------------------------------------------------------------------------
        | Calculate score
        |--------------------------------------------------------------------------
        */

        $score = 100;

        foreach ($checks as $check) {
            if ($check['status']) {
                continue;
            }

            if ($check['severity'] === 'critical') {
                $score -= 15;
            } elseif ($check['severity'] === 'warning') {
                $score -= 8;
            }
        }

        $score = max(0, min(100, $score));

        /*
        |--------------------------------------------------------------------------
        | Overall security level
        |--------------------------------------------------------------------------
        */

        if ($score >= 90) {
            $level = 'Excellent';
            $levelClass = 'excellent';
        } elseif ($score >= 75) {
            $level = 'Good';
            $levelClass = 'good';
        } elseif ($score >= 50) {
            $level = 'Needs Attention';
            $levelClass = 'warning';
        } else {
            $level = 'Critical';
            $levelClass = 'critical';
        }

        $passed = collect($checks)
            ->where('status', true)
            ->count();

        $failed = collect($checks)
            ->where('status', false)
            ->count();

        $criticalIssues = collect($checks)
            ->where('status', false)
            ->where('severity', 'critical')
            ->count();

        $warnings = collect($checks)
            ->where('status', false)
            ->where('severity', 'warning')
            ->count();

        return [
            'score' => $score,
            'level' => $level,
            'level_class' => $levelClass,
            'passed' => $passed,
            'failed' => $failed,
            'critical' => $criticalIssues,
            'warnings' => $warnings,
            'checks' => $checks,
        ];
    }

    /**
     * Build an individual audit result.
     */
    private function auditCheck(
        string $name,
        bool $status,
        string $severity,
        string $successMessage,
        string $failureMessage
    ): array {
        return [
            'name' => $name,
            'status' => $status,
            'severity' => $severity,
            'message' => $status
                ? $successMessage
                : $failureMessage,
        ];
    }
}