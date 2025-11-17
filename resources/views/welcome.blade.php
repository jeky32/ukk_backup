<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TIMLY - Modern Project Management Platform</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            --warning-amber: #F5C77D;
            --bg-light: #F9FAFB;
            --bg-medium: #E8EDF5;
            --bg-card: #FFFFFF;
            --text-dark: #2D3748;
            --text-medium: #5A6B83;
            --text-light: #8894A8;
            --border-light: #D8E1EE;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: linear-gradient(135deg, #F9FAFB 0%, #EEF2F7 50%, #E8EDF5 100%);
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* ===== ANIMATED BACKGROUND ELEMENTS ===== */
        .bg-elements {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            pointer-events: none;
        }

        .blob-1 {
            position: absolute;
            width: 120%;
            height: 120px;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.15) 0%, rgba(102, 217, 232, 0.12) 100%);
            border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%;
            animation: blob-float 8s ease-in-out infinite;
            top: 5%;
            right: -8%;
            filter: blur(40px);
        }

        .blob-2 {
            position: absolute;
            width: 90px;
            height:90px;
            background: linear-gradient(135deg, rgba(139, 127, 232, 0.15) 0%, rgba(232, 152, 199, 0.12) 100%);
            border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
            animation: blob-float 12s ease-in-out infinite reverse;
            bottom: 8%;
            left: -6%;
            filter: blur(40px);
        }

        .blob-3 {
            position: absolute;
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, rgba(136, 232, 208, 0.12) 0%, rgba(125, 212, 165, 0.1) 100%);
            border-radius: 70% 30% 30% 70% / 30% 70% 70% 30%;
            animation: blob-float 10s ease-in-out infinite;
            top: 45%;
            right: 8%;
            animation-delay: -5s;
            filter: blur(40px);
        }

        @keyframes blob-float {
            0%, 100% {
                transform: translate(0, 0) scale(1) rotate(0deg);
            }
            25% {
                transform: translate(25px, -40px) scale(1.05) rotate(90deg);
            }
            50% {
                transform: translate(-25px, 25px) scale(0.95) rotate(180deg);
            }
            75% {
                transform: translate(40px, 40px) scale(1.02) rotate(270deg);
            }
        }

       /* ===== NAVBAR (UPDATED) ===== */
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background: rgba(249, 250, 251, 0.75);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-bottom: 1px solid rgba(216, 225, 238, 0.3);
    z-index: 9999;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 0.75rem 0;
    box-shadow: 0 2px 12px rgba(91, 141, 238, 0.04);
}

.navbar.scrolled {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(30px) saturate(200%);
    -webkit-backdrop-filter: blur(30px) saturate(200%);
    padding: 0.5rem 0;
    box-shadow: 0 4px 24px rgba(91, 141, 238, 0.1);
    border-bottom: 1px solid rgba(91, 141, 238, 0.15);
}

.nav-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 3rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
}

.logo::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    width: 0;
    height: 3px;
    background: linear-gradient(90deg, #5B8DEE, #66D9E8);
    border-radius: 10px;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.logo:hover::after {
    width: 100%;
}

.logo:hover {
    transform: translateY(-2px);
}

.logo-icon {
    position: relative;
    width: 52px;
    height: 52px;
    background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 100%);
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 24px rgba(91, 141, 238, 0.35),
                0 0 0 0 rgba(91, 141, 238, 0.4);
    animation: logo-pulse 3s ease-in-out infinite;
    transition: all 0.3s ease;
}

.logo:hover .logo-icon {
    box-shadow: 0 12px 32px rgba(91, 141, 238, 0.45),
                0 0 0 8px rgba(91, 141, 238, 0.1);
    transform: rotate(-5deg) scale(1.05);
}

@keyframes logo-pulse {
    0%, 100% {
        box-shadow: 0 8px 24px rgba(91, 141, 238, 0.35),
                    0 0 0 0 rgba(91, 141, 238, 0.4);
    }
    50% {
        box-shadow: 0 12px 32px rgba(91, 141, 238, 0.45),
                    0 0 0 12px rgba(91, 141, 238, 0);
    }
}

.logo-icon svg {
    width: 30px;
    height: 30px;
    color: white;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
    transition: all 0.3s ease;
}

.logo:hover .logo-icon svg {
    transform: rotate(15deg) scale(1.1);
}

.logo-text {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.875rem;
    font-weight: 900;
    background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 50%, #8B7FE8 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -1.5px;
    background-size: 200% 100%;
    animation: text-shimmer 4s ease-in-out infinite;
    transition: all 0.3s ease;
}

.logo:hover .logo-text {
    letter-spacing: -0.5px;
}

@keyframes text-shimmer {
    0%, 100% {
        background-position: 200% 0;
    }
    50% {
        background-position: -200% 0;
    }
}

.nav-links {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    align-items: center;
}

.nav-links li {
    position: relative;
}

.nav-links li a {
    padding: 0.875rem 1.5rem;
    color: var(--text-medium);
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-weight: 600;
    font-size: 0.9375rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
}

