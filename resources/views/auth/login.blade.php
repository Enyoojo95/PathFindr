<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PathFindr</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* ── Side panel ── */
        .auth-side {
            background: linear-gradient(160deg, #0a1540 0%, #1A2A6C 45%, #0d3320 100%);
            position: relative; overflow: hidden;
        }

        /* ── Animated mesh gradient ── */
        .mesh {
            position: absolute; inset: 0; z-index: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(45,95,158,0.55) 0%, transparent 70%),
                radial-gradient(ellipse 50% 60% at 80% 80%, rgba(67,160,71,0.4) 0%, transparent 70%),
                radial-gradient(ellipse 40% 40% at 60% 10%, rgba(245,166,35,0.2) 0%, transparent 70%);
            animation: meshShift 10s ease-in-out infinite alternate;
        }
        @keyframes meshShift {
            0%   { filter: hue-rotate(0deg) brightness(1); }
            100% { filter: hue-rotate(15deg) brightness(1.1); }
        }

        /* ── Floating particles ── */
        .particle {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.6);
            animation: particleDrift var(--d,6s) ease-in-out infinite alternate;
            animation-delay: var(--dl,0s);
        }
        @keyframes particleDrift {
            0%   { transform: translate(0,0) scale(1); opacity: 0.2; }
            100% { transform: translate(var(--tx,10px), var(--ty,-20px)) scale(1.5); opacity: 0.8; }
        }

        /* ── Central compass ── */
        .compass-wrap {
            position: relative; width: 200px; height: 200px; margin: 0 auto;
        }
        .compass-ring {
            position: absolute; inset: 0; border-radius: 50%;
            border: 2px dashed rgba(255,255,255,0.15);
            animation: spinRing var(--spd, 20s) linear infinite;
        }
        .compass-ring.rev { animation-direction: reverse; }
        @keyframes spinRing { to { transform: rotate(360deg); } }

        .compass-center {
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .compass-logo {
            width: 90px; height: 90px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 28px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.15);
            animation: logoPulse 4s ease-in-out infinite;
        }
        @keyframes logoPulse {
            0%,100% { box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 0 0 0 rgba(67,160,71,0); }
            50%      { box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 0 0 16px rgba(67,160,71,0.08); }
        }

        /* ── Orbit icons ── */
        .orbit-dot {
            position: absolute; width: 40px; height: 40px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.9rem;
            /* counter-rotate so icon stays upright */
            animation: counterSpin var(--spd,20s) linear infinite;
        }
        .compass-ring.rev .orbit-dot { animation-direction: reverse; }
        @keyframes counterSpin { to { transform: rotate(-360deg); } }

        /* positions on outer ring */
        .od-top    { top: -20px; left: 50%; transform: translateX(-50%); }
        .od-right  { right: -20px; top: 50%; transform: translateY(-50%); }
        .od-bottom { bottom: -20px; left: 50%; transform: translateX(-50%); }
        .od-left   { left: -20px; top: 50%; transform: translateY(-50%); }

        /* ── Floating stat cards ── */
        .stat-card {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 16px; padding: 12px 18px;
            animation: floatCard var(--d,5s) ease-in-out infinite alternate;
            animation-delay: var(--dl,0s);
        }
        @keyframes floatCard {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

        /* ── Animated path line ── */
        .path-svg path {
            stroke-dasharray: 400;
            stroke-dashoffset: 400;
            animation: drawPath 3s ease forwards 0.5s;
        }
        @keyframes drawPath { to { stroke-dashoffset: 0; } }

        /* ── Typewriter ── */
        #tw-text::after {
            content: '|'; color: #F5A623;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }

        /* ── Glow line ── */
        .glow-line {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(67,160,71,0.6), rgba(245,166,35,0.6), transparent);
            animation: glowMove 4s ease-in-out infinite;
        }
        @keyframes glowMove {
            0%,100% { opacity: 0.4; transform: scaleX(0.6); }
            50%      { opacity: 1;   transform: scaleX(1); }
        }
        html { min-height:100%; scroll-behavior:smooth; }
        body { min-height:100vh; min-height:100dvh; overflow-x:hidden; }
        button, a, input, select, textarea { touch-action:manipulation; max-width:100%; }
        img, svg { max-width:100%; }
        .flex-1, form, form > div { min-width:0; }
        input:not([type="checkbox"]):not([type="radio"]), select, textarea { min-height:44px; }
        button, [type="submit"] { min-height:44px; }
        @media(max-width:640px) {
            body > div { min-height:100vh; min-height:100dvh; }
            body > div > div:last-child { padding-top:max(1.5rem,env(safe-area-inset-top)); padding-bottom:max(1.5rem,env(safe-area-inset-bottom)); }
            .auth-side { display:none; }
        }
        @media(max-width:380px) {
            body > div > div:last-child { padding-left:max(1rem,env(safe-area-inset-left)); padding-right:max(1rem,env(safe-area-inset-right)); }
            input:not([type="checkbox"]):not([type="radio"]), select, textarea { font-size:16px !important; }
        }
        @media(prefers-reduced-motion:reduce) {
            *,*::before,*::after { scroll-behavior:auto!important; animation-duration:.01ms!important; animation-iteration-count:1!important; transition-duration:.01ms!important; }
        }
    </style>
