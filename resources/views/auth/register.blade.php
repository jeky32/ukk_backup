<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - TIMLY</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-blue: #5B8DEE;
            --primary-purple: #8B7FE8;
            --accent-cyan: #66D9E8;
            --accent-lavender: #A79FE8;
            --accent-pink: #E898C7;
            --accent-mint: #88E8D0;
            --accent-peach: #F5B28B;
            --success-green: #7DD4A5;
            --text-dark: #2D3748;
            --text-medium: #5A6B83;
            --text-light: #8894A8;
            --bg-light: #F9FAFB;
            --border-light: #D8E1EE;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #F9FAFB 0%, #EEF2F7 50%, #E8EDF5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }

        .main-container {
            background: white;
            border-radius: 24px;
            width: 100%;
            max-width: 1100px;
            box-shadow: 0 20px 60px rgba(91, 141, 238, 0.15);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Left Section - Illustration */
        .left-section {
            background: linear-gradient(135deg, #5B8DEE 0%, #8B7FE8 50%, #66D9E8 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .left-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-30px, -30px); }
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .left-header {
            background: rgba(255, 255, 255, 0.15);
            padding: 24px 28px;
            border-radius: 12px;
            margin-bottom: 32px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .left-header h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .left-header p {
            font-size: 16px;
            font-weight: 600;
        }

        .illustration {
            margin: 40px 0;
        }

        .illustration svg {
            width: 100%;
            max-width: 300px;
            height: auto;
        }

        /* Right Section */
        .right-section {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 32px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 14px;
            color: var(--text-medium);
        }

        /* Progress indicator */
        .progress-steps {
            display: flex;
            gap: 8px;
            margin-bottom: 28px;
            justify-content: space-between;
        }

        .step {
            flex: 1;
            height: 4px;
            background: rgba(91, 141, 238, 0.1);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .step.active {
            background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 100%);
            box-shadow: 0 2px 8px rgba(91, 141, 238, 0.2);
        }

        /* Alert Messages */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(125, 212, 165, 0.1);
            color: #047857;
            border: 1px solid rgba(125, 212, 165, 0.3);
        }

        .alert-error {
            background: rgba(248, 113, 113, 0.1);
            color: #991b1b;
            border: 1px solid rgba(248, 113, 113, 0.3);
        }

        /* Google Register Button */
        .google-register-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 14px;
            border: 2px solid rgba(91, 141, 238, 0.2);
            border-radius: 12px;
            background: rgba(91, 141, 238, 0.05);
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 24px;
        }

        .google-register-btn:hover {
            border-color: var(--primary-blue);
            background: rgba(91, 141, 238, 0.1);
            box-shadow: 0 8px 20px rgba(91, 141, 238, 0.15);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: var(--text-light);
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(91, 141, 238, 0.1);
        }

        .divider span {
            padding: 0 12px;
            font-size: 13px;
            color: var(--text-medium);
        }

        /* Form Group */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid rgba(91, 141, 238, 0.15);
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: rgba(91, 141, 238, 0.02);
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: white;
            box-shadow: 0 0 0 3px rgba(91, 141, 238, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-light);
        }

        .input-error {
            border-color: #ef4444 !important;
            background: rgba(239, 68, 68, 0.05);
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        /* Two column layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 8px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .strength-bar {
            flex: 1;
            height: 5px;
            background: rgba(91, 141, 238, 0.1);
            border-radius: 3px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #ff6b6b;
            transition: all 0.3s ease;
            border-radius: 3px;
        }

        .strength-fill.weak {
            width: 33%;
            background: linear-gradient(90deg, #ff6b6b, #ff8e6b);
        }

        .strength-fill.medium {
            width: 66%;
            background: linear-gradient(90deg, #ffd43b, #ffed7f);
        }

        .strength-fill.strong {
            width: 100%;
            background: linear-gradient(90deg, #51cf66, #69d977);
        }

        .strength-text {
            color: var(--text-medium);
            font-size: 11px;
            min-width: 35px;
        }

        /* Register Button */
        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            margin-bottom: 20px;
            box-shadow: 0 8px 20px rgba(91, 141, 238, 0.2);
        }

        .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(91, 141, 238, 0.3);
        }

        .register-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        /* Login Link */
        .login-section {
            text-align: center;
            font-size: 13px;
            color: var(--text-medium);
            margin-bottom: 16px;
        }

        .login-section a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .login-section a:hover {
            text-decoration: underline;
        }

        /* Terms */
        .terms-text {
            font-size: 12px;
            color: var(--text-medium);
            text-align: center;
            margin-bottom: 16px;
            line-height: 1.4;
        }

        .terms-text a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .terms-text a:hover {
            text-decoration: underline;
        }

        /* Benefits section */
        .benefits {
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.08), rgba(102, 217, 232, 0.08));
            border-radius: 12px;
            padding: 16px;
            border: 1.5px solid rgba(91, 141, 238, 0.15);
            border-left: 4px solid var(--primary-blue);
        }

        .benefit-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
            color: var(--text-dark);
        }

        .benefit-item:last-child {
            margin-bottom: 0;
        }

        .benefit-item i {
            color: var(--primary-blue);
            font-weight: 600;
            min-width: 16px;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .left-section {
                padding: 40px 30px;
            }

            .right-section {
                padding: 40px 30px;
            }
        }

        @media (max-width: 768px) {
            .left-section {
                display: none;
            }

            .main-container {
                max-width: 100%;
                border-radius: 20px;
            }

            .right-section {
                padding: 40px 24px;
            }

            .form-header h1 {
                font-size: 24px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- Left Section -->
        <div class="left-section">
            <div class="left-content">
                <div class="left-header">
                    <h2>TIMLY</h2>
                    <p>Project Management</p>
                </div>

                <div class="illustration">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background circles with animation -->
                        <circle cx="350" cy="80" r="60" fill="rgba(255,255,255,0.08)">
                            <animate attributeName="r" values="60;70;60" dur="4s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="80" cy="320" r="50" fill="rgba(255,255,255,0.06)">
                            <animate attributeName="r" values="50;60;50" dur="5s" repeatCount="indefinite"/>
                        </circle>

                        <!-- Platform -->
                        <ellipse cx="200" cy="280" rx="120" ry="40" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.3)" stroke-width="2">
                            <animate attributeName="ry" values="40;42;40" dur="3s" repeatCount="indefinite"/>
                        </ellipse>

                        <!-- Person 1 - Developer -->
                        <g>
                            <circle cx="100" cy="180" r="24" fill="#FCD34D">
                                <animate attributeName="cy" values="180;178;180" dur="4s" repeatCount="indefinite"/>
                            </circle>
                            <path d="M 85 170 Q 100 160 115 170" fill="#78350F" opacity="0.8"/>
                            <circle cx="93" cy="178" r="2.5" fill="#1e1e1e"/>
                            <circle cx="107" cy="178" r="2.5" fill="#1e1e1e"/>
                            <path d="M 93 188 Q 100 192 107 188" stroke="#1e1e1e" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <rect x="85" y="205" width="30" height="55" rx="15" fill="#3B82F6">
                                <animate attributeName="y" values="205;203;205" dur="4s" repeatCount="indefinite"/>
                            </rect>
                            <path d="M 85 220 Q 70 225 65 235" stroke="#FCD34D" stroke-width="11" stroke-linecap="round">
                                <animate attributeName="d" values="M 85 220 Q 70 225 65 235; M 85 220 Q 70 223 65 233; M 85 220 Q 70 225 65 235" dur="1s" repeatCount="indefinite"/>
                            </path>
                            <path d="M 115 220 Q 130 225 135 235" stroke="#FCD34D" stroke-width="11" stroke-linecap="round">
                                <animate attributeName="d" values="M 115 220 Q 130 225 135 235; M 115 220 Q 130 223 135 233; M 115 220 Q 130 225 135 235" dur="1s" repeatCount="indefinite" begin="0.5s"/>
                            </path>
                            <rect x="92" y="260" width="7" height="30" rx="3" fill="#1e293b"/>
                            <rect x="101" y="260" width="7" height="30" rx="3" fill="#1e293b"/>
                        </g>

                        <!-- Person 2 - Manager -->
                        <g>
                            <circle cx="200" cy="160" r="26" fill="#F59E0B">
                                <animate attributeName="cy" values="160;158;160" dur="3s" repeatCount="indefinite"/>
                            </circle>
                            <ellipse cx="200" cy="150" rx="20" ry="12" fill="#92400E"/>
                            <circle cx="192" cy="158" r="3" fill="#1e1e1e"/>
                            <circle cx="208" cy="158" r="3" fill="#1e1e1e"/>
                            <path d="M 190 168 Q 200 173 210 168" stroke="#1e1e1e" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <rect x="182" y="187" width="36" height="60" rx="18" fill="#8B5CF6">
                                <animate attributeName="y" values="187;185;187" dur="3s" repeatCount="indefinite"/>
                            </rect>
                            <path d="M 182 205 Q 160 200 145 185" stroke="#F59E0B" stroke-width="12" stroke-linecap="round">
                                <animate attributeName="d" values="M 182 205 Q 160 200 145 185; M 182 205 Q 160 195 140 180; M 182 205 Q 160 200 145 185" dur="2s" repeatCount="indefinite"/>
                            </path>
                            <path d="M 218 205 Q 240 200 255 185" stroke="#F59E0B" stroke-width="12" stroke-linecap="round">
                                <animate attributeName="d" values="M 218 205 Q 240 200 255 185; M 218 205 Q 240 195 260 180; M 218 205 Q 240 200 255 185" dur="2s" repeatCount="indefinite" begin="1s"/>
                            </path>
                            <rect x="192" y="247" width="7" height="35" rx="3" fill="#1e293b"/>
                            <rect x="201" y="247" width="7" height="35" rx="3" fill="#1e293b"/>
                        </g>

                        <!-- Person 3 - Designer -->
                        <g>
                            <circle cx="300" cy="185" r="23" fill="#EC4899">
                                <animate attributeName="cy" values="185;183;185" dur="3.5s" repeatCount="indefinite"/>
                            </circle>
                            <circle cx="315" cy="180" r="8" fill="#9D174D"/>
                            <ellipse cx="300" cy="175" rx="18" ry="10" fill="#9D174D"/>
                            <circle cx="293" cy="183" r="2.5" fill="#1e1e1e"/>
                            <circle cx="307" cy="183" r="2.5" fill="#1e1e1e"/>
                            <path d="M 293 192 Q 300 195 307 192" stroke="#1e1e1e" stroke-width="2" fill="none" stroke-linecap="round"/>
                            <rect x="285" y="210" width="30" height="52" rx="15" fill="#10B981">
                                <animate attributeName="y" values="210;208;210" dur="3.5s" repeatCount="indefinite"/>
                            </rect>
                            <path d="M 285 225 Q 270 230 265 235" stroke="#EC4899" stroke-width="10" stroke-linecap="round"/>
                            <rect x="255" y="230" width="25" height="18" rx="2" fill="#60A5FA" stroke="#3B82F6" stroke-width="1.5"/>
                            <path d="M 315 225 Q 295 235 280 240" stroke="#EC4899" stroke-width="10" stroke-linecap="round">
                                <animate attributeName="d" values="M 315 225 Q 295 235 280 240; M 315 225 Q 295 233 278 238; M 315 225 Q 295 235 280 240" dur="1.5s" repeatCount="indefinite"/>
                            </path>
                            <rect x="292" y="262" width="7" height="28" rx="3" fill="#1e293b"/>
                            <rect x="301" y="262" width="7" height="28" rx="3" fill="#1e293b"/>
                        </g>

                        <!-- Notification icons -->
                        <g transform="translate(50, 100)">
                            <circle cx="0" cy="0" r="12" fill="#EF4444" opacity="0.9">
                                <animate attributeName="cy" values="0;-5;0" dur="2s" repeatCount="indefinite"/>
                            </circle>
                            <path d="M -4 -2 Q 0 -6 4 -2 L 4 2 Q 4 4 2 4 L -2 4 Q -4 4 -4 2 Z" fill="white"/>
                            <circle cx="0" cy="5" r="1" fill="white"/>
                        </g>

                        <g transform="translate(350, 200)">
                            <circle cx="0" cy="0" r="12" fill="#3B82F6" opacity="0.9">
                                <animate attributeName="cy" values="0;-5;0" dur="2.5s" repeatCount="indefinite"/>
                            </circle>
                            <rect x="-5" y="-3" width="10" height="6" rx="1" fill="white"/>
                            <path d="M -5 -3 L 0 1 L 5 -3" stroke="white" stroke-width="1" fill="none"/>
                        </g>

                        <!-- Text bubbles -->
                        <g>
                            <ellipse cx="60" cy="160" rx="28" ry="18" fill="rgba(255,255,255,0.95)">
                                <animate attributeName="ry" values="18;20;18" dur="2s" repeatCount="indefinite"/>
                            </ellipse>
                            <polygon points="50,175 42,185 55,178" fill="rgba(255,255,255,0.95)"/>
                            <text x="60" y="165" font-size="20" fill="#5B8DEE" text-anchor="middle">💡</text>

                            <ellipse cx="340" cy="170" rx="28" ry="18" fill="rgba(255,255,255,0.95)">
                                <animate attributeName="ry" values="18;20;18" dur="2.5s" repeatCount="indefinite"/>
                            </ellipse>
                            <polygon points="350,185 358,195 345,188" fill="rgba(255,255,255,0.95)"/>
                            <text x="340" y="175" font-size="20" fill="#5B8DEE" text-anchor="middle">✓</text>
                        </g>

                        <!-- Success indicator -->
                        <g transform="translate(360, 340)">
                            <circle cx="0" cy="0" r="18" fill="#10B981" opacity="0.9">
                                <animate attributeName="r" values="18;20;18" dur="2s" repeatCount="indefinite"/>
                            </circle>
                            <path d="M -6 0 L -2 4 L 6 -4" stroke="white" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                        </g>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div class="form-header">
                <h1>Buat Akun</h1>
                <p>Daftar dan mulai mengelola proyek Anda</p>
            </div>

            <!-- Progress Steps -->
            <div class="progress-steps">
                <div class="step active"></div>
                <div class="step active"></div>
                <div class="step"></div>
            </div>

            @if (session('status'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Google Register Button -->
            <a href="#" class="google-register-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                <span>Daftar dengan Google</span>
            </a>

            <div class="divider">
                <span>Atau</span>
            </div>

            <!-- Register Form -->
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name" class="form-label">Nama Lengkap</label>
                        <input
                            id="full_name"
                            name="full_name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            value="{{ old('full_name') }}"
                            class="form-input @error('full_name') input-error @enderror"
                            placeholder="Masukkan nama lengkap">
                        @error('full_name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            autocomplete="username"
                            value="{{ old('email') }}"
                            class="form-input @error('email') input-error @enderror"
                            placeholder="example@email.com">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="form-input @error('password') input-error @enderror"
                            placeholder="Minimal 8 karakter"
                            onkeyup="checkPasswordStrength(this.value)">
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-text" id="strengthText"></span>
                        </div>
                        @error('password')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="form-input @error('password_confirmation') input-error @enderror"
                            placeholder="Ulangi password">
                        @error('password_confirmation')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="register-btn" id="submitBtn">
                    Daftar Sekarang
                </button>
            </form>

            @if (Route::has('login'))
                <div class="login-section">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Masuk di sini</a>
                </div>
            @endif

            <div class="terms-text">
                Dengan mendaftar, Anda setuju dengan
                <a href="#">Syarat & Ketentuan</a> dan
                <a href="#">Kebijakan Privasi</a>
            </div>

            <!-- Benefits Section -->
            <div class="benefits">
                <div class="benefit-item">
                    <i class="fas fa-check"></i>
                    <span>Kelola proyek dengan kanban board</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-check"></i>
                    <span>Kolaborasi real-time dengan tim</span>
                </div>
                <div class="benefit-item">
                    <i class="fas fa-check"></i>
                    <span>Lacak progress proyek secara mudah</span>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Canvas Confetti JS -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

    <script>
        // Password Strength Checker
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/\d/)) strength++;
            if (password.match(/[^a-zA-Z\d]/)) strength++;

            strengthFill.className = 'strength-fill';

            if (password.length === 0) {
                strengthText.textContent = '';
            } else if (strength <= 1) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Lemah';
            } else if (strength <= 2) {
                strengthFill.classList.add('medium');
                strengthText.textContent = 'Sedang';
            } else {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Kuat';
            }
        }

        // Form Submit Loading Animation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        });

        // Confetti Animation Function
        function launchConfetti() {
            var duration = 3 * 1000;
            var animationEnd = Date.now() + duration;
            var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 99999 };

            function randomInRange(min, max) {
                return Math.random() * (max - min) + min;
            }

            var interval = setInterval(function() {
                var timeLeft = animationEnd - Date.now();

                if (timeLeft <= 0) {
                    return clearInterval(interval);
                }

                var particleCount = 50 * (timeLeft / duration);

                confetti(Object.assign({}, defaults, {
                    particleCount,
                    origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                }));

                confetti(Object.assign({}, defaults, {
                    particleCount,
                    origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                }));
            }, 250);
        }

        @if(session('success'))
            launchConfetti();

            setTimeout(function() {
                Swal.fire({
                    icon: 'success',
                    title: '🎉 Selamat!',
                    html: '<div style="font-size: 16px; color: #374151;">{{ session('success') }}</div><div style="font-size: 14px; color: #6b7280; margin-top: 10px;">Akun Anda telah berhasil dibuat!</div>',
                    showConfirmButton: true,
                    confirmButtonText: 'Mulai Sekarang',
                    confirmButtonColor: '#5B8DEE',
                    timer: 5000,
                    timerProgressBar: true,
                    showClass: {
                        popup: 'animate__animated animate__bounceIn'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut'
                    },
                    didOpen: () => {
                        confetti({
                            particleCount: 100,
                            spread: 70,
                            origin: { y: 0.6 }
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('login') }}';
                    }
                });
            }, 500);
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `
                    <div style="text-align: left; max-height: 200px; overflow-y: auto;">
                        @foreach($errors->all() as $error)
                            <div style="margin: 8px 0; padding: 8px; background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 4px;">
                                <i class="fas fa-exclamation-circle" style="color: #ef4444;"></i> {{ $error }}
                            </div>
                        @endforeach
                    </div>
                `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#ef4444',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        @endif

        @if(session('status'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('status') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#5B8DEE',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                }
            });
        @endif
    </script>
</body>
</html>