.nav-links li a::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(91, 141, 238, 0.1), rgba(102, 217, 232, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
    border-radius: 12px;
}

.nav-links li a::after {
    content: '';
    position: absolute;
    bottom: 8px;
    left: 50%;
    transform: translateX(-50%) scaleX(0);
    width: 40%;
    height: 3px;
    background: linear-gradient(90deg, #5B8DEE, #66D9E8);
    border-radius: 10px;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-links li a:hover {
    color: var(--primary-blue);
    transform: translateY(-2px);
}

.nav-links li a:hover::before {
    opacity: 1;
}

.nav-links li a:hover::after {
    transform: translateX(-50%) scaleX(1);
}

.nav-buttons {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.btn {
    padding: 0.875rem 2rem;
    font-size: 0.9375rem;
    font-weight: 700;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.625rem;
    border: none;
    font-family: inherit;
    position: relative;
    overflow: hidden;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

.btn i {
    transition: transform 0.3s ease;
}

.btn:hover i {
    transform: translateX(4px);
}

.btn-login {
    background: transparent;
    color: var(--primary-blue);
    border: 2.5px solid var(--primary-blue);
    position: relative;
    z-index: 1;
}

.btn-login::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(91, 141, 238, 0.1), rgba(102, 217, 232, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
    border-radius: 12px;
}

.btn-login:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(91, 141, 238, 0.25);
    border-color: #66D9E8;
}

.btn-login:hover::after {
    opacity: 1;
}

.btn-signup {
    background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 100%);
    color: white;
    box-shadow: 0 8px 24px rgba(91, 141, 238, 0.35);
    border: none;
    position: relative;
    z-index: 1;
}

.btn-signup::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #66D9E8 0%, #8B7FE8 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: -1;
    border-radius: 12px;
}

.btn-signup:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 36px rgba(91, 141, 238, 0.45);
}

.btn-signup:hover::after {
    opacity: 1;
}

.btn-signup:active {
    transform: translateY(-1px) scale(0.98);
}

