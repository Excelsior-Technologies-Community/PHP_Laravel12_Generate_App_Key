<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>APP_KEY Security Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                sans-serif;

            background: #f4f7fb;
            color: #1f2937;
            min-height: 100vh;
        }

        .header {
            background: linear-gradient(135deg,
                    #111827,
                    #1f2937);

            color: white;
            padding: 32px 20px;
        }

        .header-inner {
            max-width: 1200px;
            margin: auto;
        }

        .header h1 {
            font-size: 30px;
            margin-bottom: 8px;
        }

        .header p {
            color: #d1d5db;
            font-size: 15px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .alert {
            padding: 16px 18px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .summary-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));

            gap: 18px;
            margin-bottom: 28px;
        }

        .summary-card {
            background: white;
            border-radius: 16px;
            padding: 22px;
            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .summary-title {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 800;
        }

        .success-text {
            color: #16a34a;
        }

        .danger-text {
            color: #dc2626;
        }

        .warning-text {
            color: #d97706;
        }

        .section {
            background: white;
            border-radius: 18px;
            padding: 26px;
            margin-bottom: 25px;

            box-shadow:
                0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 22px;
        }

        .section-header h2 {
            font-size: 21px;
        }

        .section-header p {
            color: #6b7280;
            font-size: 13px;
            margin-top: 5px;
        }

        .health-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .health-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;

            padding: 17px;

            border: 1px solid #e5e7eb;
            border-radius: 12px;

            background: #fafafa;
        }

        .health-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .status-icon {
            width: 38px;
            height: 38px;

            display: flex;
            align-items: center;
            justify-content: center;

            border-radius: 50%;

            font-weight: 800;
            font-size: 16px;
        }

        .status-ok {
            background: #dcfce7;
            color: #15803d;
        }

        .status-fail {
            background: #fee2e2;
            color: #b91c1c;
        }

        .health-title {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .health-message {
            font-size: 13px;
            color: #6b7280;
        }

        .badge {
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .info-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(220px, 1fr));

            gap: 15px;
        }

        .info-card {
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fafafa;
        }

        .info-card small {
            display: block;
            color: #6b7280;
            margin-bottom: 7px;
        }

        .info-card strong {
            font-size: 16px;
        }

        .actions {
            display: grid;
            grid-template-columns:
                repeat(auto-fit, minmax(280px, 1fr));

            gap: 18px;
        }

        .action-card {
            border: 1px solid #e5e7eb;
            border-radius: 15px;
            padding: 22px;
        }

        .action-card h3 {
            margin-bottom: 8px;
        }

        .action-card p {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        button {
            border: none;
            border-radius: 10px;
            padding: 12px 17px;
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-audit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;

            padding: 12px 17px;

            border-radius: 10px;

            background: #2563eb;
            color: white;

            text-decoration: none;

            font-weight: 700;
            font-size: 14px;

            transition: background 0.2s ease;
        }

        .btn-audit:hover {
            background: #1d4ed8;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .warning-box {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            border-radius: 12px;
            padding: 17px;
            margin-top: 20px;
            line-height: 1.6;
            font-size: 13px;
        }

        .security-note {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            border-radius: 12px;
            padding: 17px;
            margin-top: 20px;
            line-height: 1.6;
            font-size: 13px;
        }

        .footer {
            text-align: center;
            padding: 30px 20px;
            color: #6b7280;
            font-size: 13px;
        }

        @media (max-width: 700px) {

            .header h1 {
                font-size: 24px;
            }

            .section {
                padding: 18px;
            }

            .health-item {
                align-items: flex-start;
                flex-direction: column;
            }

            .section-header {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <header class="header">
        <div class="header-inner">

            <h1>
                🔐 APP_KEY Security Dashboard
            </h1>

            <p>
                Monitor Laravel encryption configuration,
                application key health, and security status.
            </p>

        </div>
    </header>


    <div class="container">

        {{-- APP_KEY Rotation Status --}}

        @if(request('status') === 'rotated')

        <div class="alert alert-success">
            <strong>✓ APP_KEY Rotated Successfully</strong>

            <div style="margin-top: 6px; font-weight: 500;">
                A new Laravel application key has been generated
                successfully and the configuration cache has been cleared.
            </div>
        </div>

        @endif


        @if(request('status') === 'missing-key')

        <div class="alert alert-error">
            <strong>✕ APP_KEY Rotation Failed</strong>

            <div style="margin-top: 6px; font-weight: 500;">
                No existing APP_KEY was found.
                Generate an application key first.
            </div>
        </div>

        @endif


        @if(request('status') === 'failed')

        <div class="alert alert-error">
            <strong>✕ APP_KEY Rotation Failed</strong>

            <div style="margin-top: 6px; font-weight: 500;">
                The application key could not be rotated.
                Please check the Laravel log for more information.
            </div>
        </div>

        @endif


        {{-- Normal Session Messages --}}

        @if(session('success'))

        <div class="alert alert-success">
            ✓ {{ session('success') }}
        </div>

        @endif


        @if(session('error'))

        <div class="alert alert-error">
            ✕ {{ session('error') }}
        </div>

        @endif


        {{-- Summary --}}
        @php
        $totalChecks = count($health);

        $passedChecks = collect($health)
        ->filter(fn ($item) => $item['status'])
        ->count();

        $failedChecks = $totalChecks - $passedChecks;

        $healthPercentage = $totalChecks > 0
        ? round(($passedChecks / $totalChecks) * 100)
        : 0;
        @endphp


        <div class="summary-grid">

            <div class="summary-card">
                <div class="summary-title">
                    Overall Security Health
                </div>

                <div class="summary-value
                {{ $healthPercentage >= 80
                    ? 'success-text'
                    : 'danger-text' }}">

                    {{ $healthPercentage }}%

                </div>
            </div>


            <div class="summary-card">
                <div class="summary-title">
                    Passed Checks
                </div>

                <div class="summary-value success-text">
                    {{ $passedChecks }}
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-title">
                    Failed Checks
                </div>

                <div class="summary-value
                {{ $failedChecks > 0
                    ? 'danger-text'
                    : 'success-text' }}">

                    {{ $failedChecks }}

                </div>
            </div>


            <div class="summary-card">
                <div class="summary-title">
                    Environment
                </div>

                <div class="summary-value">
                    {{ strtoupper(app()->environment()) }}
                </div>
            </div>

        </div>


        {{-- Health Checks --}}
        <div class="section">

            <div class="section-header">

                <div>
                    <h2>
                        🩺 Encryption & Configuration Health
                    </h2>

                    <p>
                        Real-time checks for your Laravel APP_KEY
                        and encryption configuration.
                    </p>
                </div>

            </div>


            <div class="health-list">

                @foreach($health as $check)

                <div class="health-item">

                    <div class="health-left">

                        <div class="status-icon
                            {{ $check['status']
                                ? 'status-ok'
                                : 'status-fail' }}">

                            {{ $check['status'] ? '✓' : '!' }}

                        </div>

                        <div>

                            <div class="health-title">
                                {{ $check['label'] }}
                            </div>

                            <div class="health-message">
                                {{ $check['message'] }}
                            </div>

                        </div>

                    </div>


                    <span class="badge
                        {{ $check['status']
                            ? 'badge-success'
                            : 'badge-danger' }}">

                        {{ $check['status'] ? 'Healthy' : 'Action Required' }}

                    </span>

                </div>

                @endforeach

            </div>


            <div class="security-note">
                <strong>Security:</strong>
                The actual APP_KEY is intentionally never displayed
                by this dashboard. Only its configuration and health
                status are inspected.
            </div>

        </div>


        {{-- Configuration Information --}}
        <div class="section">

            <div class="section-header">

                <div>
                    <h2>
                        ⚙️ Application Encryption Information
                    </h2>

                    <p>
                        Safe configuration information without exposing
                        sensitive key material.
                    </p>
                </div>

            </div>


            <div class="info-grid">

                <div class="info-card">

                    <small>
                        Encryption Cipher
                    </small>

                    <strong>
                        {{ config('app.cipher', 'AES-256-CBC') }}
                    </strong>

                </div>


                <div class="info-card">

                    <small>
                        Laravel Environment
                    </small>

                    <strong>
                        {{ app()->environment() }}
                    </strong>

                </div>


                <div class="info-card">

                    <small>
                        APP_KEY Status
                    </small>

                    <strong class="success-text">
                        {{ config('app.key') ? 'Configured' : 'Missing' }}
                    </strong>

                </div>


                <div class="info-card">

                    <small>
                        Debug Mode
                    </small>

                    <strong class="{{ config('app.debug')
                    ? 'warning-text'
                    : 'success-text' }}">

                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}

                    </strong>

                </div>


                <div class="info-card">

                    <small>
                        Configuration Cache
                    </small>

                    <strong>
                        {{ app()->configurationIsCached()
                        ? 'Cached'
                        : 'Not Cached' }}
                    </strong>

                </div>


                <div class="info-card">

                    <small>
                        Previous Keys
                    </small>

                    <strong>
                        {{ !empty(config('app.previous_keys', []))
                        ? 'Configured'
                        : 'Not Configured' }}
                    </strong>

                </div>

            </div>

        </div>


        {{-- Actions --}}
        <div class="section">

            <div class="section-header">

                <div>
                    <h2>
                        🛠️ Security Actions
                    </h2>

                    <p>
                        Perform encryption diagnostics and key management.
                    </p>
                </div>

            </div>


            <div class="actions">

                {{-- Encryption Test --}}
                <div class="action-card">

                    <h3>
                        🔬 Test Encryption
                    </h3>

                    <p>
                        Run a live encryption and decryption test using
                        Laravel's Crypt service to verify that your
                        APP_KEY is working correctly.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('app-key-security.test-encryption') }}">

                        @csrf

                        <button
                            type="submit"
                            class="btn-primary">
                            Run Encryption Test
                        </button>

                    </form>

                </div>


                {{-- Key Rotation --}}
                <div class="action-card">

                    <h3>
                        🔄 Rotate APP_KEY
                    </h3>

                    <p>
                        Generate a completely new Laravel application key.
                        This can invalidate existing encrypted sessions,
                        cookies, and other encrypted application data.
                    </p>

                    <form
                        method="POST"
                        action="{{ route('app-key-security.rotate') }}"
                        onsubmit="return confirmRotation();">

                        @csrf

                        <input
                            type="hidden"
                            name="confirmation"
                            id="rotationConfirmation"
                            value="">

                        <button
                            type="submit"
                            class="btn-danger">
                            Rotate APP_KEY
                        </button>

                    </form>

                </div>

                {{-- Security Audit --}}
                <div class="action-card">

                    <h3>
                        🛡️ Security Audit
                    </h3>

                    <p>
                        Run a complete security audit to identify APP_KEY,
                        .env, encryption, debug-mode, configuration,
                        and potential security risks.
                    </p>

                    <a
                        href="{{ route('app-key-security.security-audit') }}"
                        class="btn-audit">
                        🛡️ Run Security Audit
                    </a>

                </div>

            </div>


            <div class="warning-box">

                <strong>⚠ Important:</strong>

                APP_KEY rotation is a destructive security operation.
                Existing encrypted sessions and cookies may become
                invalid after the key changes. Users may need to log
                in again. Never rotate the production key casually.

            </div>

        </div>


        {{-- Recommendations --}}
        <div class="section">

            <div class="section-header">

                <div>

                    <h2>
                        🛡️ Security Recommendations
                    </h2>

                    <p>
                        Recommended practices for protecting your Laravel
                        application key.
                    </p>

                </div>

            </div>


            <div class="health-list">

                <div class="health-item">

                    <div class="health-left">

                        <div class="status-icon status-ok">
                            ✓
                        </div>

                        <div>

                            <div class="health-title">
                                Keep .env out of Git
                            </div>

                            <div class="health-message">
                                Never commit your real .env file or
                                APP_KEY to a public repository.
                            </div>

                        </div>

                    </div>

                </div>


                <div class="health-item">

                    <div class="health-left">

                        <div class="status-icon status-ok">
                            ✓
                        </div>

                        <div>

                            <div class="health-title">
                                Do not expose APP_KEY
                            </div>

                            <div class="health-message">
                                Do not print, log, email, or display the
                                actual application key.
                            </div>

                        </div>

                    </div>

                </div>


                <div class="health-item">

                    <div class="health-left">

                        <div class="status-icon status-ok">
                            ✓
                        </div>

                        <div>

                            <div class="health-title">
                                Use HTTPS in production
                            </div>

                            <div class="health-message">
                                Protect encrypted application traffic
                                with HTTPS and secure cookies.
                            </div>

                        </div>

                    </div>

                </div>


                <div class="health-item">

                    <div class="health-left">

                        <div class="status-icon status-ok">
                            ✓
                        </div>

                        <div>

                            <div class="health-title">
                                Rotate compromised keys
                            </div>

                            <div class="health-message">
                                If the APP_KEY is exposed, rotate it
                                immediately and investigate the exposure.
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <footer class="footer">

        Laravel 12 APP_KEY Security Dashboard

        <br>

        Secure encryption configuration monitoring

    </footer>


    <script>
        function confirmRotation() {

            const firstConfirmation = confirm(
                "WARNING: Rotating APP_KEY can invalidate existing encrypted sessions and cookies.\n\nDo you want to continue?"
            );

            if (!firstConfirmation) {
                return false;
            }

            const secondConfirmation = prompt(
                "Type ROTATE to confirm APP_KEY rotation:"
            );

            if (secondConfirmation !== "ROTATE") {

                alert(
                    "APP_KEY rotation cancelled. You must type ROTATE exactly."
                );

                return false;
            }

            document.getElementById(
                "rotationConfirmation"
            ).value = "ROTATE";

            return true;
        }
    </script>

</body>

</html>