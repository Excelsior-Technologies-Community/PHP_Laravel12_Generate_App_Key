<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>APP_KEY Security Audit</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Arial,
                sans-serif;

            background: #f4f7fb;
            color: #172033;
        }

        .container {
            max-width: 1150px;
            margin: 0 auto;
            padding: 30px 20px 60px;
        }

        .header {
            background: linear-gradient(
                135deg,
                #111827,
                #263449
            );

            color: white;

            padding: 30px;

            border-radius: 18px;

            margin-bottom: 25px;

            box-shadow:
                0 15px 40px rgba(15, 23, 42, 0.15);
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .header p {
            margin: 0;
            color: #cbd5e1;
        }

        .back {
            display: inline-block;

            margin-bottom: 20px;

            text-decoration: none;

            color: #2563eb;

            font-weight: 700;
        }

        .score-section {
            background: white;

            border-radius: 18px;

            padding: 30px;

            margin-bottom: 25px;

            box-shadow:
                0 8px 30px rgba(15, 23, 42, 0.07);
        }

        .score-layout {
            display: grid;

            grid-template-columns:
                220px 1fr;

            gap: 30px;

            align-items: center;
        }

        .score-circle {
            width: 190px;
            height: 190px;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            flex-direction: column;

            margin: auto;

            border: 14px solid #e5e7eb;
        }

        .score-circle.excellent {
            border-color: #16a34a;
        }

        .score-circle.good {
            border-color: #2563eb;
        }

        .score-circle.warning {
            border-color: #f59e0b;
        }

        .score-circle.critical {
            border-color: #dc2626;
        }

        .score {
            font-size: 48px;
            font-weight: 800;
        }

        .score-label {
            color: #64748b;
            font-size: 14px;
        }

        .security-level {
            font-size: 24px;

            font-weight: 800;

            margin-bottom: 10px;
        }

        .excellent-text {
            color: #15803d;
        }

        .good-text {
            color: #2563eb;
        }

        .warning-text {
            color: #d97706;
        }

        .critical-text {
            color: #dc2626;
        }

        .stats {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 15px;

            margin-top: 20px;
        }

        .stat {
            padding: 20px;

            border-radius: 14px;

            background: #f8fafc;

            border: 1px solid #e2e8f0;
        }

        .stat strong {
            display: block;

            font-size: 28px;

            margin-bottom: 5px;
        }

        .stat span {
            color: #64748b;

            font-size: 14px;
        }

        .audit-card {
            background: white;

            border-radius: 18px;

            padding: 25px;

            box-shadow:
                0 8px 30px rgba(15, 23, 42, 0.07);
        }

        .audit-card h2 {
            margin-top: 0;

            margin-bottom: 20px;
        }

        .check {
            display: flex;

            align-items: flex-start;

            gap: 15px;

            padding: 18px 0;

            border-bottom: 1px solid #e5e7eb;
        }

        .check:last-child {
            border-bottom: none;
        }

        .icon {
            width: 40px;
            height: 40px;

            flex: 0 0 40px;

            border-radius: 50%;

            display: flex;

            align-items: center;
            justify-content: center;

            font-weight: 800;
        }

        .icon.pass {
            background: #dcfce7;
            color: #15803d;
        }

        .icon.fail {
            background: #fee2e2;
            color: #dc2626;
        }

        .check-content {
            flex: 1;
        }

        .check-name {
            font-size: 17px;

            font-weight: 800;

            margin-bottom: 5px;
        }

        .check-message {
            color: #64748b;

            line-height: 1.6;
        }

        .badge {
            display: inline-block;

            padding: 4px 9px;

            border-radius: 999px;

            font-size: 11px;

            font-weight: 800;

            text-transform: uppercase;

            margin-left: 8px;
        }

        .badge-critical {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-warning {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-info {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .recommendations {
            margin-top: 25px;

            background: #fffbeb;

            border: 1px solid #fde68a;

            border-radius: 16px;

            padding: 22px;
        }

        .recommendations h3 {
            margin-top: 0;

            color: #92400e;
        }

        .recommendations ul {
            margin-bottom: 0;

            padding-left: 20px;
        }

        .recommendations li {
            margin-bottom: 8px;

            line-height: 1.5;
        }

        .actions {
            margin-top: 25px;

            display: flex;

            gap: 12px;

            flex-wrap: wrap;
        }

        .button {
            display: inline-block;

            padding: 12px 18px;

            border-radius: 10px;

            text-decoration: none;

            font-weight: 700;

            border: none;

            cursor: pointer;
        }

        .button-primary {
            background: #2563eb;

            color: white;
        }

        .button-secondary {
            background: #e2e8f0;

            color: #172033;
        }

        @media (max-width: 800px) {

            .score-layout {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns:
                    repeat(2, 1fr);
            }
        }

        @media (max-width: 500px) {

            .container {
                padding: 15px;
            }

            .header {
                padding: 22px;
            }

            .header h1 {
                font-size: 24px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .check {
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <a
        href="{{ route('app-key-security.index') }}"
        class="back"
    >
        ← Back to APP_KEY Dashboard
    </a>

    <div class="header">

        <h1>
            🛡️ APP_KEY Security Audit
        </h1>

        <p>
            Automated security analysis of your Laravel
            application key and encryption configuration.
        </p>

    </div>


    {{-- Security Score --}}

    <div class="score-section">

        <div class="score-layout">

            <div>

                <div
                    class="score-circle {{ $audit['level_class'] }}"
                >

                    <div class="score">
                        {{ $audit['score'] }}
                    </div>

                    <div class="score-label">
                        / 100
                    </div>

                </div>

            </div>


            <div>

                <div
                    class="security-level
                    {{ $audit['level_class'] }}-text"
                >
                    {{ $audit['level'] }}
                </div>

                <p>
                    The security scanner analyzed your
                    Laravel APP_KEY configuration and
                    related application security settings.
                </p>

                <div class="stats">

                    <div class="stat">

                        <strong>
                            {{ $audit['passed'] }}
                        </strong>

                        <span>
                            Passed Checks
                        </span>

                    </div>

                    <div class="stat">

                        <strong>
                            {{ $audit['failed'] }}
                        </strong>

                        <span>
                            Failed Checks
                        </span>

                    </div>

                    <div class="stat">

                        <strong>
                            {{ $audit['critical'] }}
                        </strong>

                        <span>
                            Critical Issues
                        </span>

                    </div>

                    <div class="stat">

                        <strong>
                            {{ $audit['warnings'] }}
                        </strong>

                        <span>
                            Warnings
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- Audit Checks --}}

    <div class="audit-card">

        <h2>
            🔍 Security Audit Results
        </h2>


        @foreach($audit['checks'] as $check)

            <div class="check">

                <div
                    class="icon
                    {{ $check['status'] ? 'pass' : 'fail' }}"
                >

                    {{ $check['status'] ? '✓' : '!' }}

                </div>


                <div class="check-content">

                    <div class="check-name">

                        {{ $check['name'] }}

                        @if(!$check['status'])

                            <span
                                class="badge
                                badge-{{ $check['severity'] }}"
                            >
                                {{ $check['severity'] }}
                            </span>

                        @endif

                    </div>


                    <div class="check-message">

                        {{ $check['message'] }}

                    </div>

                </div>

            </div>

        @endforeach


        {{-- Recommendations --}}

        @if($audit['failed'] > 0)

            <div class="recommendations">

                <h3>
                    ⚠️ Recommended Actions
                </h3>

                <ul>

                    @foreach($audit['checks'] as $check)

                        @if(!$check['status'])

                            <li>
                                <strong>
                                    {{ $check['name'] }}:
                                </strong>

                                {{ $check['message'] }}
                            </li>

                        @endif

                    @endforeach

                </ul>

            </div>

        @else

            <div
                class="recommendations"
                style="
                    background:#f0fdf4;
                    border-color:#bbf7d0;
                "
            >

                <h3 style="color:#166534;">
                    ✓ Excellent Security Configuration
                </h3>

                <p style="margin-bottom:0;">
                    No failed security checks were detected.
                    Your APP_KEY configuration currently
                    passes all available security checks.
                </p>

            </div>

        @endif


        <div class="actions">

            <a
                href="{{ route('app-key-security.index') }}"
                class="button button-secondary"
            >
                ← Dashboard
            </a>

            <a
                href="{{ route('app-key-security.security-audit') }}"
                class="button button-primary"
            >
                🔄 Run Audit Again
            </a>

        </div>

    </div>

</div>

</body>

</html>