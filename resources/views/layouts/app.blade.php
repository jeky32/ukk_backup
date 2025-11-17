<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Font Awesome - TAMBAHAN BARU -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

        <!-- Animate.css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Canvas Confetti JS -->
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>

        <script>
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

                    // Confetti dari kiri
                    confetti(Object.assign({}, defaults, {
                        particleCount,
                        origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 }
                    }));

                    // Confetti dari kanan
                    confetti(Object.assign({}, defaults, {
                        particleCount,
                        origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 }
                    }));
                }, 250);
            }

            // Success Notification with Confetti Animation
            @if(session('success'))
                // Launch confetti immediately
                launchConfetti();

                // Show SweetAlert2 with animation
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: '🎉 Selamat!',
                        html: '<div style="font-size: 16px; color: #374151; line-height: 1.6;">{{ session('success') }}</div>',
                        showConfirmButton: true,
                        confirmButtonText: 'Mulai Sekarang',
                        confirmButtonColor: '#667eea',
                        timer: 5000,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        showClass: {
                            popup: 'animate__animated animate__bounceIn animate__faster'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOut'
                        },
                        didOpen: () => {
                            // Additional confetti burst when modal opens
                            confetti({
                                particleCount: 100,
                                spread: 70,
                                origin: { y: 0.6 }
                            });
                        }
                    });
                }, 500);
            @endif

            // Error Notification with Shake Animation
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Tutup',
                    confirmButtonColor: '#ef4444',
                    showClass: {
                        popup: 'animate__animated animate__shakeX'
                    }
                });
            @endif

            // Info/Warning Notification
            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3b82f6',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    }
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#f59e0b',
                    showClass: {
                        popup: 'animate__animated animate__tada'
                    }
                });
            @endif
        </script>
    </body>
</html>