</head>
<body class="bg-sky min-h-screen">
<div class="min-h-screen flex">

    {{-- ══ LEFT PANEL ══ --}}
    <div class="auth-side hidden lg:flex lg:w-5/12 flex-col justify-between p-10 relative">
        <div class="mesh"></div>

        {{-- Particles --}}
        @php
        $particles = [
            [10,15,3,3,'7s','0s','8px','-25px'],[20,70,2,2,'5s','1s','12px','-18px'],
            [70,20,4,4,'8s','0.5s','-10px','-30px'],[85,65,2,2,'6s','1.5s','15px','-20px'],
            [50,45,3,3,'9s','0.3s','-8px','-15px'],[35,85,2,2,'5s','2s','10px','-25px'],
            [60,10,3,3,'7s','0.8s','-12px','-20px'],[90,40,2,2,'6s','0.2s','8px','-18px'],
            [15,55,4,4,'8s','1.2s','14px','-22px'],[78,88,2,2,'5s','0.7s','-9px','-16px'],
        ];
        @endphp
        @foreach($particles as [$l,$t,$w,$h,$d,$dl,$tx,$ty])
        <div class="particle" style="left:{{$l}}%;top:{{$t}}%;width:{{$w}}px;height:{{$h}}px;--d:{{$d}};--dl:{{$dl}};--tx:{{$tx}};--ty:{{$ty}};"></div>
        @endforeach

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="relative z-10 w-fit">
            <img src="{{ asset('images/logo.png') }}" alt="PathFindr" class="h-10 w-auto drop-shadow-lg">
        </a>

        {{-- Central animation --}}
        <div class="relative z-10 flex flex-col items-center gap-8">

            {{-- Compass orbit system --}}
            <div class="compass-wrap">
                {{-- Outer ring — CW --}}
                <div class="compass-ring" style="--spd:22s; inset:-30px;">
                    <div class="orbit-dot od-top"    style="--spd:22s;"><i class="fas fa-graduation-cap text-[#F5A623]"></i></div>
                    <div class="orbit-dot od-right"  style="--spd:22s;"><i class="fas fa-briefcase text-[#43A047]"></i></div>
                    <div class="orbit-dot od-bottom" style="--spd:22s;"><i class="fas fa-university text-white/80"></i></div>
                    <div class="orbit-dot od-left"   style="--spd:22s;"><i class="fas fa-flask text-[#EBF4FF]"></i></div>
                </div>
                {{-- Inner ring — CCW --}}
                <div class="compass-ring rev" style="--spd:14s; inset:20px;">
                    <div class="orbit-dot od-top"    style="--spd:14s;"><i class="fas fa-calculator text-[#F5A623]"></i></div>
                    <div class="orbit-dot od-bottom" style="--spd:14s;"><i class="fas fa-chart-line text-[#43A047]"></i></div>
                </div>
                {{-- Center --}}
                <div class="compass-center">
                    <div class="compass-logo">
                        <img src="{{ asset('images/logo.png') }}" alt="PathFindr" class="w-16 h-auto">
                    </div>
                </div>
            </div>

            {{-- Animated SVG path --}}
            <svg class="path-svg w-52 opacity-80" viewBox="0 0 260 36" fill="none">
                <path d="M0 18 Q65 4 130 18 Q195 32 260 18"
                      stroke="url(#pg)" stroke-width="2.5" stroke-linecap="round"/>
                <defs>
                    <linearGradient id="pg" x1="0" y1="0" x2="260" y2="0" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#1A2A6C"/>
                        <stop offset="50%" stop-color="#43A047"/>
                        <stop offset="100%" stop-color="#F5A623"/>
                    </linearGradient>
                </defs>
            </svg>

            {{-- Typewriter headline --}}
            <div class="text-center">
                <div class="text-white font-extrabold text-2xl leading-tight tracking-tight mb-1">
                    <span id="tw-text"></span>
                </div>
                <div class="glow-line w-48 mx-auto mt-3"></div>
            </div>

            {{-- Floating stat cards --}}
            <div class="flex gap-3">
                <div class="stat-card text-center" style="--d:4s;--dl:0s;">
                    <div class="text-[#F5A623] font-extrabold text-xl leading-none">30</div>
                    <div class="text-white/50 text-[10px] mt-1 uppercase tracking-widest">Questions</div>
                </div>
                <div class="stat-card text-center" style="--d:5s;--dl:0.6s;">
                    <div class="text-[#43A047] font-extrabold text-xl leading-none">3</div>
                    <div class="text-white/50 text-[10px] mt-1 uppercase tracking-widest">Paths</div>
                </div>
                <div class="stat-card text-center" style="--d:4.5s;--dl:1.2s;">
                    <div class="text-white font-extrabold text-xl leading-none">AI</div>
                    <div class="text-white/50 text-[10px] mt-1 uppercase tracking-widest">Powered</div>
                </div>
            </div>
        </div>

        {{-- Footer tagline --}}
        <p class="text-white/25 text-xs relative z-10 text-center">
            &copy; {{ date('Y') }} PathFindr &mdash; Know Your Strengths. Choose Your Future.
        </p>
    </div>

    {{-- ══ RIGHT PANEL — Form ══ --}}
    <div class="flex-1 flex flex-col justify-center px-6 sm:px-10 lg:px-16 py-10 overflow-y-auto">

        <div class="lg:hidden mb-8 text-center">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/logo.png') }}" alt="PathFindr" class="h-10 w-auto mx-auto">
            </a>
        </div>

        <div class="w-full max-w-md mx-auto">
            <div class="mb-8">
                <h1 class="text-2xl font-extrabold text-navy">Login to Your Account</h1>
                <p class="text-dark/60 text-sm mt-1">Enter your credentials to continue</p>
            </div>

            @if (session('status'))
                <div class="bg-green/10 border border-green/30 text-green rounded-xl px-4 py-3 mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <i class="fas fa-exclamation-circle"></i> Login failed:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-dark mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="e.g. amara@email.com"
                               class="w-full pl-10 pr-4 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent @error('email') border-red-400 @enderror">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-dark">
                            Password <span class="text-red-500">*</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-navy hover:text-gold transition-colors font-medium">Forgot password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="password" id="password" name="password" required placeholder="Enter your password"
                               class="w-full pl-10 pr-10 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('password',this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-dark/40 hover:text-dark text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="remember_me" name="remember"
                           class="w-4 h-4 rounded border-sky text-navy focus:ring-navy cursor-pointer">
                    <label for="remember_me" class="text-sm text-dark/70 cursor-pointer select-none">
                        Remember me for 30 days
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-green hover:bg-emerald-600 text-white font-semibold py-3.5 rounded-xl transition-all duration-200 text-base shadow-lg shadow-green/25 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>

            <p class="text-center text-sm text-dark/60 mt-6">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-navy font-semibold hover:text-gold transition-colors ml-1">Register free</a>
            </p>
            <p class="text-center mt-4">
                <a href="{{ url('/') }}" class="text-xs text-dark/40 hover:text-navy transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Back to PathFindr Home
                </a>
            </p>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        field.type  = field.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }

    // Typewriter
    const phrases = ['Welcome Back!', 'Find Your Path.', 'Know Your Strengths.', 'Choose Your Future.'];
    let pi = 0, ci = 0, deleting = false;
    const el = document.getElementById('tw-text');
    function typewrite() {
        const phrase = phrases[pi];
        if (!deleting) {
            el.textContent = phrase.slice(0, ++ci);
            if (ci === phrase.length) { deleting = true; setTimeout(typewrite, 1800); return; }
        } else {
            el.textContent = phrase.slice(0, --ci);
            if (ci === 0) { deleting = false; pi = (pi + 1) % phrases.length; setTimeout(typewrite, 300); return; }
        }
        setTimeout(typewrite, deleting ? 45 : 80);
    }
    setTimeout(typewrite, 800);
</script>
</body>
</html>