/* Mobile menu button (hidden by default) */
.mobile-menu-btn {
    display: none;
    background: transparent;
    border: none;
    color: var(--text-medium);
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.mobile-menu-btn:hover {
    background: rgba(91, 141, 238, 0.1);
    color: var(--primary-blue);
}

        .btn {
            padding: 0.75rem 1.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            font-family: inherit;
        }

        .btn-login {
            background: transparent;
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-login:hover {
            background: rgba(91, 141, 238, 0.1);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 141, 238, 0.2);
        }

        .btn-signup {
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            color: white;
            box-shadow: 0 8px 24px rgba(91, 141, 238, 0.3);
        }

        .btn-signup:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(91, 141, 238, 0.4);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            min-height: 100vh;
            padding: 10rem 3rem 6rem;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-content {
            animation: slide-up 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
            max-width: 900px;
            margin: 0 auto;
        }

        @keyframes slide-up {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.1), rgba(102, 217, 232, 0.1));
            padding: 1rem 1.5rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--primary-blue);
            margin-bottom: 2.5rem;
            border: 1.5px solid rgba(91, 141, 238, 0.3);
            animation: badge-pulse 2.5s ease-in-out infinite;
            backdrop-filter: blur(10px);
        }

        @keyframes badge-pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(91, 141, 238, 0.4);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 0 12px rgba(91, 141, 238, 0);
                transform: scale(1.02);
            }
        }

        .hero-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 5rem;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 2.25rem;
            letter-spacing: -2px;
            color: var(--text-dark);
            animation: title-fade 1s ease-out 0.2s both;
        }

        @keyframes title-fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 35%, #8B7FE8 65%, #E898C7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: gradient-shift 4s ease infinite;
            background-size: 300% 100%;
        }

        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .hero-subtitle {
            font-size: 1.375rem;
            line-height: 1.8;
            color: var(--text-medium);
            margin-bottom: 3.5rem;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            animation: subtitle-fade 1s ease-out 0.3s both;
        }

        @keyframes subtitle-fade {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-subtitle .highlight {
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            background-size: 200% 200%;
            animation: gradient-flow 3s ease infinite;
            display: inline-block;
            position: relative;
        }

        .hero-subtitle .highlight::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #5B8DEE, #66D9E8);
            transform: scaleX(0);
            animation: underline-grow 2s ease-in-out infinite;
        }

        @keyframes gradient-flow {
            0%, 100% {
                background-position: 0% 50%;
                transform: translateY(0px);
            }
            50% {
                background-position: 100% 50%;
                transform: translateY(-2px);
            }
        }

        @keyframes underline-grow {
            0%, 100% {
                transform: scaleX(0);
                opacity: 0;
            }
            50% {
                transform: scaleX(1);
                opacity: 1;
            }
        }

        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
            animation: buttons-fade 1s ease-out 0.4s both;
            justify-content: center;
        }

        @keyframes buttons-fade {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hero {
            padding: 1.25rem 3.25rem;
            font-size: 1.0625rem;
            font-weight: 700;
            border-radius: 12px;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #5B8DEE, #8B7FE8);
            color: white;
            box-shadow: 0 12px 32px rgba(91, 141, 238, 0.3);
            animation: float-up 3s ease-in-out infinite;
        }

        @keyframes float-up {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        .btn-hero-primary:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(91, 141, 238, 0.4);
        }

        .btn-hero-secondary {
            background: rgba(91, 141, 238, 0.08);
            color: var(--primary-blue);
            border: 2px solid var(--primary-blue);
        }

        .btn-hero-secondary:hover {
            background: rgba(91, 141, 238, 0.15);
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(91, 141, 238, 0.2);
        }

        /* ===== FEATURES SECTION ===== */
        .features {
            padding: 8.5rem 3rem;
            background: linear-gradient(135deg, #F9FAFB 0%, #EEF2F7 100%);
        }

        .features-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 6rem;
            animation: header-fade 0.8s ease-out;
        }

        @keyframes header-fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .section-badge {
            display: inline-block;
            font-size: 0.875rem;
            font-weight: 800;
            color: var(--primary-blue);
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.1), rgba(102, 217, 232, 0.1));
            border-radius: 50px;
            border: 1px solid rgba(91, 141, 238, 0.2);
        }

        .section-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 3.75rem;
            font-weight: 900;
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .section-subtitle {
            font-size: 1.25rem;
            color: var(--text-medium);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3rem;
        }

        .feature-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFB 100%);
            border-radius: 20px;
            padding: 3.5rem 2.75rem;
            border: 1.5px solid rgba(91, 141, 238, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            animation: feature-enter 0.6s ease-out backwards;
        }

        .feature-card:nth-child(1) { animation-delay: 0s; }
        .feature-card:nth-child(2) { animation-delay: 0.15s; }
        .feature-card:nth-child(3) { animation-delay: 0.3s; }
        .feature-card:nth-child(4) { animation-delay: 0.45s; }
        .feature-card:nth-child(5) { animation-delay: 0.6s; }
        .feature-card:nth-child(6) { animation-delay: 0.75s; }
        .feature-card:nth-child(7) { animation-delay: 0.9s; }
        .feature-card:nth-child(8) { animation-delay: 1.05s; }
        .feature-card:nth-child(9) { animation-delay: 1.2s; }
        .feature-card:nth-child(10) { animation-delay: 1.35s; }
        .feature-card:nth-child(11) { animation-delay: 1.5s; }
        .feature-card:nth-child(12) { animation-delay: 1.65s; }

        @keyframes feature-enter {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #5B8DEE, #66D9E8, #8B7FE8);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 24px 48px rgba(91, 141, 238, 0.15);
            border-color: rgba(91, 141, 238, 0.3);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.25rem;
            margin-bottom: 2rem;
            box-shadow: 0 12px 28px rgba(91, 141, 238, 0.25);
            transition: all 0.4s ease;
        }

        .feature-card:nth-child(1) .feature-icon {
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
        }

        .feature-card:nth-child(2) .feature-icon {
            background: linear-gradient(135deg, #8B7FE8, #A79FE8);
        }

        .feature-card:nth-child(3) .feature-icon {
            background: linear-gradient(135deg, #E898C7, #F5B28B);
        }

        .feature-card:nth-child(4) .feature-icon {
            background: linear-gradient(135deg, #88E8D0, #7DD4A5);
        }

        .feature-card:nth-child(5) .feature-icon {
            background: linear-gradient(135deg, #F5C77D, #F5B28B);
        }

        .feature-card:nth-child(6) .feature-icon {
            background: linear-gradient(135deg, #A79FE8, #5B8DEE);
        }

        .feature-card:nth-child(7) .feature-icon {
            background: linear-gradient(135deg, #66D9E8, #88E8D0);
        }

        .feature-card:nth-child(8) .feature-icon {
            background: linear-gradient(135deg, #5B8DEE, #8B7FE8);
        }

        .feature-card:nth-child(9) .feature-icon {
            background: linear-gradient(135deg, #E898C7, #A79FE8);
        }

        .feature-card:nth-child(10) .feature-icon {
            background: linear-gradient(135deg, #88E8D0, #66D9E8);
        }

        .feature-card:nth-child(11) .feature-icon {
            background: linear-gradient(135deg, #F5B28B, #F5C77D);
        }

        .feature-card:nth-child(12) .feature-icon {
            background: linear-gradient(135deg, #5B8DEE, #A79FE8);
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(-8deg);
        }

        .feature-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.5625rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.25rem;
            letter-spacing: -0.5px;
        }

        .feature-desc {
            font-size: 1.0625rem;
            color: var(--text-medium);
            line-height: 1.8;
        }

        /* Feature list items with checkmarks */
        .feature-list {
            list-style: none;
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .feature-list li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            color: var(--text-medium);
            line-height: 1.6;
        }

        .feature-list li::before {
            content: '✓';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.15), rgba(102, 217, 232, 0.15));
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        /* ===== BENEFITS SECTION ===== */
        .benefits {
            padding: 8.5rem 3rem;
            background: linear-gradient(135deg, #F9FAFB 0%, #EEF2F7 100%);
        }

        .benefits-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 4rem;
        }

        .benefit-item {
            display: flex;
            gap: 2rem;
            animation: benefit-slide 0.8s ease-out backwards;
        }

        .benefit-item:nth-child(1) { animation-delay: 0s; }
        .benefit-item:nth-child(2) { animation-delay: 0.2s; }
        .benefit-item:nth-child(3) { animation-delay: 0.4s; }
        .benefit-item:nth-child(4) { animation-delay: 0.6s; }

        @keyframes benefit-slide {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .benefit-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            flex-shrink: 0;
            box-shadow: 0 8px 20px rgba(91, 141, 238, 0.2);
        }

        .benefit-content h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.375rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .benefit-content p {
            color: var(--text-medium);
            line-height: 1.8;
        }

        /* ===== COMPARISON SECTION ===== */
        .comparison {
            padding: 8.5rem 3rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F9FAFB 100%);
            border-top: 2px solid rgba(91, 141, 238, 0.1);
        }

        .comparison-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .comparison-table {
            width: 100%;
            background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFB 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 16px 40px rgba(91, 141, 238, 0.1);
            border: 1.5px solid rgba(91, 141, 238, 0.1);
            animation: table-fade 0.8s ease-out;
        }

        @keyframes table-fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .table-header {
            background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 100%);
            color: white;
            padding: 2rem;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
            font-weight: 800;
            font-size: 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .table-row {
            padding: 1.75rem 2rem;
            border-top: 1px solid rgba(91, 141, 238, 0.1);
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 1rem;
            align-items: center;
            transition: all 0.3s ease;
        }

        .table-row:hover {
            background: rgba(91, 141, 238, 0.05);
        }

        .table-feature {
            font-weight: 700;
            color: var(--text-dark);
        }

        .table-cell {
            text-align: center;
            color: var(--text-medium);
        }

        .table-cell.included {
            color: var(--success-green);
            font-weight: 700;
        }

        .table-cell.included::before {
            content: '✓ ';
        }

        .table-cell.excluded {
            color: var(--text-light);
        }

        /* ===== USE CASES SECTION ===== */
        .use-cases {
            padding: 8.5rem 3rem;
            background: linear-gradient(135deg, #F9FAFB 0%, #EEF2F7 100%);
        }

        .use-cases-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .use-cases-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.5rem;
        }

        .use-case-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFB 100%);
            border-radius: 16px;
            padding: 2.5rem;
            border: 1.5px solid rgba(91, 141, 238, 0.1);
            transition: all 0.3s ease;
            animation: usecase-float 0.8s ease-out backwards;
        }

        .use-case-card:nth-child(1) { animation-delay: 0s; }
        .use-case-card:nth-child(2) { animation-delay: 0.15s; }
        .use-case-card:nth-child(3) { animation-delay: 0.3s; }

        @keyframes usecase-float {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .use-case-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(91, 141, 238, 0.15);
            border-color: rgba(91, 141, 238, 0.3);
        }

        .usecase-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }

        .usecase-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .usecase-desc {
            font-size: 0.95rem;
            color: var(--text-medium);
            line-height: 1.6;
        }

        /* ===== WORKFLOW SECTION (NEW FEATURE) ===== */
        .workflow {
            padding: 8.5rem 3rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F9FAFB 100%);
            border-top: 2px solid rgba(91, 141, 238, 0.1);
        }

        .workflow-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .workflow-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            position: relative;
        }

        .workflow-step {
            background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFB 100%);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            border: 1.5px solid rgba(91, 141, 238, 0.1);
            text-align: center;
            position: relative;
            animation: step-appear 0.8s ease-out backwards;
        }

        .workflow-step:nth-child(1) { animation-delay: 0s; }
        .workflow-step:nth-child(2) { animation-delay: 0.2s; }
        .workflow-step:nth-child(3) { animation-delay: 0.4s; }
        .workflow-step:nth-child(4) { animation-delay: 0.6s; }

        @keyframes step-appear {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .workflow-step::after {
            content: '→';
            position: absolute;
            right: -1.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2rem;
            color: var(--primary-blue);
            opacity: 0.3;
        }

        .workflow-step:last-child::after {
            display: none;
        }

        .workflow-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.25rem;
            margin: 0 auto 1.5rem;
        }

        .workflow-step-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.75rem;
        }

        .workflow-step-desc {
            font-size: 0.9rem;
            color: var(--text-medium);
            line-height: 1.6;
        }

        /* ===== SECURITY SECTION (NEW FEATURE) ===== */
        .security {
            padding: 8.5rem 3rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F9FAFB 100%);
            border-top: 2px solid rgba(91, 141, 238, 0.1);
        }

        .security-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .security-features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
        }

        .security-item {
            background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFB 100%);
            border-radius: 16px;
            padding: 2.5rem;
            border: 1.5px solid rgba(91, 141, 238, 0.1);
            display: flex;
            gap: 1.5rem;
            animation: security-slide 0.8s ease-out backwards;
        }

        .security-item:nth-child(1) { animation-delay: 0s; }
        .security-item:nth-child(2) { animation-delay: 0.15s; }
        .security-item:nth-child(3) { animation-delay: 0.3s; }
        .security-item:nth-child(4) { animation-delay: 0.45s; }

        @keyframes security-slide {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .security-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.75rem;
            flex-shrink: 0;
        }

        .security-content h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.125rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .security-content p {
            font-size: 0.95rem;
            color: var(--text-medium);
            line-height: 1.6;
        }

        /* ===== CTA SECTION ===== */
        .cta {
            padding: 8.5rem 3rem;
            background: linear-gradient(135deg, #5B8DEE 0%, #66D9E8 50%, #8B7FE8 100%);
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(circle at 20% 30%, rgba(255, 255, 255, 0.15), transparent),
                radial-gradient(circle at 80% 70%, rgba(255, 255, 255, 0.08), transparent),
                radial-gradient(circle at 40% 50%, rgba(167, 159, 232, 0.1), transparent);
        }

        .cta-container {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            animation: cta-fade 0.8s ease-out;
        }

        @keyframes cta-fade {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .cta h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            margin-bottom: 1.75rem;
            letter-spacing: -1px;
        }

        .cta p {
            font-size: 1.375rem;
            margin-bottom: 3.5rem;
            opacity: 0.98;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .cta-button {
            padding: 1.375rem 3.5rem;
            background: white;
            color: var(--primary-blue);
            border: none;
            border-radius: 14px;
            font-size: 1.125rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .cta-button:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.2);
        }

        .cta-button-secondary {
            background: transparent;
            color: white;
            border: 2.5px solid white;
        }

        .cta-button-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* ===== FAQ SECTION ===== */
        .faq {
            padding: 8.5rem 3rem;
            background: linear-gradient(180deg, #FFFFFF 0%, #F9FAFB 100%);
            border-top: 2px solid rgba(91, 141, 238, 0.1);
        }

        .faq-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 5rem;
        }

        .faq-items {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .faq-item {
            background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFB 100%);
            border: 1.5px solid rgba(91, 141, 238, 0.1);
            border-radius: 16px;
            overflow: hidden;
            animation: faq-fade 0.6s ease-out backwards;
        }

        .faq-item:nth-child(1) { animation-delay: 0s; }
        .faq-item:nth-child(2) { animation-delay: 0.1s; }
        .faq-item:nth-child(3) { animation-delay: 0.2s; }
        .faq-item:nth-child(4) { animation-delay: 0.3s; }
        .faq-item:nth-child(5) { animation-delay: 0.4s; }
        .faq-item:nth-child(6) { animation-delay: 0.5s; }

        @keyframes faq-fade {
            from {
                opacity: 0;
                transform: translateY(15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .faq-question {
            padding: 1.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.02), rgba(102, 217, 232, 0.02));
        }

        .faq-question:hover {
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.05), rgba(102, 217, 232, 0.05));
        }

        .faq-q-text {
            font-size: 1.125rem;
            font-weight: 700;
            color: var(--text-dark);
            font-family: 'Plus Jakarta Sans', sans-serif;
            text-align: left;
        }

        .faq-toggle {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.1), rgba(102, 217, 232, 0.1));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-size: 1.5rem;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .faq-item.open .faq-toggle {
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            color: white;
            transform: rotate(180deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.open .faq-answer {
            max-height: 300px;
        }

        .faq-answer-text {
            padding: 0 1.75rem 1.75rem;
            color: var(--text-medium);
            line-height: 1.8;
            font-size: 1rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: linear-gradient(135deg, #2D3748 0%, #1A202C 100%);
            color: white;
            padding: 7rem 3rem 3rem;
            border-top: 1px solid rgba(91, 141, 238, 0.2);
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1.2fr 1.2fr 1.2fr;
            gap: 5rem;
            margin-bottom: 5rem;
        }

        .footer-about h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1.5rem;
            letter-spacing: -1px;
        }

        .footer-about p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, rgba(91, 141, 238, 0.2), rgba(102, 217, 232, 0.2));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #66D9E8;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 1px solid rgba(91, 141, 238, 0.3);
            font-size: 1.125rem;
        }

        .social-link:hover {
            background: linear-gradient(135deg, #5B8DEE, #66D9E8);
            color: white;
            transform: translateY(-4px);
        }

        .footer-links h4 {
            font-size: 1.0625rem;
            font-weight: 800;
            margin-bottom: 2rem;
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 1rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.65);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .footer-links a:hover {
            color: #66D9E8;
            padding-left: 0.5rem;
        }

        .footer-divider {
            border-top: 1px solid rgba(91, 141, 238, 0.2);
            padding-top: 3rem;
            margin-top: 4rem;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9375rem;
            color: rgba(255, 255, 255, 0.6);
        }

        .footer-legal {
            display: flex;
            gap: 2.5rem;
        }

        .footer-legal a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-legal a:hover {
            color: #66D9E8;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .footer-content {
                grid-template-columns: 1fr;
                gap: 4rem;
            }

            .hero-title {
                font-size: 4rem;
            }

            .features-grid,
            .use-cases-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .workflow-steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .benefits-grid,
            .security-features {
                grid-template-columns: 1fr;
            }

            .table-header,
            .table-row {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 3rem;
            }

            .cta h2 {
                font-size: 2.75rem;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0 1.5rem;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 8rem 1.5rem 4rem;
            }

            .hero-title {
                font-size: 2.75rem;
                margin-bottom: 1.5rem;
            }

            .hero-subtitle {
                font-size: 1.125rem;
                margin-bottom: 2rem;
            }

            .hero-buttons,
            .cta-buttons {
                flex-direction: column;
                gap: 1rem;
            }

            .features-grid,
            .use-cases-grid,
            .benefits-grid,
            .workflow-steps,
            .security-features {
                grid-template-columns: 1fr;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta h2 {
                font-size: 2rem;
            }

            .cta p {
                font-size: 1.1rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .footer-legal {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .footer {
                padding: 4rem 1.5rem 2rem;
            }

            .features,
            .benefits,
            .comparison,
            .use-cases,
            .workflow,
            .security,
            .cta,
            .faq {
                padding: 5rem 1.5rem;
            }

            .workflow-step::after {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .hero-title {
                font-size: 1.875rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.75rem;
            }

            .btn-hero {
                padding: 1rem 2rem;
                font-size: 1rem;
            }

            .cta h2 {
                font-size: 1.75rem;
            }

            .footer {
                padding: 2rem 1rem;
            }
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: linear-gradient(180deg, #F9FAFB, #EEF2F7);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #5B8DEE, #66D9E8);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #4A7CD9, #55C8D7);
        }
    </style>
</head>
<body>
    <!-- Background Elements -->
    <div class="bg-elements">
        <div class="blob-1"></div>
        <div class="blob-2"></div>
        <div class="blob-3"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <span class="logo-text">TIMLY</span>
            </a>
            <ul class="nav-links">
                <li><a href="#features">Features</a></li>
                <li><a href="#benefits">Benefits</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
            <div class="nav-buttons">
                <a href="{{ route('login') }}" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i>Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-signup">
                    Get Started <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    Trusted by 10,000+ teams worldwide
                </div>
                <h1 class="hero-title">
                    Manage Projects<br>
                    <span class="gradient-text">Smarter & Faster</span>
                </h1>

                <p class="hero-subtitle">
                    Transform chaos into clarity. Empower your team to <span class="highlight">collaborate seamlessly</span>, deliver projects <span class="highlight">3x faster</span>, and turn ambitious goals into <span class="highlight">remarkable achievements</span>.
                </p>

                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-hero btn-hero-primary">
                        Start Free Trial <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#features" class="btn btn-hero btn-hero-secondary">
                        <i class="fas fa-play-circle"></i> Learn More
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="section-header">
                <div class="section-badge">FEATURES</div>
                <h2 class="section-title">Everything You Need</h2>
                <p class="section-subtitle">
                    Powerful tools designed to help your team collaborate better and achieve more together
                </p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-columns"></i>
                    </div>
                    <h3 class="feature-title">Kanban Boards</h3>
                    <p class="feature-desc">
                        Visualize your workflow with intuitive drag-and-drop boards
                    </p>
                    <ul class="feature-list">
                        <li>Drag & drop interface</li>
                        <li>Custom columns & workflows</li>
                        <li>Card templates</li>
                        <li>Quick filters & search</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="feature-title">Team Collaboration</h3>
                    <p class="feature-desc">
                        Work together seamlessly with real-time updates
                    </p>
                    <ul class="feature-list">
                        <li>Real-time sync</li>
                        <li>Comments & mentions</li>
                        <li>File attachments</li>
                        <li>Activity tracking</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Advanced Analytics</h3>
                    <p class="feature-desc">
                        Track progress with comprehensive dashboards
                    </p>
                    <ul class="feature-list">
                        <li>Custom reports</li>
                        <li>Performance metrics</li>
                        <li>Time tracking</li>
                        <li>Export to Excel/PDF</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Smart Automation</h3>
                    <p class="feature-desc">
                        Automate repetitive tasks and workflows
                    </p>
                    <ul class="feature-list">
                        <li>Custom automation rules</li>
                        <li>Trigger-based actions</li>
                        <li>Scheduled tasks</li>
                        <li>Workflow templates</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="feature-title">Enterprise Security</h3>
                    <p class="feature-desc">
                        Bank-level security with end-to-end encryption
                    </p>
                    <ul class="feature-list">
                        <li>2FA authentication</li>
                        <li>Role-based permissions</li>
                        <li>Data encryption</li>
                        <li>Compliance certified</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Mobile Apps</h3>
                    <p class="feature-desc">
                        Access your projects anywhere with native apps
                    </p>
                    <ul class="feature-list">
                        <li>iOS & Android apps</li>
                        <li>Offline mode</li>
                        <li>Push notifications</li>
                        <li>Cross-device sync</li>
                    </ul>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Time Tracking</h3>
                    <p class="feature-desc">
                        Monitor time spent on tasks and projects with built-in timers, detailed logs, and productivity insights
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="feature-title">Team Chat</h3>
                    <p class="feature-desc">
                        Communicate instantly with built-in messaging, threaded conversations, and @mentions for seamless collaboration
                    </p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="feature-title">Document Management</h3>
                    <p class="feature-desc">
                        Organize, share, and version control all project files in one secure centralized location with easy access
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="benefits" id="benefits">
        <div class="benefits-container">
            <div class="section-header">
                <div class="section-badge">BENEFITS</div>
                <h2 class="section-title">Why Teams Choose TIMLY</h2>
                <p class="section-subtitle">
                    Experience the difference that modern project management can make
                </p>
            </div>

            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Increase Productivity</h3>
                        <p>Get more done with less effort. Automate repetitive tasks and focus on what matters most to your team.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Better Collaboration</h3>
                        <p>Keep your team aligned and communicate effectively with real-time updates and seamless collaboration tools.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Full Visibility</h3>
                        <p>Gain complete visibility into all your projects with comprehensive dashboards and detailed reporting.</p>
                    </div>
                </div>

                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div class="benefit-content">
                        <h3>Data-Driven Decisions</h3>
                        <p>Make informed decisions with advanced analytics and insights powered by your project data.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section class="workflow">
        <div class="workflow-container">
            <div class="section-header">
                <div class="section-badge">WORKFLOW</div>
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">
                    Get started in minutes with our simple four-step process
                </p>
            </div>

            <div class="workflow-steps">
                <div class="workflow-step">
                    <div class="workflow-number">1</div>
                    <div class="workflow-step-title">Sign Up</div>
                    <div class="workflow-step-desc">Create your free account in seconds</div>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">2</div>
                    <div class="workflow-step-title">Create Project</div>
                    <div class="workflow-step-desc">Set up your first project and boards</div>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">3</div>
                    <div class="workflow-step-title">Invite Team</div>
                    <div class="workflow-step-desc">Add team members and assign roles</div>
                </div>

                <div class="workflow-step">
                    <div class="workflow-number">4</div>
                    <div class="workflow-step-title">Start Working</div>
                    <div class="workflow-step-desc">Begin collaborating and tracking progress</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Section -->
    <section class="comparison">
        <div class="comparison-container">
            <div class="section-header">
                <div class="section-badge">COMPARISON</div>
                <h2 class="section-title">TIMLY vs Others</h2>
                <p class="section-subtitle">
                    See how TIMLY compares to other project management tools
                </p>
            </div>

            <div class="comparison-table">
                <div class="table-header">
                    <div>Features</div>
                    <div>TIMLY</div>
                    <div>Competitor A</div>
                    <div>Competitor B</div>
                </div>
                <div class="table-row">
                    <div class="table-feature">Kanban Boards</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell included">Yes</div>
                </div>
                <div class="table-row">
                    <div class="table-feature">Real-Time Collaboration</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell excluded">No</div>
                </div>
                <div class="table-row">
                    <div class="table-feature">Advanced Analytics</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell excluded">No</div>
                    <div class="table-cell included">Yes</div>
                </div>
                <div class="table-row">
                    <div class="table-feature">Mobile Apps</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell excluded">No</div>
                </div>
                <div class="table-row">
                    <div class="table-feature">Automation Rules</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell excluded">No</div>
                    <div class="table-cell included">Yes</div>
                </div>
                <div class="table-row">
                    <div class="table-feature">24/7 Support</div>
                    <div class="table-cell included">Yes</div>
                    <div class="table-cell excluded">No</div>
                    <div class="table-cell excluded">No</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Use Cases Section -->
    <section class="use-cases">
        <div class="use-cases-container">
            <div class="section-header">
                <div class="section-badge">USE CASES</div>
                <h2 class="section-title">Perfect For Every Team</h2>
                <p class="section-subtitle">
                    TIMLY works for teams across different industries and project types
                </p>
            </div>

            <div class="use-cases-grid">
                <div class="use-case-card">
                    <div class="usecase-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <div class="usecase-title">Software Development</div>
                    <div class="usecase-desc">
                        Manage sprints, track bugs, and coordinate development workflows with precision and ease
                    </div>
                </div>

                <div class="use-case-card">
                    <div class="usecase-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <div class="usecase-title">Creative Agencies</div>
                    <div class="usecase-desc">
                        Organize design projects, manage assets, and collaborate with clients seamlessly
                    </div>
                </div>

                <div class="use-case-card">
                    <div class="usecase-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="usecase-title">Enterprise Teams</div>
                    <div class="usecase-desc">
                        Scale project management across departments with advanced security and compliance features
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Section -->
    <section class="security">
        <div class="security-container">
            <div class="section-header">
                <div class="section-badge">SECURITY</div>
                <h2 class="section-title">Enterprise-Grade Security</h2>
                <p class="section-subtitle">
                    Your data security and privacy are our top priorities
                </p>
            </div>

            <div class="security-features">
                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="security-content">
                        <h3>End-to-End Encryption</h3>
                        <p>All data is encrypted using AES-256 encryption both in transit and at rest, ensuring maximum security.</p>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="security-content">
                        <h3>SOC 2 Compliance</h3>
                        <p>We maintain SOC 2 Type II certification and comply with international security standards.</p>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="security-content">
                        <h3>Two-Factor Authentication</h3>
                        <p>Add an extra layer of security with 2FA support via SMS, authenticator apps, and hardware keys.</p>
                    </div>
                </div>

                <div class="security-item">
                    <div class="security-icon">
                        <i class="fas fa-database"></i>
                    </div>
                    <div class="security-content">
                        <h3>Automatic Backups</h3>
                        <p>Daily automated backups ensure your data is always safe and recoverable in case of any issues.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Ready to Transform Your Project Management?</h2>
            <p>Join thousands of teams already using TIMLY to collaborate better and achieve more</p>
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="cta-button">
                    <i class="fas fa-rocket"></i>
                    Start Free Trial
                </a>
                <a href="{{ route('login') }}" class="cta-button cta-button-secondary">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </a>
            </div>
            <p style="margin-top: 2rem; font-size: 1rem; opacity: 0.9;">
                <i class="fas fa-check-circle"></i> No credit card required • Free forever plan • Cancel anytime
            </p>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq" id="faq">
        <div class="faq-container">
            <div class="faq-header">
                <div class="section-header">
                    <div class="section-badge">FAQ</div>
                    <h2 class="section-title">Frequently Asked Questions</h2>
                    <p class="section-subtitle">
                        Find answers to common questions about TIMLY and how it can help your team
                    </p>
                </div>
            </div>

            <div class="faq-items">
                <div class="faq-item" data-faq>
                    <div class="faq-question">
                        <div class="faq-q-text">How do I get started with TIMLY?</div>
                        <div class="faq-toggle"><i class="fas fa-chevron-down"></i></div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-text">
                            Getting started with TIMLY is easy! Simply sign up for a free account, create your first project, add team members, and start managing your tasks. You can be up and running in minutes. We also provide comprehensive onboarding tutorials and support to help you get the most out of TIMLY.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-faq>
                    <div class="faq-question">
                        <div class="faq-q-text">Can I import my existing projects into TIMLY?</div>
                        <div class="faq-toggle"><i class="fas fa-chevron-down"></i></div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-text">
                            Yes! TIMLY supports importing projects from many popular project management tools. We have built-in importers for Asana, Monday.com, Trello, and many others. You can also manually import data via CSV files. Our support team can help you with the migration process.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-faq>
                    <div class="faq-question">
                        <div class="faq-q-text">Is TIMLY secure? How is my data protected?</div>
                        <div class="faq-toggle"><i class="fas fa-chevron-down"></i></div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-text">
                            Security is our top priority. TIMLY uses bank-level AES 256-bit encryption for all data in transit and at rest. We perform daily automatic backups, maintain SOC 2 compliance, and conduct regular security audits. Your data is stored on secure servers in multiple geographic locations.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-faq>
                    <div class="faq-question">
                        <div class="faq-q-text">Can I customize TIMLY for my team's needs?</div>
                        <div class="faq-toggle"><i class="fas fa-chevron-down"></i></div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-text">
                            Absolutely! TIMLY offers extensive customization options. You can customize workflows, create custom fields, set up automated rules, configure templates, and much more. For enterprise clients, we also provide API access and advanced customization options.
                        </div>
                    </div>
                </div>

                <div class="faq-item" data-faq>
                    <div class="faq-question">
                        <div class="faq-q-text">What kind of support does TIMLY offer?</div>
                        <div class="faq-toggle"><i class="fas fa-chevron-down"></i></div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-text">
                            We offer comprehensive support including email, live chat, documentation, video tutorials, and a knowledge base. Premium plans include priority support with faster response times. We also have an active community forum where users share tips and best practices.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-about">
                    <h3>TIMLY</h3>
                    <p>
                        Modern project management platform built for teams that want to move faster and achieve more together. Trusted by 10,000+ companies worldwide to deliver exceptional results and streamline their project workflows effectively.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link" title="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link" title="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-link" title="GitHub"><i class="fab fa-github"></i></a>
                    </div>
                </div>

                <div class="footer-links">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#">Security</a></li>
                        <li><a href="#">API Documentation</a></li>
                        <li><a href="#">Roadmap</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Press</a></li>
                    </ul>
                </div>

                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Community Forum</a></li>
                        <li><a href="#">Guides & Tutorials</a></li>
                        <li><a href="#">Status Page</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-divider">
                <div class="footer-bottom">
                    <span>© 2025 TIMLY. All rights reserved. Made with ❤️ for productive teams.</span>
                    <div class="footer-legal">
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            });
        });

        // FAQ Toggle
        document.querySelectorAll('[data-faq]').forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                document.querySelectorAll('[data-faq]').forEach(otherItem => {
                    if (otherItem !== item) {
                        otherItem.classList.remove('open');
                    }
                });
                item.classList.toggle('open');
            });
        });
    </script>
</body>
</html>
