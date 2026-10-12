<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PathFindr — Know Your Strengths. Choose Your Future.')</title>

    <!-- FontAwesome 6 Free CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <x-dark-mode />

    <style>
        /* Global scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1A2A6C;
        }
        ::-webkit-scrollbar-thumb {
            background: #43A047;
            border-radius: 99px;
            border: 2px solid #1A2A6C;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #2e7d32;
        }
        ::-webkit-scrollbar-corner {
            background: #1A2A6C;
        }
        html { min-height: 100%; scroll-behavior: smooth; }
        body { min-height: 100vh; min-height: 100dvh; overflow-x: hidden; }
        button, a, input, select, textarea { touch-action: manipulation; }
        button, [role="button"], input, select, textarea { max-width: 100%; }
        img, video, canvas, svg { max-width: 100%; }
        main, section, article, form, fieldset, .flex-1 { min-width: 0; }
        main table { max-width: 100%; }
        main :where(input:not([type="checkbox"]):not([type="radio"]):not([type="range"]), select, textarea) { min-height: 44px; }
        main :where(button, [type="button"], [type="submit"], [type="reset"]) { min-height: 44px; }
        @media (max-width: 640px) {
            body > main { min-height: 60vh; }
            main :where(.overflow-x-auto) { -webkit-overflow-scrolling: touch; }
            main :where(h1, h2, h3, p, a, button, label, td, th) { overflow-wrap: anywhere; }
            nav { padding-left: max(0px, env(safe-area-inset-left)); padding-right: max(0px, env(safe-area-inset-right)); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>

    @yield('styles')
</head>
<body class="font-sans antialiased bg-white text-[#2D3748]">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    @include('components.footer')

    @yield('scripts')
    <x-toast />
</body>
</html>
