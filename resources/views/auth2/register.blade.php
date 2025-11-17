<script>
        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]+/)) strength++;


            strengthFill.className = 'strength-fill';

            if (password.length === 0) {
                strengthText.textContent = '';
                strengthFill.className = 'strength-fill';
            } else if (strength < 2) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Lemah';
            } else if (strength < 4) {
                strengthFill.classList.add('medium');
                strengthText.textContent = 'Sedang';
            } else {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Kuat';
            }
        }

        // Untuk Laravel flash messages, uncomment ini:
        /*
        document.addEventListener('DOMContentLoaded', function() {
            const alertContainer = document.getElementById('alert-container');

            @if (session('success'))
                alertContainer.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                `;
            @endif

            @if ($errors->any())
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </span>
                    </div>
                `;
            @endif
        });
        */
    </script><!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Timly</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Left Section - Illustration */
        .left-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            background: rgba(255, 255, 255, 0.2);
            padding: 24px 28px;
            border-radius: 12px;
            margin-bottom: 32px;
            backdrop-filter: blur(10px);
        }

        .left-header h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .left-header p {
            font-size: 24px;
            font-weight: 700;
            line-height: 1.3;
        }

        .left-header small {
            font-size: 12px;
            opacity: 0.8;
            display: block;
            margin-top: 8px;
        }

        .illustration {
            margin: 40px 0;
        }

        .illustration svg {
            width: 100%;
            max-width: 300px;
            height: auto;
        }

        /* Right Section - Register Form */
        .right-section {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1e1e1e;
            margin-bottom: 8px;
        }

        .form-header p {
            font-size: 14px;
            color: #6b7280;
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
            height: 3px;
            background: #e5e7eb;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .step.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Benefits section */
        .benefits {
            background: #f8f9ff;
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }

        .benefit-item {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 13px;
            color: #374151;
        }

        .benefit-item:last-child {
            margin-bottom: 0;
        }

        .benefit-item i {
            color: #667eea;
            font-weight: 600;
            min-width: 16px;
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 6px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            width: 0%;
            background: #ff6b6b;
            transition: all 0.3s ease;
        }

        .strength-fill.weak {
            width: 33%;
            background: #ff6b6b;
        }

        .strength-fill.medium {
            width: 66%;
            background: #ffd43b;
        }

        .strength-fill.strong {
            width: 100%;
            background: #51cf66;
        }

        .strength-text {
            color: #6b7280;
            font-size: 11px;
        }

        .register-section a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .register-section a:hover {
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        /* Google Register Button */
        .google-register-btn {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 14px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            color: #1e1e1e;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-bottom: 24px;
        }

        .google-register-btn:hover {
            border-color: #667eea;
            background: #f8f9ff;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            margin: 24px 0;
            color: #d1d5db;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .divider span {
            padding: 0 12px;
            font-size: 13px;
            color: #9ca3af;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .form-select option {
            background: white;
            color: #1e1e1e;
        }

        /* Two column layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* Register Button */
        .register-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Login Link */
        .login-section {
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }

        .login-section a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-section a:hover {
            text-decoration: underline;
        }

        /* Terms */
        .terms-text {
            font-size: 12px;
            color: #6b7280;
            text-align: center;
            margin-top: 12px;
            line-height: 1.4;
        }

        .terms-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        /* Success Message */
        .success-message {
            display: none;
            text-align: center;
            animation: scaleInCenter 0.6s ease-out;
        }

        @keyframes scaleInCenter {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            font-size: 50px;
            color: white;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
            animation: bounce 0.8s ease-out;
        }

        @keyframes bounce {
            0% {
                transform: scale(0.3) rotateZ(-45deg);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .success-message h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .success-message p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.8;
            margin-bottom: 24px;
        }

        .success-message a {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .success-message a:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr;
            }

            .left-section {
                display: none;
            }

            .right-section {
                padding: 40px 30px;
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
                    <h3>Teamline</h3>
                    <p>Project Management Service</p>
                    <small>Bergabunglah dengan ribuan tim yang produktif</small>
                </div>

                <div class="illustration">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <!-- Background decorative circles -->
                        <circle cx="350" cy="80" r="60" fill="rgba(255,255,255,0.08)"/>
                        <circle cx="80" cy="320" r="50" fill="rgba(255,255,255,0.06)"/>

                        <!-- Central Table/Meeting Circle -->
                        <ellipse cx="200" cy="240" rx="80" ry="30" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="2"/>

                        <!-- Meeting Items on table -->
                        <rect x="140" y="225" width="30" height="20" rx="3" fill="#ff6b6b" opacity="0.8"/>
                        <rect x="230" y="225" width="30" height="20" rx="3" fill="#4ecdc4" opacity="0.8"/>
                        <circle cx="200" cy="235" r="8" fill="#ffd43b" opacity="0.8"/>

                        <!-- Person 1 (Top Left) - Leaning forward enthusiastically -->
                        <!-- Head -->
                        <circle cx="90" cy="120" r="22" fill="#FFB84D"/>
                        <circle cx="82" cy="115" r="3" fill="#1e1e1e"/>
                        <circle cx="98" cy="115" r="3" fill="#1e1e1e"/>
                        <path d="M 82 125 Q 90 128 98 125" stroke="#1e1e1e" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <!-- Smile -->
                        <path d="M 85 128 Q 90 131 95 128" stroke="#1e1e1e" stroke-width="1.5" fill="none"/>
                        <!-- Body (leaning) -->
                        <rect x="75" y="145" width="30" height="50" rx="15" fill="#3B82F6" transform="rotate(-15 90 145)"/>
                        <!-- Arms pointing to center -->
                        <path d="M 65 155 Q 50 170 40 180" stroke="#FFB84D" stroke-width="10" stroke-linecap="round" opacity="0.9"/>
                        <rect x="65" y="150" width="12" height="8" rx="4" fill="#FFB84D"/>
                        <!-- Legs -->
                        <rect x="82" y="195" width="7" height="30" rx="3" fill="#1e293b"/>
                        <rect x="91" y="195" width="7" height="30" rx="3" fill="#1e293b"/>

                        <!-- Person 2 (Top Right) - Enthusiastic, hands up -->
                        <!-- Head -->
                        <circle cx="310" cy="110" r="22" fill="#F472B6"/>
                        <circle cx="302" cy="105" r="3" fill="#1e1e1e"/>
                        <circle cx="318" cy="105" r="3" fill="#1e1e1e"/>
                        <path d="M 302 115 Q 310 118 318 115" stroke="#1e1e1e" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <!-- Smile wide -->
                        <path d="M 305 120 Q 310 123 315 120" stroke="#1e1e1e" stroke-width="2" fill="none"/>
                        <!-- Body -->
                        <rect x="295" y="135" width="30" height="50" rx="15" fill="#A855F7"/>
                        <!-- Arms raised (celebrating) -->
                        <path d="M 280 145 Q 270 120 265 90" stroke="#F472B6" stroke-width="10" stroke-linecap="round" opacity="0.9"/>
                        <path d="M 320 145 Q 330 120 335 90" stroke="#F472B6" stroke-width="10" stroke-linecap="round" opacity="0.9"/>
                        <!-- Legs -->
                        <rect x="302" y="185" width="7" height="35" rx="3" fill="#1e293b"/>
                        <rect x="311" y="185" width="7" height="35" rx="3" fill="#1e293b"/>

                        <!-- Person 3 (Bottom Center-Left) - Sitting, relaxed -->
                        <!-- Head -->
                        <circle cx="140" cy="180" r="20" fill="#FBBF24"/>
                        <circle cx="133" cy="176" r="2.5" fill="#1e1e1e"/>
                        <circle cx="147" cy="176" r="2.5" fill="#1e1e1e"/>
                        <path d="M 133 185 Q 140 188 147 185" stroke="#1e1e1e" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <!-- Smile -->
                        <path d="M 136 189 Q 140 191 144 189" stroke="#1e1e1e" stroke-width="1" fill="none"/>
                        <!-- Body (seated) -->
                        <rect x="128" y="202" width="24" height="45" rx="12" fill="#06B6D4"/>
                        <!-- Arms on table -->
                        <rect x="110" y="210" width="18" height="8" rx="4" fill="#FBBF24"/>
                        <rect x="152" y="210" width="18" height="8" rx="4" fill="#FBBF24"/>
                        <!-- Legs under table -->
                        <rect x="134" y="247" width="6" height="25" rx="3" fill="#1e293b"/>
                        <rect x="142" y="247" width="6" height="25" rx="3" fill="#1e293b"/>

                        <!-- Person 4 (Bottom Center-Right) - Sitting, speaking -->
                        <!-- Head -->
                        <circle cx="260" cy="180" r="20" fill="#FB7185"/>
                        <circle cx="253" cy="176" r="2.5" fill="#1e1e1e"/>
                        <circle cx="267" cy="176" r="2.5" fill="#1e1e1e"/>
                        <path d="M 253 185 Q 260 189 267 185" stroke="#1e1e1e" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <!-- Open mouth (speaking) -->
                        <ellipse cx="260" cy="191" rx="3" ry="4" fill="#1e1e1e"/>
                        <!-- Body (seated) -->
                        <rect x="248" y="202" width="24" height="45" rx="12" fill="#10B981"/>
                        <!-- Arms gesturing -->
                        <path d="M 245 215 Q 225 210 215 200" stroke="#FB7185" stroke-width="9" stroke-linecap="round" opacity="0.9"/>
                        <rect x="248" y="210" width="10" height="7" rx="3" fill="#FB7185"/>
                        <!-- Legs under table -->
                        <rect x="254" y="247" width="6" height="25" rx="3" fill="#1e293b"/>
                        <rect x="262" y="247" width="6" height="25" rx="3" fill="#1e293b"/>

                        <!-- Laptops on table -->
                        <rect x="155" y="255" width="20" height="12" rx="2" fill="#374151" opacity="0.7"/>
                        <rect x="225" y="255" width="20" height="12" rx="2" fill="#374151" opacity="0.7"/>

                        <!-- Chat bubbles showing communication -->
                        <!-- Bubble 1 (Person 1) -->
                        <ellipse cx="50" cy="130" rx="25" ry="16" fill="rgba(255,255,255,0.95)"/>
                        <polygon points="45,145 35,155 48,148" fill="rgba(255,255,255,0.95)"/>
                        <circle cx="45" cy="130" r="2" fill="#667eea"/>
                        <circle cx="55" cy="130" r="2" fill="#667eea"/>
                        <circle cx="65" cy="130" r="2" fill="#667eea"/>

                        <!-- Bubble 2 (Person 2) -->
                        <ellipse cx="360" cy="85" rx="25" ry="16" fill="rgba(255,255,255,0.95)"/>
                        <polygon points="375,95 385,75 370,88" fill="rgba(255,255,255,0.95)"/>
                        <circle cx="355" cy="85" r="2" fill="#667eea"/>
                        <circle cx="365" cy="85" r="2" fill="#667eea"/>

                        <!-- Decorative elements -->
                        <circle cx="50" cy="340" r="14" fill="rgba(255,255,255,0.9)"/>
                        <path d="M 45 335 L 50 340 L 55 335" stroke="#667eea" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <line x1="45" y1="345" x2="55" y2="345" stroke="#667eea" stroke-width="2" stroke-linecap="round"/>

                        <!-- Success indicator -->
                        <circle cx="360" cy="350" r="16" fill="#10B981" opacity="0.8"/>
                        <path d="M 355 350 L 360 355 L 368 345" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
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

            <!-- Alert Container -->
            <div id="alert-container"></div>

            <!-- Google Register Button -->
            <a href="{{ route('auth.google') }}" class="google-register-btn">
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
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            required
                            value="{{ old('name') }}"
                            class="form-input"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="{{ old('email') }}"
                            class="form-input"
                            placeholder="Masukkan email aktif">
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
                            class="form-input"
                            placeholder="Minimal 8 karakter"
                            onkeyup="checkPasswordStrength(this.value)">
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strengthFill"></div>
                            </div>
                            <span class="strength-text" id="strengthText"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="form-input"
                            placeholder="Ulangi password">
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    Daftar Sekarang
                </button>
            </form>

            <div class="login-section">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a>
            </div>

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
                    <span>Akses gratis untuk 3 proyek pertama</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthFill = document.getElementById('strengthFill');
            const strengthText = document.getElementById('strengthText');

            let strength = 0;

            if (password.length >= 8) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            strengthFill.className = 'strength-fill';

            if (password.length === 0) {
                strengthText.textContent = '';
                strengthFill.className = 'strength-fill';
            } else if (strength < 2) {
                strengthFill.classList.add('weak');
                strengthText.textContent = 'Lemah';
            } else if (strength < 4) {
                strengthFill.classList.add('medium');
                strengthText.textContent = 'Sedang';
            } else {
                strengthFill.classList.add('strong');
                strengthText.textContent = 'Kuat';
            }
        }

        // Untuk Laravel flash messages, uncomment ini:
        /*
        document.addEventListener('DOMContentLoaded', function() {
            const alertContainer = document.getElementById('alert-container');

            @if (session('success'))
                alertContainer.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                `;
            @endif

            @if ($errors->any())
                alertContainer.innerHTML = `
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>
                            @foreach ($errors->all() as $error)
                                {{ $error }}
                            @endforeach
                        </span>
                    </div>
                `;
            @endif
        });
        */
    </script>
</body>
</html>
