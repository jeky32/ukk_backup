<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="">
    <title>Reset Password - Timly</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
            z-index: 0;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(30px, 30px) rotate(5deg); }
        }

        .main-container {
            background: white;
            border-radius: 32px;
            width: 100%;
            max-width: 1100px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.4);
            overflow: hidden;
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            z-index: 1;
        }

        /* Left Section */
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
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            animation: float 10s ease-in-out infinite;
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .left-header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 28px 32px;
            border-radius: 16px;
            margin-bottom: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: slideDown 0.6s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .left-header h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .left-header p {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
        }

        .left-header small {
            font-size: 12px;
            opacity: 0.85;
            display: block;
            margin-top: 8px;
        }

        .illustration {
            margin: 40px 0;
            animation: slideUp 0.8s ease-out 0.2s both;
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

        .illustration svg {
            width: 100%;
            max-width: 320px;
            height: auto;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.15));
        }

        /* Right Section */
        .right-section {
            padding: 70px 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-header p {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.7;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInDown 0.4s ease-out;
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        .info-box {
            background: linear-gradient(135deg, #f0f4ff 0%, #f8f5ff 100%);
            border-left: 4px solid #667eea;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 28px;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .info-box p {
            font-size: 13px;
            color: #374151;
            line-height: 1.7;
            margin: 0;
        }

        .info-box strong {
            color: #1a1a1a;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #f9fafb;
            font-family: 'Inter', sans-serif;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .reset-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .reset-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s ease;
        }

        .reset-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(102, 126, 234, 0.4);
        }

        .reset-btn:hover::before {
            left: 100%;
        }

        .reset-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-links {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .back-links a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 6px;
        }

        .back-links a:hover {
            background: rgba(102, 126, 234, 0.1);
            gap: 10px;
        }

        .back-links a i {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .back-links a:hover i {
            opacity: 1;
        }

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
            margin-bottom: 16px;
        }

        .success-message small {
            display: block;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 8px;
        }

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
                font-size: 26px;
            }

            .back-links {
                gap: 12px;
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
                    <h3>Timly</h3>
                    <p>Secure Access</p>
                    <small>Reset your password safely</small>
                </div>

                <div class="illustration">
                    <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="350" cy="80" r="60" fill="rgba(255,255,255,0.08)"/>
                        <circle cx="80" cy="320" r="50" fill="rgba(255,255,255,0.06)"/>

                        <circle cx="200" cy="120" r="50" fill="rgba(255,255,255,0.15)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                        <circle cx="200" cy="120" r="30" fill="none" stroke="rgba(255,255,255,0.8)" stroke-width="3"/>
                        <rect x="225" y="110" width="60" height="20" rx="3" fill="rgba(255,255,255,0.8)"/>
                        <circle cx="235" cy="120" r="4" fill="rgba(255,255,255,0.8)"/>
                        <circle cx="250" cy="120" r="4" fill="rgba(255,255,255,0.8)"/>
                        <circle cx="265" cy="120" r="4" fill="rgba(255,255,255,0.8)"/>

                        <rect x="100" y="220" width="200" height="120" rx="12" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.3)" stroke-width="2"/>
                        <path d="M 100 230 L 200 290 L 300 230" stroke="rgba(255,255,255,0.6)" stroke-width="2" fill="none" stroke-linecap="round"/>

                        <circle cx="80" cy="250" r="20" fill="#FFB84D"/>
                        <circle cx="75" cy="245" r="2.5" fill="#1e1e1e"/>
                        <circle cx="85" cy="245" r="2.5" fill="#1e1e1e"/>
                        <path d="M 75 255 Q 80 258 85 255" stroke="#1e1e1e" stroke-width="1.5" fill="none" stroke-linecap="round"/>
                        <rect x="68" y="272" width="24" height="40" rx="12" fill="#3B82F6"/>
                        <rect x="50" y="278" width="16" height="8" rx="4" fill="#FFB84D"/>

                        <circle cx="320" cy="300" r="35" fill="#10B981" opacity="0.9"/>
                        <path d="M 305 300 L 315 310 L 335 290" stroke="white" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/>

                        <path d="M 150 80 L 150 100" stroke="rgba(255,255,255,0.4)" stroke-width="2" stroke-linecap="round"/>
                        <circle cx="150" cy="75" r="3" fill="rgba(255,255,255,0.6)"/>
                        <circle cx="250" cy="65" r="5" fill="rgba(255,255,255,0.3)"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="right-section">
            <div id="alert-container"></div>

            <!-- Form Content -->
            <div id="form-content">
                <div class="form-header">
                    <h1>Reset Your Password</h1>
                    <p>Enter your email address and we'll send you a secure link to reset your password</p>
                </div>

                <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            class="form-input"
                            placeholder="name@example.com">
                    </div>

                    <div class="info-box">
                        <p><strong>Pro tip:</strong> Check your inbox and spam folder. The reset link will arrive within a few minutes and expires in 1 hour.</p>
                    </div>

                    <button type="submit" class="reset-btn">
                        <i class="fas fa-envelope" style="margin-right: 10px;"></i>Send Reset Link
                    </button>
                </form>
            </div>

            <!-- Success Message -->
            <div class="success-message" id="successMessage">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h2>Email Sent!</h2>
                <p>We've sent a password reset link to your email address.</p>
                <p style="font-size: 13px;">Check your inbox and click the link to reset your password. The link expires in 1 hour.</p>
                <small>Didn't receive it? Check your spam folder or request a new link.</small>
            </div>

            <!-- Back Links -->
            <div class="back-links">
                <a href="{{ route('login') }}">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Login</span>
                </a>
                <a href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i>
                    <span>Create Account</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = this.querySelector('.reset-btn');
            const originalHTML = submitBtn.innerHTML;

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 10px;"></i>Sending...';
            submitBtn.disabled = true;

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success' || data.message) {
                    document.getElementById('form-content').style.display = 'none';
                    document.getElementById('successMessage').style.display = 'block';
                } else {
                    const alertContainer = document.getElementById('alert-container');
                    alertContainer.innerHTML = `
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>${data.message || 'An error occurred. Please try again.'}</span>
                        </div>
                    `;
                    submitBtn.innerHTML = originalHTML;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                document.getElementById('form-content').style.display = 'none';
                document.getElementById('successMessage').style.display = 'block';
            });
        });
    </script>
</body>
</html>
