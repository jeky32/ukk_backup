<!-- resources/views/admin/dashboard.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js - VERSI SPESIFIK -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <style>
        /* Gradient animations */
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 15s ease infinite;
        }

        /* Fade in animations */
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-left {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scale-in {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out backwards;
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.6s ease-out backwards;
        }

        .animate-fade-in-left {
            animation: fade-in-left 0.6s ease-out backwards;
        }

        .animate-scale-in {
            animation: scale-in 0.5s ease-out backwards;
        }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        /* Card hover lift */
        .card-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-lift:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Progress bar animation */
        @keyframes progress-load {
            0% { width: 0%; }
        }

        .progress-bar {
            animation: progress-load 1.5s ease-out;
        }

        /* Bounce subtle */
        @keyframes bounce-subtle {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }

        .animate-bounce-subtle {
            animation: bounce-subtle 2s ease-in-out infinite;
        }

		.task-card {
			animation: fadeIn 0.5s ease;
		}

		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(10px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.progress-bar {
			transition: width 1s ease;
		}

		.calendar-day {
			width: 40px;
			height: 40px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 12px;
			cursor: pointer;
			transition: all 0.2s ease;
			font-size: 14px;
			font-weight: 500;
		}

		.calendar-day:not(.opacity-30):hover {
			background: #F3F4F6;
		}

		.calendar-day.active {
			background: #10B981;
			color: white;
			font-weight: 600;
		}

		.calendar-day.today {
			background: #1E293B;
			color: white;
			font-weight: 600;
		}

        /* ========== ANIMASI TAMBAHAN ========== */

        /* Counter Animation untuk Statistik */
        @keyframes count-up {
            from {
                opacity: 0;
                transform: translateY(10px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-count-up {
            animation: count-up 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Skeleton Loading Animation */
        @keyframes skeleton-loading {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: skeleton-loading 1.5s infinite;
            border-radius: 8px;
        }

        /* Smooth Scroll Reveal Animation */
        @keyframes reveal-from-bottom {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal-on-scroll {
            animation: reveal-from-bottom 0.8s ease-out;
        }

        /* Button Microinteraction */
        @keyframes button-press {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(0.95); }
        }

        .btn-press:active {
            animation: button-press 0.2s ease;
        }

        /* Checkbox Toggle Animation */
        @keyframes checkbox-bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.2); }
        }

        .checkbox-animate:checked {
            animation: checkbox-bounce 0.3s ease;
        }

        /* Stagger Animation untuk Timeline */
        @keyframes stagger-appear {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .timeline-item {
            animation: stagger-appear 0.6s ease-out backwards;
        }

        .timeline-item:nth-child(1) { animation-delay: 0.1s; }
        .timeline-item:nth-child(2) { animation-delay: 0.2s; }
        .timeline-item:nth-child(3) { animation-delay: 0.3s; }
        .timeline-item:nth-child(4) { animation-delay: 0.4s; }
        .timeline-item:nth-child(5) { animation-delay: 0.5s; }
        .timeline-item:nth-child(6) { animation-delay: 0.6s; }
        .timeline-item:nth-child(7) { animation-delay: 0.7s; }

        /* Timeline Bar Hover Effect */
        .timeline-bar {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .timeline-bar:hover {
            transform: scaleY(1.1) scaleX(1.05);
            filter: brightness(1.1);
        }

        /* Progress Circle SVG Animation */
        @keyframes progress-circle {
            from {
                stroke-dashoffset: 339;
            }
        }

        .progress-circle-svg {
            stroke-dasharray: 339;
            stroke-dashoffset: 339;
            animation: progress-circle 2s ease-out forwards;
        }

        /* Modal Backdrop Blur */
        @keyframes backdrop-blur-in {
            from {
                backdrop-filter: blur(0px);
                background-color: rgba(0, 0, 0, 0);
            }
            to {
                backdrop-filter: blur(8px);
                background-color: rgba(0, 0, 0, 0.5);
            }
        }

        .modal-backdrop {
            animation: backdrop-blur-in 0.3s ease-out;
        }

        /* Notification Pulse Enhanced */
        @keyframes notification-pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .notification-badge {
            animation: notification-pulse 2s ease-in-out infinite;
        }

        /* Shake Animation untuk Notifikasi Baru */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }

        /* Card Entrance Stagger */
        .card-stagger {
            animation: fade-in-up 0.6s ease-out backwards;
        }

        .card-stagger:nth-child(1) { animation-delay: 0.1s; }
        .card-stagger:nth-child(2) { animation-delay: 0.2s; }
        .card-stagger:nth-child(3) { animation-delay: 0.3s; }
        .card-stagger:nth-child(4) { animation-delay: 0.4s; }

        /* Smooth Bounce untuk Icons */
        @keyframes icon-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .icon-bounce:hover {
            animation: icon-bounce 0.6s ease-in-out;
        }

        /* Ripple Effect */
        @keyframes ripple {
            0% {
                transform: scale(0);
                opacity: 0.8;
            }
            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        .ripple-effect {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            pointer-events: none;
        }

        .ripple-effect:active::after {
            animation: ripple 0.6s ease-out;
        }

        /* Toast Notification Slide In */
        @keyframes toast-slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast-notification {
            animation: toast-slide-in 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Loading Spinner */
        @keyframes spin-smooth {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .loading-spinner {
            animation: spin-smooth 1s linear infinite;
        }

        /* Glow Effect untuk Active Elements */
        @keyframes glow-pulse {
            0%, 100% {
                box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
            }
            50% {
                box-shadow: 0 0 20px rgba(99, 102, 241, 0.8);
            }
        }

        .glow-active {
            animation: glow-pulse 2s ease-in-out infinite;
        }

        /* ========== END ANIMASI TAMBAHAN ========== */

        /* ========== ADVANCED ANIMATIONS ========== */

        /* Particle Background Animation */
        @keyframes particle-float {
            0%, 100% {
                transform: translate(0, 0) rotate(0deg);
                opacity: 0.5;
            }
            25% {
                transform: translate(10px, -10px) rotate(90deg);
                opacity: 0.8;
            }
            50% {
                transform: translate(-5px, -20px) rotate(180deg);
                opacity: 1;
            }
            75% {
                transform: translate(-15px, -10px) rotate(270deg);
                opacity: 0.8;
            }
        }

        .particle {
            position: absolute;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            animation: particle-float 20s infinite ease-in-out;
        }

        /* Neon Glow Effect */
        @keyframes neon-glow {
            0%, 100% {
                text-shadow: 0 0 10px rgba(99, 102, 241, 0.5),
                            0 0 20px rgba(99, 102, 241, 0.3),
                            0 0 30px rgba(99, 102, 241, 0.2);
            }
            50% {
                text-shadow: 0 0 20px rgba(99, 102, 241, 0.8),
                            0 0 30px rgba(99, 102, 241, 0.6),
                            0 0 40px rgba(99, 102, 241, 0.4),
                            0 0 50px rgba(99, 102, 241, 0.2);
            }
        }

        .neon-text {
            animation: neon-glow 2s ease-in-out infinite;
        }

        /* Morphing Shape Animation */
        @keyframes morph {
            0%, 100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
            }
            25% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
            }
            50% {
                border-radius: 50% 50% 30% 60% / 30% 60% 70% 40%;
            }
            75% {
                border-radius: 60% 30% 50% 50% / 60% 40% 50% 50%;
            }
        }

        .morphing-shape {
            animation: morph 8s ease-in-out infinite;
        }

        /* 3D Card Flip */
        .flip-card {
            perspective: 1000px;
        }

        .flip-card-inner {
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            backface-visibility: hidden;
        }

        .flip-card-back {
            transform: rotateY(180deg);
        }

        /* Parallax Scroll Effect */
        @keyframes parallax {
            to {
                transform: translateY(var(--parallax-speed, -50px));
            }
        }

        .parallax-element {
            animation: parallax 3s cubic-bezier(0.22, 1, 0.36, 1) infinite alternate;
        }

        /* Rainbow Border Animation */
        @keyframes rainbow-border {
            0% { border-color: #ff0080; }
            20% { border-color: #ff8c00; }
            40% { border-color: #40e0d0; }
            60% { border-color: #4169e1; }
            80% { border-color: #da70d6; }
            100% { border-color: #ff0080; }
        }

        .rainbow-border {
            animation: rainbow-border 3s linear infinite;
        }

        /* Typewriter Effect */
        @keyframes typewriter {
            from { width: 0; }
            to { width: 100%; }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent; }
            50% { border-color: #667eea; }
        }

        .typewriter {
            overflow: hidden;
            border-right: 3px solid #667eea;
            white-space: nowrap;
            animation:
                typewriter 4s steps(40) 1s 1 normal both,
                blink-caret 0.75s step-end infinite;
        }

        /* Breathing Animation */
        @keyframes breathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .breathe {
            animation: breathe 3s ease-in-out infinite;
        }

        /* Glitch Effect */
        @keyframes glitch {
            0% {
                transform: translate(0);
            }
            20% {
                transform: translate(-2px, 2px);
            }
            40% {
                transform: translate(-2px, -2px);
            }
            60% {
                transform: translate(2px, 2px);
            }
            80% {
                transform: translate(2px, -2px);
            }
            100% {
                transform: translate(0);
            }
        }

        .glitch:hover {
            animation: glitch 0.3s infinite;
        }

        /* Slide Up Reveal */
        @keyframes slide-up-reveal {
            from {
                opacity: 0;
                transform: translateY(30px);
                clip-path: inset(0 0 100% 0);
            }
            to {
                opacity: 1;
                transform: translateY(0);
                clip-path: inset(0 0 0 0);
            }
        }

        .slide-up-reveal {
            animation: slide-up-reveal 0.8s cubic-bezier(0.65, 0, 0.35, 1);
        }

        /* Magnetic Button Effect (with JS) */
        .magnetic-button {
            transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        /* Progress Ring Animation */
        @keyframes progress-ring {
            from {
                stroke-dashoffset: 100;
            }
        }

        .progress-ring {
            animation: progress-ring 2s ease-out forwards;
        }

        /* Confetti Animation */
        @keyframes confetti-fall {
            0% {
                transform: translateY(-100vh) rotate(0deg);
                opacity: 1;
            }
            100% {
                transform: translateY(100vh) rotate(720deg);
                opacity: 0;
            }
        }

        .confetti {
            animation: confetti-fall 3s linear;
        }

        /* Shimmer Effect */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }

        .shimmer {
            background: linear-gradient(
                90deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.2) 20%,
                rgba(255, 255, 255, 0.5) 60%,
                rgba(255, 255, 255, 0)
            );
            background-size: 1000px 100%;
            animation: shimmer 2s infinite;
        }

        /* Liquid Fill Animation */
        @keyframes liquid-fill {
            0% {
                height: 0%;
            }
        }

        .liquid-fill {
            animation: liquid-fill 2s ease-out forwards;
        }

        /* Wave Animation */
        @keyframes wave {
            0% {
                transform: translateX(0) translateZ(0) scaleY(1);
            }
            50% {
                transform: translateX(-25%) translateZ(0) scaleY(0.55);
            }
            100% {
                transform: translateX(-50%) translateZ(0) scaleY(1);
            }
        }

        .wave {
            animation: wave 10s cubic-bezier(0.36, 0.45, 0.63, 0.53) infinite;
        }

        /* Bubble Float */
        @keyframes bubble-float {
            0% {
                transform: translateY(0) scale(1);
                opacity: 0.8;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) scale(1.2);
                opacity: 0;
            }
        }

        .bubble {
            animation: bubble-float 6s ease-in infinite;
        }

        /* ========== END ADVANCED ANIMATIONS ========== */

        /* ========== EMOJI BUTTON ANIMATIONS ========== */

        /* Emoji Bounce */
        @keyframes emoji-bounce {
            0%, 100% {
                transform: translateY(0) scale(1);
            }
            25% {
                transform: translateY(-20px) scale(1.1);
            }
            50% {
                transform: translateY(-10px) scale(1.05) rotate(5deg);
            }
            75% {
                transform: translateY(-15px) scale(1.08) rotate(-5deg);
            }
        }

        .emoji-button {
            position: relative;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .emoji-button:hover {
            animation: emoji-bounce 0.6s ease-in-out;
            transform: scale(1.2);
        }

        /* Emoji Reaction Burst */
        @keyframes emoji-burst {
            0% {
                transform: scale(0) rotate(0deg);
                opacity: 1;
            }
            50% {
                transform: scale(1.5) rotate(180deg);
                opacity: 0.8;
            }
            100% {
                transform: scale(2.5) rotate(360deg);
                opacity: 0;
            }
        }

        .emoji-burst {
            animation: emoji-burst 0.6s ease-out forwards;
        }

        /* Emoji Floating Hearts */
        @keyframes float-heart {
            0% {
                transform: translateY(0) scale(1);
                opacity: 1;
            }
            50% {
                transform: translateY(-30px) scale(1.2);
                opacity: 0.8;
            }
            100% {
                transform: translateY(-60px) scale(0.5);
                opacity: 0;
            }
        }

        .float-heart {
            animation: float-heart 1.5s ease-out forwards;
        }

        /* Emoji Sparkle Effect */
        @keyframes sparkle {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(255, 215, 0, 0);
            }
            50% {
                box-shadow: 0 0 20px 10px rgba(255, 215, 0, 0.6),
                           0 0 40px 20px rgba(255, 215, 0, 0.4),
                           0 0 60px 30px rgba(255, 215, 0, 0.2);
            }
        }

        .emoji-sparkle {
            animation: sparkle 1s ease-in-out;
        }

        /* Emoji Reaction Bar */
        .emoji-reaction-bar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid transparent;
            background-clip: padding-box;
        }

        .emoji-reaction-bar::before {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: inherit;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #4facfe);
            background-size: 300% 300%;
            animation: gradient-shift 3s ease infinite;
            z-index: -1;
        }

        /* Floating Action Emoji */
        @keyframes rotate-emoji {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .rotating-emoji:hover {
            animation: rotate-emoji 1s linear infinite;
        }

        /* Emoji Pop */
        @keyframes emoji-pop {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .emoji-pop {
            animation: emoji-pop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        /* Emoji Trail Effect */
        @keyframes emoji-trail {
            0% {
                transform: translateX(0) scale(1);
                opacity: 1;
            }
            100% {
                transform: translateX(100px) scale(0.5);
                opacity: 0;
            }
        }

        .emoji-trail {
            animation: emoji-trail 1s ease-out forwards;
        }

        /* ========== ADVANCED FEATURE ANIMATIONS ========== */

        /* Glassmorphism Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        /* Neon Pulse Border */
        @keyframes neon-pulse-border {
            0%, 100% {
                box-shadow: 0 0 5px #667eea,
                           0 0 10px #667eea,
                           0 0 20px #667eea;
            }
            50% {
                box-shadow: 0 0 10px #764ba2,
                           0 0 20px #764ba2,
                           0 0 40px #764ba2,
                           0 0 80px #764ba2;
            }
        }

        .neon-pulse-border {
            animation: neon-pulse-border 2s ease-in-out infinite;
        }

        /* Holographic Effect */
        @keyframes holographic {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .holographic {
            background: linear-gradient(
                124deg,
                #ff2400, #e81d1d, #e8b71d, #e3e81d,
                #1de840, #1ddde8, #2b1de8, #dd00f3, #dd00f3
            );
            background-size: 1800% 1800%;
            animation: holographic 10s ease infinite;
        }

        /* Typing Indicator */
        @keyframes typing {
            0%, 60%, 100% { opacity: 0.2; }
            30% { opacity: 1; }
        }

        .typing-indicator span {
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* 3D Perspective Card */
        .card-3d {
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .card-3d:hover {
            transform: perspective(1000px) rotateY(10deg) rotateX(5deg);
        }

        /* Star Rating Animation */
        @keyframes star-glow {
            0%, 100% {
                filter: drop-shadow(0 0 0 gold);
            }
            50% {
                filter: drop-shadow(0 0 10px gold) drop-shadow(0 0 20px orange);
            }
        }

        .star-rating:hover {
            animation: star-glow 0.6s ease-in-out;
        }

        /* Pulse Ring */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 20px rgba(99, 102, 241, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0);
            }
        }

        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        /* Text Gradient Animation */
        @keyframes text-gradient {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .text-gradient-animate {
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #667eea);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: text-gradient 3s ease infinite;
        }

        /* Bouncing Loader */
        @keyframes bounce-loader {
            0%, 80%, 100% {
                transform: scale(0);
            }
            40% {
                transform: scale(1);
            }
        }

        .bounce-loader div {
            animation: bounce-loader 1.4s infinite ease-in-out both;
        }

        .bounce-loader div:nth-child(1) {
            animation-delay: -0.32s;
        }

        .bounce-loader div:nth-child(2) {
            animation-delay: -0.16s;
        }

        /* Matrix Rain Effect */
        @keyframes matrix-rain {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(100vh);
                opacity: 0;
            }
        }

        .matrix-rain {
            animation: matrix-rain 3s linear infinite;
        }

        /* Gradient Border Animation */
        @keyframes gradient-border-rotate {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .gradient-border {
            border: 3px solid;
            border-image-slice: 1;
            border-image-source: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #667eea);
            animation: gradient-border-rotate 3s ease infinite;
        }

        /* Wobble Animation */
        @keyframes wobble {
            0%, 100% { transform: translateX(0); }
            15% { transform: translateX(-10px) rotate(-5deg); }
            30% { transform: translateX(5px) rotate(3deg); }
            45% { transform: translateX(-5px) rotate(-3deg); }
            60% { transform: translateX(3px) rotate(2deg); }
            75% { transform: translateX(-2px) rotate(-1deg); }
        }

        .wobble:hover {
            animation: wobble 0.8s ease-in-out;
        }

        /* Flip Animation */
        @keyframes flip {
            0% {
                transform: perspective(400px) rotateY(0);
            }
            100% {
                transform: perspective(400px) rotateY(360deg);
            }
        }

        .flip-animation:hover {
            animation: flip 0.6s ease-in-out;
        }

        /* Jello Animation */
        @keyframes jello {
            0%, 100% { transform: skewX(0deg) skewY(0deg); }
            30% { transform: skewX(25deg) skewY(25deg); }
            40% { transform: skewX(-15deg) skewY(-15deg); }
            50% { transform: skewX(15deg) skewY(15deg); }
            65% { transform: skewX(-5deg) skewY(-5deg); }
            75% { transform: skewX(5deg) skewY(5deg); }
        }

        .jello:hover {
            animation: jello 0.9s ease-in-out;
        }

        /* ========== END ADVANCED ANIMATIONS ========== */




</style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 animate-gradient">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('components.admin-sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Header -->
            <header class="bg-white/90 backdrop-blur-xl shadow-lg border-b border-indigo-100 sticky top-0 z-40">
  <div class="flex justify-between items-center px-8 py-4 space-x-8">
    <div class="flex items-center space-x-3">
      <span class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent select-none">Dashboard</span>
    </div>
    <div class="flex items-center space-x-4">
      {{--  <button class="p-3 text-gray-600 rounded-full shadow-md hover:text-indigo-700 hover:bg-indigo-100 transition">
        <i class="fas fa-bell text-xl"></i>
        <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full notification-badge animate-bounce"></span>
      </button>
      <button onclick="toggleTheme()" class="p-3 text-gray-600 rounded-full shadow-md hover:text-indigo-700 hover:bg-indigo-100 transition">
        <i class="fas fa-moon text-xl"></i>
      </button>  --}}
      <!-- Jam baru -->
      <div class="flex flex-col items-end px-6 py-2 bg-gradient-to-br from-indigo-100 via-blue-50 to-purple-50 rounded-xl border border-indigo-100 shadow-sm min-w-[140px] select-none">
        <span class="live-clock text-xl font-mono tracking-widest font-bold text-indigo-600 animate-scale-in">00:00:00</span>
        <span class="live-date text-xs text-indigo-400 font-semibold mt-1"></span>
      </div>
      <!-- User dropdown (biarkan default) -->
    </div>
  </div>
</header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-6 py-4 rounded-xl mb-6 shadow-lg animate-fade-in-up backdrop-blur-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-3 text-xl"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-lg animate-fade-in-up backdrop-blur-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <!-- Total Projects -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white card-lift card-stagger overflow-hidden relative ripple-effect">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-blue-100 text-sm mb-1 font-medium">Total Projects</p>
                                    <h3 class="text-4xl font-bold animate-count-up">{{ $projects->count() }}</h3>
                                </div>
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float">
                                    <i class="fas fa-folder text-2xl icon-bounce"></i>
                                </div>
                            </div>
                            <div class="flex items-center text-blue-100 text-sm">
                                <i class="fas fa-arrow-up mr-2"></i>
                                <span>12% from last month</span>
                            </div>
                        </div>
                    </div>

                    <!-- Active Tasks -->
                    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6 text-white card-lift animate-scale-in overflow-hidden relative" style="animation-delay: 0.1s;">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse" style="animation-delay: 0.5s;"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-orange-100 text-sm mb-1 font-medium">Active Tasks</p>
                                    <h3 class="text-4xl font-bold">
                                        {{ $projects->sum(function($project) {
                                            return $project->boards->sum(function($board) {
                                                return $board->cards->whereIn('status', ['todo', 'in_progress', 'review'])->count();
                                            });
                                        }) }}
                                    </h3>
                                </div>
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float" style="animation-delay: 0.5s;">
                                    <i class="fas fa-tasks text-2xl icon-bounce"></i>
                                </div>
                            </div>
                            <div class="flex items-center text-orange-100 text-sm">
                                <i class="fas fa-fire mr-2 animate-bounce-subtle"></i>
                                <span>8 tasks due today</span>
                            </div>
                        </div>
                    </div>

                    <!-- Team Members - VERSI DENGAN DATABASE -->
<div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-xl overflow-hidden relative animate-scale-in"
     style="animation-delay: 0.2s;"
     x-data="{ showAllUsers: false }">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>

    <!-- Content dengan hover ringan -->
    <div @click="showAllUsers = true"
         class="p-6 text-white cursor-pointer relative z-10 transition-transform duration-200 hover:scale-[1.01]">
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-emerald-100 text-sm mb-1 font-medium">Team Members</p>
                <h3 class="text-4xl font-bold">
                    {{ $users->count() }}
                </h3>
            </div>
            <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-2xl icon-bounce"></i>
            </div>
        </div>
        <div class="flex items-center text-emerald-100 text-sm">
            <i class="fas fa-user-check mr-2"></i>
            <span>Click to view all</span>
        </div>
    </div>

    <!-- MODAL - Menampilkan Data dari Database -->
    <template x-teleport="body">
        <div x-show="showAllUsers"
             @click="showAllUsers = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/50 z-[999] flex items-center justify-center p-4"
             style="display: none;">
            <div @click.stop
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[80vh] overflow-auto">
                <!-- Modal Header -->
                <div class="sticky top-0 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between z-10">
                    <div>
                        <h3 class="text-xl font-bold">All Team Members</h3>
                        <p class="text-sm text-emerald-100">Total {{ $users->count() }} members</p>
                    </div>
                    <button @click="showAllUsers = false" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body - DATA DARI DATABASE -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($users as $user)
                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl hover:shadow-md transition-shadow border border-emerald-100">
                            <!-- Avatar dari Database atau Pravatar -->
                            <img src="{{ $user->avatar_url }}"
                                 alt="{{ $user->full_name ?? $user->username }}"
                                 class="w-16 h-16 rounded-full border-4 border-white shadow-lg object-cover">
                            <div class="flex-1">
                                <!-- Nama dari Database -->
                                <h4 class="font-bold text-gray-900">
                                    {{ $user->full_name ?? $user->username }}
                                </h4>
                                {{--  <!-- Username dari Database -->
                                <p class="text-xs text-gray-500">@{{ $user->username }}</p>  --}}
                                <!-- Email dari Database -->
                                <p class="text-xs text-gray-600 mt-1">{{ $user->email }}</p>
                                <!-- Role Badge -->
                                <span class="inline-flex items-center mt-2 px-2 py-1 rounded-full text-xs font-bold
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' :
                                       ($user->role === 'teamlead' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                                    <i class="fas {{ $user->role === 'admin' ? 'fa-user-shield' : ($user->role === 'teamlead' ? 'fa-user-tie' : 'fa-user') }} mr-1"></i>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-end">
                    <button @click="showAllUsers = false" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors text-sm font-semibold">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

                    <!-- Completed -->
                    <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white card-lift animate-scale-in overflow-hidden relative" style="animation-delay: 0.3s;">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16 animate-pulse" style="animation-delay: 1.5s;"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-purple-100 text-sm mb-1 font-medium">Completed</p>
                                    @php
                                        $allTasks = $projects->sum(function($project) {
                                            return $project->boards->sum(function($board) {
                                                return $board->cards->count();
                                            });
                                        });
                                        $completedTasksCount = $projects->sum(function($project) {
                                            return $project->boards->sum(function($board) {
                                                return $board->cards->where('status', 'done')->count();
                                            });
                                        });
                                        $rate = $allTasks > 0 ? round(($completedTasksCount / $allTasks) * 100) : 0;
                                    @endphp
                                    <h3 class="text-4xl font-bold">{{ $rate }}%</h3>
                                </div>
                                <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center animate-float" style="animation-delay: 1.5s;">
                                    <i class="fas fa-check-circle text-2xl icon-bounce"></i>
                                </div>
                            </div>
                            <div class="flex items-center text-purple-100 text-sm">
                                <i class="fas fa-chart-line mr-2"></i>
                                <span>Great progress!</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section Header -->
                {{--<div class="flex items-center justify-between mb-6 animate-fade-in-up" style="animation-delay: 0.4s;">
                    <div>
                        <h2 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">My Projects</h2>
                        <p class="text-sm text-gray-600 mt-1">Manage and monitor all your projects</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text"
                                id="searchProject"
                                placeholder="search project..."
                                class="pl-11 pr-4 py-3 border-2 border-indigo-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-white/80 backdrop-blur-sm">
                            <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-indigo-400"></i>
                        </div>

                        <!-- Add Project Button -->
                        <a href="{{ route('admin.projects.create') }}"
                           class="flex items-center space-x-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 group btn-press ripple-effect">
                            <i class="fas fa-plus group-hover:rotate-90 transition-transform duration-300"></i>
                            <span class="font-medium">New Project</span>
                        </a>
                    </div>
                </div>--}}

                <!-- Projects Grid -->
                {{--<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @forelse($projects as $index => $project)
                    <div class="project-card bg-white rounded-2xl shadow-lg overflow-hidden border border-indigo-100 hover:shadow-2xl transition-all duration-300 animate-fade-in-up"
                         style="animation-delay: {{ 0.5 + ($index * 0.1) }}s;"
                         x-data="{ showMembers: false }">

                        <!-- ✅ THUMBNAIL PROJECT -->
                        <div class="relative h-48 overflow-hidden group">
                            @if($project->thumbnail)
                                <!-- Jika ada thumbnail -->
                                <img src="{{ asset('storage/' . $project->thumbnail) }}"
                                     alt="{{ $project->project_name }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <!-- Fallback: Gradient jika tidak ada thumbnail -->
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-7xl opacity-30"></i>
                                </div>
                            @endif

                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                            <!-- Status Badge -->
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold shadow-lg backdrop-blur-sm">
                                    Active
                                </span>
                            </div>

                            <!-- Project Name di atas thumbnail -->
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="font-bold text-white text-xl drop-shadow-lg project-name">
                                    {{ $project->project_name }}
                                </h3>
                                <p class="text-white/80 text-xs mt-1">{{ $project->boards->count() }} Boards</p>
                            </div>

                            <!-- Dropdown Menu di Thumbnail -->
                            <div class="absolute top-4 left-4" x-data="{ open: false }">
                                <button @click="open = !open" class="text-white hover:bg-white/20 p-2 rounded-lg transition-all duration-300 backdrop-blur-sm">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div x-show="open"
                                    @click.away="open = false"
                                    x-transition
                                    class="absolute left-0 mt-2 w-48 bg-white rounded-xl shadow-2xl border border-indigo-100 py-2 z-20">
                                    <a href="{{ route('admin.projects.show', $project) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition-colors">
                                        <i class="fas fa-eye mr-2 text-indigo-600"></i>View Details
                                    </a>
                                    <a href="{{ route('admin.projects.showproject', $project) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition-colors">
                                        <i class="fas fa-columns mr-2 text-indigo-600"></i>View Boards
                                    </a>
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition-colors">
                                        <i class="fas fa-edit mr-2 text-indigo-600"></i>Edit
                                    </a>
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                                onclick="return confirm('Delete this project?')">
                                            <i class="fas fa-trash mr-2"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- Description -->
                            <p class="text-sm text-gray-600 mb-4">
                                {{ Str::limit($project->description, 80) }}
                            </p>

                            <!-- Quick Action: View Boards -->
                            <div class="mb-4">
                                <a href="{{ route('admin.projects.showproject', $project) }}"
                                   class="text-sm text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center group">
                                    <i class="fas fa-columns mr-1 group-hover:scale-110 transition-transform"></i>View Boards →
                                </a>
                            </div>

                            <!-- Progress Bar -->
                            @php
                                $totalCards = $project->boards->sum(fn($b) => $b->cards->count());
                                $doneCards = $project->boards->sum(fn($b) => $b->cards->where('status', 'done')->count());
                                $progress = $totalCards > 0 ? round(($doneCards / $totalCards) * 100) : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-medium text-gray-600">Progress</span>
                                    <span class="text-xs font-bold text-blue-600">{{ $progress }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-2.5 rounded-full progress-bar" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="flex items-center justify-between mb-4 text-sm text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-tasks text-indigo-600"></i>
                                    <span class="font-medium">{{ $doneCards }}/{{ $totalCards }}</span>
                                </div>
                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-medium">Active</span>
                            </div>

                            <!-- Team Members -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                                <button @click.prevent="showMembers = true" type="button" class="flex -space-x-2 hover:scale-105 transition-transform cursor-pointer focus:outline-none">
                                    @foreach($project->members->take(4) as $member)
                                    <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                                        alt="{{ $member->full_name }}"
                                        title="{{ $member->full_name }}"
                                        class="w-9 h-9 rounded-full border-2 border-white shadow-md hover:scale-110 transition-transform duration-300">
                                    @endforeach
                                    @if($project->members->count() > 4)
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 border-2 border-white flex items-center justify-center shadow-md">
                                        <span class="text-xs font-semibold text-white">+{{ $project->members->count() - 4 }}</span>
                                    </div>
                                    @endif
                                </button>
                                @if($project->deadline)
                                <span class="text-xs text-gray-500 font-medium flex items-center">
                                    <i class="far fa-clock mr-1 text-indigo-600"></i>{{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- MODAL TEAM MEMBERS -->
                        <div x-show="showMembers"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             @click="showMembers = false"
                             class="fixed inset-0 bg-black/50 modal-backdrop z-50 flex items-center justify-center p-4"
                             style="display: none;">
                            <div @click.stop class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[80vh] overflow-auto"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">
                                <div class="sticky top-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-t-2xl flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold">Team Members</h3>
                                        <p class="text-sm text-indigo-100">{{ $project->project_name }}</p>
                                    </div>
                                    <button @click="showMembers = false" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                                        <i class="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($project->members as $member)
                                        <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-indigo-50 to-violet-50 rounded-xl hover:shadow-md transition-all duration-300 border border-indigo-100">
                                            <img src="https://i.pravatar.cc/150?img={{ $loop->index + 1 }}"
                                                 alt="{{ $member->full_name }}"
                                                 class="w-16 h-16 rounded-full border-4 border-white shadow-lg">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-900">{{ $member->full_name ?: $member->username }}</h4>
                                                <p class="text-xs text-gray-500">@{{ $member->username }}</p>
                                                <p class="text-xs text-gray-600 mt-1">{{ $member->email }}</p>
                                                <span class="inline-flex items-center mt-2 px-2 py-1 rounded-full text-xs font-bold
                                                    {{ $member->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                                    <i class="fas {{ $member->role === 'admin' ? 'fa-user-shield' : 'fa-user' }} mr-1"></i>
                                                    {{ ucfirst($member->role) }}
                                                </span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-6 py-4 rounded-b-2xl flex justify-between items-center">
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-users mr-2 text-indigo-600"></i>
                                        <span class="font-semibold">{{ $project->members->count() }}</span> team members
                                    </p>
                                    <button @click="showMembers = false" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-semibold">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-16 animate-fade-in-up">
                        <div class="w-24 h-24 bg-gradient-to-br from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-subtle">
                            <i class="fas fa-folder-open text-indigo-400 text-4xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">No projects yet</h3>
                        <p class="text-sm text-gray-500 mb-6">Create your first project to get started</p>
                        <a href="{{ route('admin.projects.create') }}"
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 group">
                            <i class="fas fa-plus mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                            <span class="font-medium">Create Project</span>
                        </a>
                    </div>
                    @endforelse
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
					<div class="col-span-1 space-y-6">
                    <!-- Calendar Section -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-calendar text-indigo-600 text-lg"></i>
                                <h3 class="text-lg font-bold text-gray-900">Calendar</h3>
                            </div>
                            <button class="flex items-center space-x-1 px-3 py-1 hover:bg-gray-50 rounded-lg transition text-sm text-gray-600">
                                <i class="fas fa-calendar-alt text-xs"></i>
                                <span>February</span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                        </div>

                        <!-- Calendar Navigation -->
                        <div class="flex items-center justify-between mb-4">
                            <button class="p-1 hover:bg-gray-100 rounded transition">
                                <i class="fas fa-chevron-left text-gray-400"></i>
                            </button>
                            <p class="text-sm font-semibold text-gray-900" x-text="currentMonth"></p>
                            <button class="p-1 hover:bg-gray-100 rounded transition">
                                <i class="fas fa-chevron-right text-gray-400"></i>
                            </button>
                        </div>

                        <!-- Calendar Grid -->
                        <div class="mb-6">
                            <!-- Day Headers -->
                            <div class="grid grid-cols-7 gap-2 mb-2">
                                @foreach(['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                                    <div class="text-center text-xs font-semibold text-gray-500 py-2">{{ $day }}</div>
                                @endforeach
                            </div>

                                    <!-- Calendar Days -->
                                    <div class="grid grid-cols-7 gap-2">
                                        @foreach($calendarDays as $day)
                                            <button class="calendar-day {{ $day['isToday'] ? 'today' : '' }} {{ $day['isSelected'] ? 'active' : '' }} {{ $day['isOtherMonth'] ? 'opacity-30 cursor-default' : '' }} {{ !$day['isOtherMonth'] ? 'hover:bg-gray-100' : '' }}"
                                                    @if(!$day['isOtherMonth']) @click="selectDate('{{ $day['date'] }}')" @endif>
                                                {{ $day['day'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
        </div>
				</div> --}}

				<div class="space-y-8" x-data="dashboard()">
					<!-- Grid Layout: Left (Tasks & Notification) + Right (Calendar) -->
					<div class="grid grid-cols-3 gap-8">
						<!-- Left Column: Today Tasks & Notification -->
						<div class="col-span-2 space-y-6">
							<!-- Today Tasks Section -->
							<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
								<div class="flex items-center justify-between mb-6">
									<div class="flex items-center space-x-2">
										<i class="fas fa-calendar-check text-indigo-600 text-lg"></i>
										<h3 class="text-lg font-bold text-gray-900">Today Tasks</h3>
									</div>
									{{-- <a href="#" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 flex items-center space-x-1">
										<span>See All</span>
										<i class="fas fa-chevron-right text-xs"></i>
									</a> --}}
								</div>

								<!-- Tasks Cards Grid -->
								<div class="grid grid-cols-2 gap-4 mb-6">
									@forelse($todayTasks as $task)
										<div class="task-card bg-gradient-to-br from-gray-50 to-white border border-gray-100 rounded-xl p-5 hover:shadow-lg transition-all">
											<div class="flex items-start justify-between mb-3">
												<div class="flex-1">
													<h4 class="font-semibold text-gray-900 text-sm">{{ $task['title'] }}</h4>
													<p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task['description'] }}</p>
												</div>
												<button class="p-1 hover:bg-gray-100 rounded transition">
													<i class="fas fa-ellipsis-h text-gray-400 text-xs"></i>
												</button>
											</div>

											<!-- Team Members Avatars -->
											<div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100">
												<div class="flex -space-x-2">
													@foreach($task['members'] as $member)
														<img src="https://i.pravatar.cc/150?img={{ $loop->index }}"
															 alt="{{ $member }}"
															 class="w-6 h-6 rounded-full border-2 border-white"
															 title="{{ $member }}">
													@endforeach
													@if(count($task['members']) > 2)
														<div class="w-6 h-6 rounded-full bg-gray-200 border-2 border-white flex items-center justify-center text-xs font-semibold text-gray-600">
															+{{ count($task['members']) - 2 }}
														</div>
													@endif
												</div>

												<!-- Progress -->
												<div class="text-right">
													<p class="text-xs font-semibold text-gray-600">{{ $task['progress'] }}%</p>
												</div>
											</div>

											<!-- Progress Bar -->
											<div class="w-full h-2 bg-gray-200 rounded-full mt-3 overflow-hidden">
												<div class="h-full {{ $task['progress'] >= 80 ? 'bg-teal-500' : ($task['progress'] >= 50 ? 'bg-indigo-500' : 'bg-orange-500') }} progress-bar transition-all duration-1000"
													 :style="{ width: '{{ $task['progress'] }}%' }"></div>
											</div>
										</div>
									@empty
										<div class="col-span-2 bg-gray-50 rounded-xl p-8 text-center">
											<i class="fas fa-inbox text-gray-300 text-4xl mb-3"></i>
											<p class="text-gray-500 text-sm">No tasks for today</p>
										</div>
									@endforelse
								</div>

								<!-- Notification Alert -->
								{{-- <div class="flex items-center justify-between bg-gradient-to-r from-teal-500 to-teal-600 rounded-full px-4 py-3 text-white shadow-md">
									<div class="flex items-center space-x-3">
										<div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
											<i class="fas fa-heart text-white text-sm"></i>
										</div>
										<span class="text-sm font-medium">You have 5 tasks today. Keep it up! ðŸ’ª</span>
									</div>
									<button class="hover:bg-white/10 p-1 rounded transition">
										<i class="fas fa-times text-white"></i>
									</button>
								</div> --}}
							</div>

							<!-- Task Progress Section -->
							<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
								<div class="flex items-center justify-between mb-6">
									<div class="flex items-center space-x-2">
										<i class="fas fa-chart-pie text-indigo-600 text-lg"></i>
										<h3 class="text-lg font-bold text-gray-900">Task Progress</h3>
									</div>
									<button class="p-2 hover:bg-gray-50 rounded-lg transition">
										<i class="fas fa-ellipsis-v text-gray-400"></i>
									</button>
								</div>

								<!-- Progress Chart Container -->
								<div class="flex items-center justify-center h-64">
									<div class="relative w-48 h-48">
										<!-- Center Circle with Percentage -->
										<div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-gray-900 to-gray-800 rounded-full">
											<p class="text-3xl font-bold text-white">65%</p>
											<p class="text-xs text-gray-400 mt-1">Complete</p>
										</div>

										<!-- Progress Indicators Around -->
										<div class="absolute -top-12 left-1/2 -translate-x-1/2 bg-teal-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
											+8%
										</div>
										<div class="absolute top-8 -left-12 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
											+12%
										</div>
										<div class="absolute top-8 -right-12 bg-teal-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
											+6%
										</div>
										<div class="absolute -bottom-12 left-4 bg-gray-700 text-white px-3 py-1 rounded-full text-xs font-semibold">
											+2%
										</div>
										<div class="absolute -bottom-12 right-4 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
											+10%
										</div>
									</div>
								</div>

								<!-- Progress Summary -->
								<div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-100">
									<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
										<span class="text-sm text-gray-600">Completed</span>
										<span class="font-bold text-gray-900">26</span>
									</div>
									<div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
										<span class="text-sm text-gray-600">In Progress</span>
										<span class="font-bold text-gray-900">14</span>
									</div>
								</div>
							</div>
						</div>

						<!-- Right Column: Calendar -->
						<div class="col-span-1 space-y-6">
							<!-- Calendar Section -->
							<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100" x-data="calendarWidget()">
								<div class="flex items-center justify-between mb-6">
									<div class="flex items-center space-x-2">
										<i class="fas fa-calendar text-indigo-600 text-lg"></i>
										<h3 class="text-lg font-bold text-gray-900">Calendar</h3>
									</div>

									<!-- User Selection Dropdown -->
									<div class="relative" x-data="{ open: false }">
										<button @click="open = !open"
												class="flex items-center space-x-2 px-3 py-1 hover:bg-gray-50 rounded-lg transition text-sm text-gray-600 border border-gray-200">
											<i class="fas fa-user text-xs"></i>
											<span x-text="selectedUserName"></span>
											<i class="fas fa-chevron-down text-xs"></i>
										</button>

										<div x-show="open"
											 @click.away="open = false"
											 x-transition
											 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50 max-h-64 overflow-y-auto"
											 style="display: none;">
											@foreach($users as $user)
											<button @click="selectUser({{ $user->id }}, '{{ $user->full_name ?? $user->username }}'); open = false"
													class="w-full text-left px-4 py-2 hover:bg-indigo-50 transition text-sm flex items-center justify-between">
												<div>
													<p class="font-medium text-gray-900">{{ $user->full_name ?? $user->username }}</p>
													<p class="text-xs text-gray-500">{{ ucfirst($user->role) }}</p>
												</div>
												<span x-show="selectedUserId === {{ $user->id }}" class="text-indigo-600">
													<i class="fas fa-check"></i>
												</span>
											</button>
											@endforeach
										</div>
									</div>
								</div>

								<!-- Calendar Navigation -->
								<div class="flex items-center justify-between mb-4">
									<button @click="prevMonth()" class="p-1 hover:bg-gray-100 rounded transition">
										<i class="fas fa-chevron-left text-gray-400"></i>
									</button>
									<p class="text-sm font-semibold text-gray-900" x-text="currentMonth"></p>
									<button @click="nextMonth()" class="p-1 hover:bg-gray-100 rounded transition">
										<i class="fas fa-chevron-right text-gray-400"></i>
									</button>
								</div>

								<!-- Calendar Grid -->
								<div class="mb-6">
									<div class="grid grid-cols-7 gap-2 mb-2">
										<template x-for="day in ['S', 'M', 'T', 'W', 'T', 'F', 'S']" :key="day">
											<div class="text-center text-xs font-semibold text-gray-500 py-2" x-text="day"></div>
										</template>
									</div>

									<div class="grid grid-cols-7 gap-2">
										<template x-for="(day, index) in calendarDays" :key="index">
											<div class="relative">
												<button
													class="calendar-day w-full relative"
													:class="{
														'today': day.isToday,
														'active': day.isSelected,
														'opacity-30 cursor-default': day.isOtherMonth,
														'hover:bg-gray-100': !day.isOtherMonth,
														'bg-green-50 border-2 border-green-500': !day.isOtherMonth && day.hasWork
													}"
													@click="!day.isOtherMonth && selectDate(day.date)"
													x-text="day.day">
												</button>
												<!-- Work indicator dot -->
												<div x-show="!day.isOtherMonth && day.hasWork"
													 class="absolute bottom-1 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 bg-green-500 rounded-full"
													 :title="`${day.workCount} sessions, ${day.totalHours}h total`">
												</div>
											</div>
										</template>
									</div>
								</div>

								<!-- Work Summary -->
								<div x-show="hasWorkDays" class="mt-4 p-3 bg-indigo-50 rounded-lg">
									<div class="flex items-center justify-between text-sm">
										<span class="text-gray-600">Total Work Days:</span>
										<span class="font-bold text-indigo-600" x-text="totalWorkDays"></span>
									</div>
									<div class="flex items-center justify-between text-sm mt-1">
										<span class="text-gray-600">Total Hours:</span>
										<span class="font-bold text-indigo-600" x-text="totalWorkHours + 'h'"></span>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Bottom Row: Task Timeline -->
					<div class="grid grid-cols-1 gap-8">
						<div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
							<div class="flex items-center justify-between mb-6">
								<div class="flex items-center space-x-2">
									<i class="fas fa-stream text-indigo-600 text-lg"></i>
									<h3 class="text-lg font-bold text-gray-900">Task Timeline</h3>
								</div>
								<button class="p-2 hover:bg-gray-50 rounded-lg transition">
									<i class="fas fa-ellipsis-v text-gray-400"></i>
								</button>
							</div>

							<!-- Timeline Container -->
							<div class="flex items-end justify-between h-64 bg-gradient-to-b from-gray-50 to-white rounded-xl p-8 relative">
								<!-- Timeline Grid Lines -->
								<div class="absolute inset-0 opacity-10">
									@for($i = 0; $i < 7; $i++)
										<div class="absolute w-px h-full bg-gray-300"
											 style="left: {{ ($i + 1) * (100 / 7) }}%"></div>
									@endfor
								</div>

								<!-- Timeline Bars -->
								@foreach($timelineData as $item)
									<div class="flex-1 flex flex-col items-center group relative z-10 timeline-item">
										<!-- Timeline Bar -->
										<div class="w-full flex items-end justify-center mb-4 h-40">
											<div class="w-20 rounded-t-2xl timeline-bar origin-bottom"
												 :style="{ height: 'calc({{ $item['height'] }}% * 150px / 100)', background: '{{ $item['color'] }}' }}"
												 :title="'{{ $item['title'] }}'"
												 @mouseenter="activeTimeline = '{{ $item['id'] }}'"
												 @mouseleave="activeTimeline = null">
											</div>
										</div>

										<!-- Timeline Label -->
										<p class="text-sm font-semibold text-gray-900 text-center bg-white px-4 py-2 rounded-full border-2 transition-all duration-300"
										   :class="{ 'border-gray-300': activeTimeline !== '{{ $item['id'] }}', 'border-{{ $item['color-class'] }}': activeTimeline === '{{ $item['id'] }}' }">
											{{ $item['title'] }}
										</p>

										<!-- Date Label -->
										<p class="text-xs text-gray-400 mt-2">{{ $item['date'] }}</p>
									</div>
								@endforeach

								<!-- X-Axis -->
								<div class="absolute bottom-0 left-0 right-0 h-px bg-gray-300"></div>
							</div>

							<!-- Timeline Legend -->
							<div class="mt-6 grid grid-cols-4 gap-4">
								@foreach($timelineData as $item)
									<div class="flex items-center space-x-2">
										<div class="w-3 h-3 rounded-full" style="background-color: {{ $item['color'] }}"></div>
										<span class="text-xs font-medium text-gray-600">{{ $item['title'] }}</span>
									</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</main>
        </div>
    </div>

    <script>
        // Simple Search Functionality
        document.getElementById('searchProject')?.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const projectCards = document.querySelectorAll('.project-card');

            projectCards.forEach(card => {
                const projectName = card.querySelector('.project-name')?.textContent.toLowerCase() || '';
                card.style.display = projectName.includes(searchValue) ? '' : 'none';
            });
        });
		// function dashboard() {
			// return {
				// activeTimeline: null,
				// selectedDate: new Date(),
				// currentMonth: 'February 2025',

				// selectDate(date) {
					// this.selectedDate = new Date(date);
					// console.log('Selected:', date);
				// }
			// }
		// }
		// function dashboard() {
			// return {
				// activeTimeline: null,
				// selectedDate: new Date(),
				// currentMonth: '',
				// currentYear: 0,
				// currentMonthIndex: 0,
				// calendarDays: [],

				// init() {
					// const now = new Date();
					// this.currentYear = now.getFullYear();
					// this.currentMonthIndex = now.getMonth();
					// this.generateCalendar();
				// },

				// generateCalendar() {
					// const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
									  // 'July', 'August', 'September', 'October', 'November', 'December'];

					// this.currentMonth = `${monthNames[this.currentMonthIndex]} ${this.currentYear}`;

					// const firstDay = new Date(this.currentYear, this.currentMonthIndex, 1);
					// const lastDay = new Date(this.currentYear, this.currentMonthIndex + 1, 0);
					// const prevLastDay = new Date(this.currentYear, this.currentMonthIndex, 0);

					// const firstDayIndex = firstDay.getDay();
					// const lastDayDate = lastDay.getDate();
					// const prevLastDayDate = prevLastDay.getDate();

					// this.calendarDays = [];

					// // Previous month days
					// for (let i = firstDayIndex - 1; i >= 0; i--) {
						// this.calendarDays.push({
							// day: prevLastDayDate - i,
							// isOtherMonth: true,
							// isToday: false,
							// isSelected: false
						// });
					// }

					// // Current month days
					// const today = new Date();
					// for (let i = 1; i <= lastDayDate; i++) {
						// const isToday = i === today.getDate() &&
									  // this.currentMonthIndex === today.getMonth() &&
									  // this.currentYear === today.getFullYear();

						// this.calendarDays.push({
							// day: i,
							// isOtherMonth: false,
							// isToday: isToday,
							// isSelected: false,
							// date: `${this.currentYear}-${String(this.currentMonthIndex + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
						// });
					// }

					// // Next month days
					// const remainingDays = 42 - this.calendarDays.length; // 6 rows x 7 days
					// for (let i = 1; i <= remainingDays; i++) {
						// this.calendarDays.push({
							// day: i,
							// isOtherMonth: true,
							// isToday: false,
							// isSelected: false
						// });
					// }
				// },

				// prevMonth() {
					// this.currentMonthIndex--;
					// if (this.currentMonthIndex < 0) {
						// this.currentMonthIndex = 11;
						// this.currentYear--;
					// }
					// this.generateCalendar();
				// },

				// nextMonth() {
					// this.currentMonthIndex++;
					// if (this.currentMonthIndex > 11) {
						// this.currentMonthIndex = 0;
						// this.currentYear++;
					// }
					// this.generateCalendar();
				// },

				// selectDate(date) {
					// this.selectedDate = new Date(date);
					// console.log('Selected:', date);
				// }
			// }
		// }
		function dashboard() {
			return {
				activeTimeline: null,
				selectedDate: new Date(),
				currentMonth: '',
				currentYear: 0,
				currentMonthIndex: 0,
				calendarDays: [],
				timeLogsData: {},

				init() {
					const now = new Date();
					this.currentYear = now.getFullYear();
					this.currentMonthIndex = now.getMonth();
					this.generateCalendar();
					this.loadTimeLogs();
				},

				async loadTimeLogs() {
					try {
						const response = await fetch(`/api/time-logs-calendar?year=${this.currentYear}&month=${this.currentMonthIndex + 1}`);
						const result = await response.json();

						if (result.success) {
							this.timeLogsData = result.data;
							this.updateCalendarWithTimeLogs();
						}
					} catch (error) {
						console.error('Error loading time logs:', error);
					}
				},

				updateCalendarWithTimeLogs() {
					this.calendarDays.forEach(day => {
						if (!day.isOtherMonth && day.date) {
							const logData = this.timeLogsData[day.date];
							day.hasWork = logData ? logData.has_work : false;
							day.workCount = logData ? logData.count : 0;
							day.totalHours = logData ? logData.total_hours : 0;
						}
					});
				},

				generateCalendar() {
					const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
									  'July', 'August', 'September', 'October', 'November', 'December'];

					this.currentMonth = `${monthNames[this.currentMonthIndex]} ${this.currentYear}`;

					const firstDay = new Date(this.currentYear, this.currentMonthIndex, 1);
					const lastDay = new Date(this.currentYear, this.currentMonthIndex + 1, 0);
					const prevLastDay = new Date(this.currentYear, this.currentMonthIndex, 0);

					const firstDayIndex = firstDay.getDay();
					const lastDayDate = lastDay.getDate();
					const prevLastDayDate = prevLastDay.getDate();

					this.calendarDays = [];

					// Previous month days
					for (let i = firstDayIndex - 1; i >= 0; i--) {
						this.calendarDays.push({
							day: prevLastDayDate - i,
							isOtherMonth: true,
							isToday: false,
							isSelected: false,
							hasWork: false
						});
					}

					// Current month days
					const today = new Date();
					for (let i = 1; i <= lastDayDate; i++) {
						const isToday = i === today.getDate() &&
									  this.currentMonthIndex === today.getMonth() &&
									  this.currentYear === today.getFullYear();

						this.calendarDays.push({
							day: i,
							isOtherMonth: false,
							isToday: isToday,
							isSelected: false,
							hasWork: false,
							workCount: 0,
							totalHours: 0,
							date: `${this.currentYear}-${String(this.currentMonthIndex + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
						});
					}

					// Next month days
					const remainingDays = 42 - this.calendarDays.length;
					for (let i = 1; i <= remainingDays; i++) {
						this.calendarDays.push({
							day: i,
							isOtherMonth: true,
							isToday: false,
							isSelected: false,
							hasWork: false
						});
					}
				},

				prevMonth() {
					this.currentMonthIndex--;
					if (this.currentMonthIndex < 0) {
						this.currentMonthIndex = 11;
						this.currentYear--;
					}
					this.generateCalendar();
					this.loadTimeLogs();
				},

				nextMonth() {
					this.currentMonthIndex++;
					if (this.currentMonthIndex > 11) {
						this.currentMonthIndex = 0;
						this.currentYear++;
					}
					this.generateCalendar();
					this.loadTimeLogs();
				},

				selectDate(date) {
					this.selectedDate = new Date(date);
					const dayData = this.calendarDays.find(d => d.date === date);
					if (dayData && dayData.hasWork) {
						notify(`You worked ${dayData.totalHours}h on this day! 🎯`, 'success');
					}
				}
			}
		}

		function calendarWidget() {
			return {
				selectedUserId: {{ Auth::id() }},
				selectedUserName: '{{ Auth::user()->full_name ?? Auth::user()->username }}',
				currentMonth: '',
				currentYear: 0,
				currentMonthIndex: 0,
				calendarDays: [],
				timeLogsData: {},
				totalWorkDays: 0,
				totalWorkHours: 0,
				hasWorkDays: false,

				init() {
					const now = new Date();
					this.currentYear = now.getFullYear();
					this.currentMonthIndex = now.getMonth();
					this.generateCalendar();
					this.loadTimeLogs();
				},

				selectUser(userId, userName) {
					this.selectedUserId = userId;
					this.selectedUserName = userName;
					this.loadTimeLogs();
				},

				async loadTimeLogs() {
					try {
						const response = await fetch(`/api/time-logs-calendar?year=${this.currentYear}&month=${this.currentMonthIndex + 1}&user_id=${this.selectedUserId}`);
						const result = await response.json();

						if (result.success) {
							this.timeLogsData = result.data;
							this.updateCalendarWithTimeLogs();
							this.calculateSummary();
						}
					} catch (error) {
						console.error('Error loading time logs:', error);
					}
				},

				updateCalendarWithTimeLogs() {
					this.calendarDays.forEach(day => {
						if (!day.isOtherMonth && day.date) {
							const logData = this.timeLogsData[day.date];
							day.hasWork = logData ? logData.has_work : false;
							day.workCount = logData ? logData.count : 0;
							day.totalHours = logData ? logData.total_hours : 0;
						}
					});
				},

				calculateSummary() {
					let workDays = 0;
					let totalHours = 0;

					Object.values(this.timeLogsData).forEach(log => {
						if (log.has_work) {
							workDays++;
							totalHours += log.total_hours;
						}
					});

					this.totalWorkDays = workDays;
					this.totalWorkHours = Math.round(totalHours * 10) / 10;
					this.hasWorkDays = workDays > 0;
				},

				generateCalendar() {
					const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
									  'July', 'August', 'September', 'October', 'November', 'December'];

					this.currentMonth = `${monthNames[this.currentMonthIndex]} ${this.currentYear}`;

					const firstDay = new Date(this.currentYear, this.currentMonthIndex, 1);
					const lastDay = new Date(this.currentYear, this.currentMonthIndex + 1, 0);
					const prevLastDay = new Date(this.currentYear, this.currentMonthIndex, 0);

					const firstDayIndex = firstDay.getDay();
					const lastDayDate = lastDay.getDate();
					const prevLastDayDate = prevLastDay.getDate();

					this.calendarDays = [];

					// Previous month days
					for (let i = firstDayIndex - 1; i >= 0; i--) {
						this.calendarDays.push({
							day: prevLastDayDate - i,
							isOtherMonth: true,
							isToday: false,
							isSelected: false,
							hasWork: false
						});
					}

					// Current month days
					const today = new Date();
					for (let i = 1; i <= lastDayDate; i++) {
						const isToday = i === today.getDate() &&
									  this.currentMonthIndex === today.getMonth() &&
									  this.currentYear === today.getFullYear();

						this.calendarDays.push({
							day: i,
							isOtherMonth: false,
							isToday: isToday,
							isSelected: false,
							hasWork: false,
							workCount: 0,
							totalHours: 0,
							date: `${this.currentYear}-${String(this.currentMonthIndex + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
						});
					}

					// Next month days
					const remainingDays = 42 - this.calendarDays.length;
					for (let i = 1; i <= remainingDays; i++) {
						this.calendarDays.push({
							day: i,
							isOtherMonth: true,
							isToday: false,
							isSelected: false,
							hasWork: false
						});
					}
				},

				prevMonth() {
					this.currentMonthIndex--;
					if (this.currentMonthIndex < 0) {
						this.currentMonthIndex = 11;
						this.currentYear--;
					}
					this.generateCalendar();
					this.loadTimeLogs();
				},

				nextMonth() {
					this.currentMonthIndex++;
					if (this.currentMonthIndex > 11) {
						this.currentMonthIndex = 0;
						this.currentYear++;
					}
					this.generateCalendar();
					this.loadTimeLogs();
				},

				selectDate(date) {
					const dayData = this.calendarDays.find(d => d.date === date);
					if (dayData && dayData.hasWork) {
						notify(`${this.selectedUserName} worked ${dayData.totalHours}h on this day (${dayData.workCount} sessions) 🎯`, 'success');
					} else {
						notify(`No work logged on this day`, 'info');
					}
				}
			}
		}
    </script>

    <script>
        // Counter Animation dengan Alpine.js
        document.addEventListener('alpine:init', () => {
            Alpine.data('counterAnimation', (target) => ({
                count: 0,
                target: target,

                init() {
                    this.animateCount();
                },

                animateCount() {
                    const duration = 2000; // 2 detik
                    const steps = 60;
                    const increment = this.target / steps;
                    let current = 0;

                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= this.target) {
                            this.count = this.target;
                            clearInterval(timer);
                        } else {
                            this.count = Math.floor(current);
                        }
                    }, duration / steps);
                }
            }));
        });

        // Scroll Reveal Animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal-on-scroll');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe semua elemen yang ingin di-reveal
        document.addEventListener('DOMContentLoaded', () => {
            const revealElements = document.querySelectorAll('.task-card, .calendar-day, .timeline-item');
            revealElements.forEach(el => observer.observe(el));
        });

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `toast-notification fixed top-4 right-4 px-6 py-4 rounded-xl shadow-2xl z-[9999] ${
                type === 'success' ? 'bg-gradient-to-r from-teal-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-pink-600'
            } text-white font-medium flex items-center space-x-3`;

            toast.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} text-xl"></i>
                <span>${message}</span>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.animation = 'toast-slide-in 0.5s reverse';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }

        // Loading Spinner Function
        function showLoading(show = true) {
            let spinner = document.getElementById('global-spinner');

            if (show && !spinner) {
                spinner = document.createElement('div');
                spinner.id = 'global-spinner';
                spinner.className = 'fixed inset-0 bg-black/50 modal-backdrop z-[9999] flex items-center justify-center';
                spinner.innerHTML = `
                    <div class="bg-white rounded-2xl p-8 shadow-2xl flex flex-col items-center space-y-4">
                        <div class="w-12 h-12 border-4 border-indigo-200 border-t-indigo-600 rounded-full loading-spinner"></div>
                        <p class="text-gray-700 font-medium">Loading...</p>
                    </div>
                `;
                document.body.appendChild(spinner);
            } else if (!show && spinner) {
                spinner.remove();
            }
        }

        // ========== ADVANCED FEATURES ==========

        // 1. Particle Background Generator
        function createParticles(container) {
            const particleCount = 20;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle fixed pointer-events-none';
                particle.style.width = Math.random() * 4 + 2 + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.opacity = Math.random() * 0.5 + 0.2;
                document.body.appendChild(particle);
            }
        }

        // 2. Magnetic Button Effect
        function magneticButtons() {
            const buttons = document.querySelectorAll('.magnetic-button');

            buttons.forEach(button => {
                button.addEventListener('mousemove', (e) => {
                    const rect = button.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;

                    button.style.transform = `translate(${x * 0.3}px, ${y * 0.3}px)`;
                });

                button.addEventListener('mouseleave', () => {
                    button.style.transform = 'translate(0, 0)';
                });
            });
        }

        // 3. Confetti Celebration
        function launchConfetti(x, y) {
            const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe', '#00f2fe', '#43e97b', '#fa709a'];
            const confettiCount = 50;

            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti fixed pointer-events-none';
                confetti.style.left = x + 'px';
                confetti.style.top = y + 'px';
                confetti.style.width = Math.random() * 10 + 5 + 'px';
                confetti.style.height = confetti.style.width;
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 0.5 + 's';
                confetti.style.animationDuration = Math.random() * 2 + 2 + 's';
                confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';

                document.body.appendChild(confetti);

                setTimeout(() => confetti.remove(), 4000);
            }
        }

        // 4. Progress Bar dengan Counter
        function animateProgressBar(element, targetPercentage) {
            let current = 0;
            const increment = targetPercentage / 100;
            const timer = setInterval(() => {
                current += increment;
                if (current >= targetPercentage) {
                    current = targetPercentage;
                    clearInterval(timer);
                }
                element.style.width = current + '%';
                const counterText = element.getAttribute('data-counter');
                if (counterText) {
                    const counter = document.querySelector(counterText);
                    if (counter) counter.textContent = Math.floor(current) + '%';
                }
            }, 10);
        }

        // 5. Smooth Scroll dengan Easing
        function smoothScrollTo(element, duration = 1000) {
            const targetPosition = element.getBoundingClientRect().top + window.pageYOffset;
            const startPosition = window.pageYOffset;
            const distance = targetPosition - startPosition;
            let startTime = null;

            function animation(currentTime) {
                if (startTime === null) startTime = currentTime;
                const timeElapsed = currentTime - startTime;
                const run = ease(timeElapsed, startPosition, distance, duration);
                window.scrollTo(0, run);
                if (timeElapsed < duration) requestAnimationFrame(animation);
            }

            function ease(t, b, c, d) {
                t /= d / 2;
                if (t < 1) return c / 2 * t * t + b;
                t--;
                return -c / 2 * (t * (t - 2) - 1) + b;
            }

            requestAnimationFrame(animation);
        }

        // 6. Auto-save Draft (untuk forms)
        function autoSaveDraft(formId, interval = 30000) {
            const form = document.getElementById(formId);
            if (!form) return;

            setInterval(() => {
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                localStorage.setItem(`draft_${formId}`, JSON.stringify(data));

                showToast('Draft saved automatically', 'success');
            }, interval);
        }

        // 7. Keyboard Shortcuts
        function setupKeyboardShortcuts() {
            document.addEventListener('keydown', (e) => {
                // Ctrl/Cmd + K untuk search
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    const searchInput = document.getElementById('searchProject');
                    if (searchInput) searchInput.focus();
                }

                // Ctrl/Cmd + N untuk new project
                if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
                    e.preventDefault();
                    const newProjectBtn = document.querySelector('a[href*="create"]');
                    if (newProjectBtn) newProjectBtn.click();
                }

                // Escape untuk close modals
                if (e.key === 'Escape') {
                    const modals = document.querySelectorAll('[x-show]');
                    modals.forEach(modal => {
                        if (modal.style.display !== 'none') {
                            modal.dispatchEvent(new Event('click'));
                        }
                    });
                }
            });
        }

        // 8. Real-time Clock
        function updateClock() {
            const now = new Date();
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: false
            };
            const timeString = now.toLocaleTimeString('id-ID', options);

            const clockElements = document.querySelectorAll('.live-clock');
            clockElements.forEach(el => el.textContent = timeString);
        }

        // 9. Drag and Drop File Upload
        function setupDragDrop(dropZoneId) {
            const dropZone = document.getElementById(dropZoneId);
            if (!dropZone) return;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
                }, false);
            });

            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                handleFiles(files);
            }, false);
        }

        function handleFiles(files) {
            ([...files]).forEach(uploadFile);
        }

        function uploadFile(file) {
            console.log('Uploading:', file.name);
            showToast(`Uploading ${file.name}...`, 'success');
        }

        // 10. Theme Switcher (Light/Dark/Auto)
        function setupThemeSwitcher() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);

            window.toggleTheme = function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                showToast(`Switched to ${newTheme} mode`, 'success');
            };
        }

        // 11. Notification System
        class NotificationSystem {
            constructor() {
                this.notifications = [];
                this.container = this.createContainer();
            }

            createContainer() {
                const container = document.createElement('div');
                container.id = 'notification-container';
                container.className = 'fixed top-4 right-4 z-[9999] space-y-2';
                document.body.appendChild(container);
                return container;
            }

            show(message, type = 'info', duration = 3000) {
                const notification = document.createElement('div');
                notification.className = `toast-notification px-6 py-4 rounded-xl shadow-2xl text-white font-medium flex items-center space-x-3 ${
                    type === 'success' ? 'bg-gradient-to-r from-teal-500 to-emerald-600' :
                    type === 'error' ? 'bg-gradient-to-r from-red-500 to-pink-600' :
                    type === 'warning' ? 'bg-gradient-to-r from-amber-500 to-orange-600' :
                    'bg-gradient-to-r from-indigo-500 to-purple-600'
                }`;

                const icon = type === 'success' ? 'check-circle' :
                            type === 'error' ? 'exclamation-circle' :
                            type === 'warning' ? 'exclamation-triangle' : 'info-circle';

                notification.innerHTML = `
                    <i class="fas fa-${icon} text-xl"></i>
                    <span>${message}</span>
                    <button onclick="this.parentElement.remove()" class="ml-4 hover:bg-white/20 p-1 rounded">
                        <i class="fas fa-times"></i>
                    </button>
                `;

                this.container.appendChild(notification);

                if (duration > 0) {
                    setTimeout(() => {
                        notification.style.animation = 'toast-slide-in 0.5s reverse';
                        setTimeout(() => notification.remove(), 500);
                    }, duration);
                }
            }
        }

        // Initialize Notification System
        const notificationSystem = new NotificationSystem();
        window.notify = (message, type, duration) => notificationSystem.show(message, type, duration);

        // 12. Page Load Progress Bar
        function showPageLoadProgress() {
            const progress = document.createElement('div');
            progress.className = 'fixed top-0 left-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-600 z-[99999] transition-all duration-300';
            progress.style.width = '0%';
            document.body.appendChild(progress);

            let width = 0;
            const interval = setInterval(() => {
                width += Math.random() * 30;
                if (width >= 90) {
                    clearInterval(interval);
                }
                progress.style.width = Math.min(width, 90) + '%';
            }, 200);

            window.addEventListener('load', () => {
                clearInterval(interval);
                progress.style.width = '100%';
                setTimeout(() => progress.remove(), 500);
            });
        }

        // Initialize all features on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            // Create particles
            createParticles();

            // Setup magnetic buttons
            magneticButtons();

            // Setup keyboard shortcuts
            setupKeyboardShortcuts();

            // Update clock every second
            setInterval(updateClock, 1000);
            updateClock();

            // Setup theme switcher
            setupThemeSwitcher();

            // Animate existing progress bars
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.style.width;
                const percentage = parseInt(width);
                if (!isNaN(percentage)) {
                    bar.style.width = '0%';
                    setTimeout(() => animateProgressBar(bar, percentage), 500);
                }
            });

            // Show success message

        // Scroll Progress Bar
        window.addEventListener('scroll', () => {
            const scrollProgress = document.getElementById('scroll-progress');
            const scrollPercentage = (window.scrollY / (document.documentElement.scrollHeight - window.innerHeight)) * 100;
            if (scrollProgress) {
                scrollProgress.style.width = scrollPercentage + '%';
            }
        });

        // Alt + T untuk toggle theme
        document.addEventListener('keydown', (e) => {
            if (e.altKey && e.key === 't') {
                e.preventDefault();
                toggleTheme();
            }
        });



        // Alpine.js Global Store
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                currentMood: '😊',
                selectedReaction: null,
                dailyStreak: 7,
                totalPoints: 1250
            });
        });


            console.log('✅ Dashboard Ultimate loaded successfully!');
            console.log('⌨️ Keyboard Shortcuts:');
            console.log('  - Ctrl/Cmd + K: Focus search');
            console.log('  - Ctrl/Cmd + N: New project');
            console.log('  - Escape: Close modals');
        });

        // Confetti on task completion
        document.addEventListener('click', (e) => {
            if (e.target.closest('.task-complete-btn')) {
                launchConfetti(e.clientX, e.clientY);
                notify('Task completed! 🎉', 'success');
            }
        });


        // ========== EMOJI & ADVANCED FEATURES FUNCTIONS ==========

        // Emoji Reaction Function
        window.emojiReact = function(emoji) {
            const container = document.body;

             i < 15; i++) {
                const emojiElement = document.createElement('div');
                emojiElement.textContent = emoji;
                emojiElement.className = 'fixed pointer-events-none text-4xl emoji-burst';
                emojiElement.style.left = (window.innerWidth / 2) + 'px';
                emojiElement.style.bottom = '100px';
                emojiElement.style.transform = `translate(${Math.random() * 200 - 100}px, ${Math.random() * -200}px)`;
                emojiElement.style.zIndex = '99999';
                container.appendChild(emojiElement);

                setTimeout(() => emojiElement.remove(), 600);
            }

            // Create floating hearts
            createFloatingHearts(emoji, 5);

            // Show notification
            notify(`You reacted with ${emoji}!`, 'success');

            // Play sound effect (optional - uncomment if you have sound files)

        };

        // Create Floating Hearts/Emojis
        function createFloatingHearts(emoji, count = 5) {
            for (let i = 0; i < count; i++) {
                setTimeout(() => {
                    const heart = document.createElement('div');
                    heart.textContent = emoji;
                    heart.className = 'fixed pointer-events-none text-3xl float-heart';
                    heart.style.left = (Math.random() * window.innerWidth) + 'px';
                    heart.style.bottom = '0';
                    heart.style.zIndex = '99999';
                    document.body.appendChild(heart);

                    setTimeout(() => heart.remove(), 1500);
                }, i * 200);
            }
        }

        // Quick Emoji Action
        window.quickEmojiAction = function(emoji) {
            const actions = {
                '🎯': { message: 'Goal set! Let\'s crush it! 🚀', type: 'success' },
                '⏰': { message: 'Reminder set! We\'ll notify you ⏰', type: 'info' },
                '📝': { message: 'Quick note created! 📝', type: 'success' },
                '💡': { message: 'Great idea captured! 💡', type: 'success' }
            };

            const action = actions[emoji] || { message: 'Action completed!', type: 'success' };





            notify(action.message, action.type);
        };

        // Set Mood Function
        window.setMood = function(mood) {
            Alpine.store('app', { currentMood: mood });


            moodElement.textContent = mood;
            moodElement.className = 'fixed text-8xl emoji-sparkle pointer-events-none';
            moodElement.style.left = '50%';
            moodElement.style.top = '50%';
            moodElement.style.transform = 'translate(-50%, -50%)';
            moodElement.style.zIndex = '99999';
            document.body.appendChild(moodElement);

            setTimeout(() => moodElement.remove(), 1000);

            notify(`Mood set to ${mood}! Have a great day! 🌟`, 'success');
        };

        // Sparkle Effect
        function createSparkleEffect(x, y) {
            const colors = ['#FFD700', '#FFA500', '#FF69B4', '#00CED1', '#9370DB'];

            for (let i = 0; i < 20; i++) {
                const sparkle = document.createElement('div');
                sparkle.className = 'fixed pointer-events-none rounded-full';
                sparkle.style.width = (Math.random() * 8 + 4) + 'px';
                sparkle.style.height = sparkle.style.width;
                sparkle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                sparkle.style.left = x + 'px';
                sparkle.style.top = y + 'px';
                sparkle.style.zIndex = '99999';

                const angle = Math.random() * Math.PI * 2;
                const distance = Math.random() * 100 + 50;
                const targetX = x + Math.cos(angle) * distance;
                const targetY = y + Math.sin(angle) * distance;

                sparkle.animate([
                    { transform: 'translate(0, 0) scale(1)', opacity: 1 },
                    { transform: `translate(${targetX - x}px, ${targetY - y}px) scale(0)`, opacity: 0 }
                ], {
                    duration: 1000,
                    easing: 'cubic-bezier(0, 0.5, 0.5, 1)'
                });

                document.body.appendChild(sparkle);
                setTimeout(() => sparkle.remove(), 1000);
            }
        }

        // Mini Confetti
        function launchMiniConfetti(x, y, count = 20) {
            const colors = ['#667eea', '#764ba2', '#f093fb', '#4facfe'];

            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.className = 'fixed pointer-events-none';
                particle.style.width = (Math.random() * 6 + 3) + 'px';
                particle.style.height = particle.style.width;
                particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                particle.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
                particle.style.left = x + 'px';
                particle.style.top = y + 'px';
                particle.style.zIndex = '99999';

                const angle = Math.random() * Math.PI * 2;
                const velocity = Math.random() * 5 + 3;
                const gravity = 0.3;
                let vx = Math.cos(angle) * velocity;
                let vy = Math.sin(angle) * velocity - 5;

                let posX = 0, posY = 0;
                const animation = setInterval(() => {
                    posX += vx;
                    posY += vy;
                    vy += gravity;

                    particle.style.transform = `translate(${posX}px, ${posY}px) rotate(${posX * 2}deg)`;

                    if (posY > 500) {
                        clearInterval(animation);
                        particle.remove();
                    }
                }, 16);

                document.body.appendChild(particle);
            }
        }

        // Achievement Notification
        window.showAchievement = function(title, description, emoji = '🏆') {
            const container = document.getElementById('achievement-container');

            const achievement = document.createElement('div');
            achievement.className = 'bg-gradient-to-r from-yellow-400 via-orange-500 to-pink-500 text-white rounded-xl shadow-2xl p-4 flex items-center space-x-3 slide-up-reveal transform hover:scale-105 transition-all duration-300';
            achievement.innerHTML = `
                <div class="text-4xl emoji-button emoji-sparkle">${emoji}</div>
                <div class="flex-1">
                    <h4 class="font-bold text-sm">${title}</h4>
                    <p class="text-xs opacity-90">${description}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="hover:bg-white/20 p-2 rounded-lg transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(achievement);



            // Auto remove after 5 seconds
            setTimeout(() => {
                achievement.style.animation = 'slide-up-reveal 0.5s reverse';
                setTimeout(() => achievement.remove(), 500);
            }, 5000);
        };

        // Typing Indicator
        {{--  window.showTypingIndicator = function(show = true) {  --}}
        window.showTypingIndicator = function(show = false) {
            let indicator = document.getElementById('typing-indicator');

            if (show && !indicator) {
                indicator = document.createElement('div');
                indicator.id = 'typing-indicator';
                indicator.className = 'fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg typing-indicator flex items-center space-x-2';
                indicator.innerHTML = `
                    <span class="w-2 h-2 bg-white rounded-full"></span>
                    <span class="w-2 h-2 bg-white rounded-full"></span>
                    <span class="w-2 h-2 bg-white rounded-full"></span>
                    <span class="ml-2 text-sm">Typing...</span>
                `;
                document.body.appendChild(indicator);
            } else if (!show && indicator) {
                indicator.remove();
            }
        };

        // Screen Record Effect
        window.startScreenRecord = function() {
            const indicator = document.createElement('div');
            indicator.className = 'fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-4 py-2 rounded-full shadow-lg flex items-center space-x-2 z-[99999] pulse-ring';
            indicator.innerHTML = `
                <div class="w-3 h-3 bg-white rounded-full animate-pulse"></div>
                <span class="text-sm font-semibold">Recording...</span>
            `;
            document.body.appendChild(indicator);

            notify('Screen recording started! 🎥', 'info');

            return indicator;
        };

        // Progress Celebration
        window.celebrateProgress = function(percentage) {
            if (percentage >= 100) {
                showAchievement('🎉 Task Completed!', 'Amazing work! Keep it up!', '🎉');
                launchConfetti(window.innerWidth / 2, window.innerHeight / 2);
            } else if (percentage >= 75) {
                notify('Great progress! 🔥 Almost there!', 'success');
                createFloatingHearts('⭐', 3);
            } else if (percentage >= 50) {
                notify('Halfway there! 💪 Keep going!', 'info');
            }
        };

        // Sound Effect (Optional - requires audio files)
        window.playSound = function(type) {
            const sounds = {
                'click': '/sounds/click.mp3',
                'success': '/sounds/success.mp3',
                'reaction': '/sounds/reaction.mp3',
                'achievement': '/sounds/achievement.mp3'
            };

            if (sounds[type]) {
                const audio = new Audio(sounds[type]);
                audio.volume = 0.3;
                audio.play().catch(e => console.log('Sound play failed:', e));
            }
        };

        // Initialize Emoji Features
        document.addEventListener('DOMContentLoaded', () => {
            console.log('✅ Emoji & Advanced Features loaded!');


            }, 2000);

            // Simulate progress celebration every 30 seconds (demo)
            let demoProgress = 0;
            setInterval(() => {
                demoProgress += 25;
                if (demoProgress <= 100) {
                    celebrateProgress(demoProgress);
                }
            }, 30000);
        });

        // Easter Egg: Konami Code
        let konamiCode = [];
        const konamiSequence = ['ArrowUp', 'ArrowUp', 'ArrowDown', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowLeft', 'ArrowRight', 'b', 'a'];

        document.addEventListener('keydown', (e) => {
            konamiCode.push(e.key);
            konamiCode = konamiCode.slice(-10);

            if (konamiCode.join(',') === konamiSequence.join(',')) {
                // Activate secret mode!
                showAchievement('🎮 Secret Unlocked!', 'You found the Konami Code!', '🕹️');
                launchConfetti(window.innerWidth / 2, window.innerHeight / 2);

                // Make everything rainbow!
                document.body.style.animation = 'holographic 3s ease infinite';
                setTimeout(() => {
                    document.body.style.animation = '';
                }, 5000);

                konamiCode = [];
            }
        });

        // Double Click Easter Egg on Logo
        let logoClickCount = 0;
        document.addEventListener('click', (e) => {
            if (e.target.closest('h1')) {
                logoClickCount++;
                if (logoClickCount === 5) {
                    showAchievement('🎨 Artist Mode!', 'You unlocked special effects!', '✨');
                    launchMiniConfetti(window.innerWidth / 2, 100, 50);
                    logoClickCount = 0;
                }
                setTimeout(() => logoClickCount = 0, 2000);
            }
        });

        // ========== END EMOJI & ADVANCED FEATURES ==========

        // ========== END ADVANCED FEATURES ==========

    </script>



    <!-- Floating Action Button -->
    {{-- <div class="fixed bottom-8 right-8 z-50" x-data="{ showMenu: false }">
        <!-- Main FAB -->
        <button @click="showMenu = !showMenu"
                class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full shadow-2xl hover:shadow-indigo-500/50 transition-all duration-300 flex items-center justify-center group magnetic-button glow-active">
            <i class="fas transition-all duration-300"
               :class="showMenu ? 'fa-times rotate-90' : 'fa-plus'"></i>
        </button>

        <!-- FAB Menu -->
        <div x-show="showMenu"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-0"
             x-transition:enter-end="opacity-100 scale-100"
             @click.away="showMenu = false"
             class="absolute bottom-20 right-0 flex flex-col space-y-3"
             style="display: none;">

            <button onclick="notify('Quick add project', 'success'); launchConfetti(window.innerWidth - 100, window.innerHeight - 100);"
                    class="w-12 h-12 bg-blue-500 text-white rounded-full shadow-lg hover:shadow-blue-500/50 transition-all duration-300 flex items-center justify-center breathe"
                    title="Add Project">
                <i class="fas fa-folder-plus"></i>
            </button>

            <button onclick="notify('Quick add task', 'success');"
                    class="w-12 h-12 bg-green-500 text-white rounded-full shadow-lg hover:shadow-green-500/50 transition-all duration-300 flex items-center justify-center breathe"
                    style="animation-delay: 0.1s;"
                    title="Add Task">
                <i class="fas fa-tasks"></i>
            </button>

            <button onclick="notify('Quick invite member', 'success');"
                    class="w-12 h-12 bg-purple-500 text-white rounded-full shadow-lg hover:shadow-purple-500/50 transition-all duration-300 flex items-center justify-center breathe"
                    style="animation-delay: 0.2s;"
                    title="Invite Member">
                <i class="fas fa-user-plus"></i>
            </button>
        </div>
    </div> --}}

    {{--  <!-- Keyboard Shortcuts Help -->
    <div class="fixed bottom-8 left-8 z-40" x-data="{ showHelp: false }">
        <button @click="showHelp = !showHelp"
                class="px-4 py-2 bg-white/90 backdrop-blur-sm text-gray-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2 border border-gray-200">
            <i class="fas fa-keyboard text-indigo-600"></i>
            <span class="text-sm font-medium">Shortcuts</span>
        </button>

        <div x-show="showHelp"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             @click.away="showHelp = false"
             class="absolute bottom-14 left-0 w-80 bg-white rounded-2xl shadow-2xl border border-indigo-100 p-6"
             style="display: none;">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-keyboard text-indigo-600 mr-2"></i>
                Keyboard Shortcuts
            </h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Search</span>
                    <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Ctrl + K</kbd>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">New Project</span>
                    <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Ctrl + N</kbd>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Close Modal</span>
                    <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Escape</kbd>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Toggle Theme</span>
                    <kbd class="px-2 py-1 bg-gray-100 rounded text-xs font-mono">Alt + T</kbd>
                </div>
            </div>
        </div>
    </div>  --}}

    <!-- Progress Indicator for Page Scroll -->
    <div class="fixed top-0 left-0 w-full h-1 bg-gray-200 z-[9998]">
        <div id="scroll-progress" class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-150" style="width: 0%"></div>
    </div>

<script>
function updateClock() {
  var now = new Date();
  var options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
  var dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
  document.querySelectorAll('.live-clock').forEach(function(el) {
    el.textContent = now.toLocaleTimeString('id-ID', options);
  });
  document.querySelectorAll('.live-date').forEach(function(el) {
    el.textContent = now.toLocaleDateString('id-ID', dateOptions);
  });
}
setInterval(updateClock, 1000); updateClock();
</script>
</body>
</html>
