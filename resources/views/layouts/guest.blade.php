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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Capa de fondo con logos flotando */
        .background-logos {
            position: fixed;
            inset: 0;
            z-index: -10;
            pointer-events: none;
            overflow: hidden;
        }

        .logo {
            position: absolute;
            width: 150px;
            opacity: 0.05;
            /* Baja visibilidad para que no distraiga */
            animation: float 20s linear infinite;
        }

        /* Animación de logos flotando */
        @keyframes float {
            0% {
                transform: translateY(100vh) translateX(0) rotate(0deg);
            }

            100% {
                transform: translateY(-20vh) translateX(50px) rotate(360deg);
            }
        }
    </style>
</head>

<body
    class="font-sans text-gray-900 antialiased min-h-screen flex justify-center items-center bg-gray-100 dark:bg-gray-900">

    <!-- Fondo de logos animados -->
    <div class="background-logos">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:5%; animation-duration:25s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:15%; animation-duration:22s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:25%; animation-duration:30s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:35%; animation-duration:27s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:45%; animation-duration:35s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:55%; animation-duration:28s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:65%; animation-duration:32s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:75%; animation-duration:30s;">
        <img src="{{ asset('images/logo.png') }}" class="logo" style="left:85%; animation-duration:33s;">
    </div>


    <!-- Tarjeta central con blur y sombra -->
    <div class="w-full sm:max-w-md px-6 py-8 bg-white/70 backdrop-blur-md shadow-2xl rounded-3xl">
        {{ $slot }}
    </div>

</body>
<div class="background-logos" id="logos-container"></div>

<style>
.background-logos {
    position: fixed;
    inset: 0;
    z-index: -10;
    pointer-events: none;
}

.logo {
    position: absolute;
    opacity: 0.05;
    width: 80px;
    will-change: transform;
    transition: transform 0.1s linear;
}
</style>

<script>
const container = document.getElementById('logos-container');
const logoCount = 15;

for (let i = 0; i < logoCount; i++) {
    const img = document.createElement('img');
    img.src = '{{ asset("images/logo.png") }}';
    img.className = 'logo';

    // Tamaño aleatorio
    img.style.width = 50 + Math.random() * 100 + 'px';

    // Posición inicial aleatoria (top, bottom, left, right)
    const sides = ['top', 'bottom', 'left', 'right'];
    const side = sides[Math.floor(Math.random() * sides.length)];

    switch(side) {
        case 'top':
            img.style.top = '-100px';
            img.style.left = Math.random() * 100 + 'vw';
            break;
        case 'bottom':
            img.style.bottom = '-100px';
            img.style.left = Math.random() * 100 + 'vw';
            break;
        case 'left':
            img.style.left = '-100px';
            img.style.top = Math.random() * 100 + 'vh';
            break;
        case 'right':
            img.style.right = '-100px';
            img.style.top = Math.random() * 100 + 'vh';
            break;
    }

    container.appendChild(img);
    animateLogo(img, side);
}

// Función para animar el logo de forma random
function animateLogo(logo, side) {
    const duration = 20 + Math.random() * 20; // 20-40s

    let keyframes;
    switch(side) {
        case 'top':
            keyframes = [
                { transform: `translateY(0px)` },
                { transform: `translateY(110vh) rotate(${Math.random()*360}deg)` }
            ];
            break;
        case 'bottom':
            keyframes = [
                { transform: `translateY(0px)` },
                { transform: `translateY(-110vh) rotate(${Math.random()*360}deg)` }
            ];
            break;
        case 'left':
            keyframes = [
                { transform: `translateX(0px)` },
                { transform: `translateX(110vw) rotate(${Math.random()*360}deg)` }
            ];
            break;
        case 'right':
            keyframes = [
                { transform: `translateX(0px)` },
                { transform: `translateX(-110vw) rotate(${Math.random()*360}deg)` }
            ];
            break;
    }

    logo.animate(keyframes, {
        duration: duration * 1000,
        iterations: Infinity,
        direction: 'alternate',
        easing: 'ease-in-out'
    });
}
</script>


</html>