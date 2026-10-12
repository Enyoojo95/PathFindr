<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — PathFindr</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        .auth-side {
            background: linear-gradient(160deg, #0a1540 0%, #1A2A6C 45%, #0d3320 100%);
            position: relative; overflow: hidden;
        }
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

        /* ── Step progress ── */
        .step-item {
            display: flex; align-items: center; gap: 16px;
            padding: 14px 18px; border-radius: 16px;
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            transition: all 0.4s ease;
        }
        .step-item.active {
            background: rgba(67,160,71,0.15);
            border-color: rgba(67,160,71,0.4);
            box-shadow: 0 4px 20px rgba(67,160,71,0.15);
        }
        .step-num {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 0.85rem; flex-shrink: 0;
            background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5);
            border: 2px solid rgba(255,255,255,0.15);
            transition: all 0.4s ease;
        }
        .step-item.active .step-num {
            background: #43A047; color: #fff; border-color: #43A047;
            box-shadow: 0 0 16px rgba(67,160,71,0.5);
        }
        .step-item.done .step-num {
            background: rgba(67,160,71,0.3); color: #43A047; border-color: #43A047;
        }
        .step-connector {
            width: 2px; height: 20px; margin-left: 35px;
            background: linear-gradient(to bottom, rgba(67,160,71,0.5), rgba(255,255,255,0.1));
        }

        /* ── Animated counter ── */
        .counter-wrap {
            display: flex; gap: 12px; justify-content: center;
        }
        .counter-card {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 16px; padding: 14px 20px;
            text-align: center; backdrop-filter: blur(12px);
            animation: floatCard var(--d,5s) ease-in-out infinite alternate;
            animation-delay: var(--dl,0s);
        }
        @keyframes floatCard {
            0%   { transform: translateY(0); }
            100% { transform: translateY(-10px); }
        }

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

        /* ── Typewriter ── */
        #tw-text::after {
            content: '|'; color: #F5A623;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0} }
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
        ];
        @endphp
        @foreach($particles as [$l,$t,$w,$h,$d,$dl,$tx,$ty])
        <div class="particle" style="left:{{$l}}%;top:{{$t}}%;width:{{$w}}px;height:{{$h}}px;--d:{{$d}};--dl:{{$dl}};--tx:{{$tx}};--ty:{{$ty}};"></div>
        @endforeach

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="relative z-10 w-fit">
            <img src="{{ asset('images/logo.png') }}" alt="PathFindr" class="h-10 w-auto drop-shadow-lg">
        </a>

        {{-- Central content --}}
        <div class="relative z-10 flex flex-col gap-6">

            {{-- Headline --}}
            <div>
                <div class="text-white font-extrabold text-2xl leading-tight mb-1">
                    <span id="tw-text"></span>
                </div>
                <div class="glow-line w-48 mt-3"></div>
            </div>

            {{-- Animated steps --}}
            <div class="flex flex-col gap-0" id="steps-wrap">
                <div class="step-item active" id="step-1">
                    <div class="step-num">1</div>
                    <div>
                        <div class="text-white font-semibold text-sm">Create your account</div>
                        <div class="text-white/40 text-xs mt-0.5">Name, email & school — takes 30 seconds</div>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-item" id="step-2">
                    <div class="step-num">2</div>
                    <div>
                        <div class="text-white/70 font-semibold text-sm">Take the assessment</div>
                        <div class="text-white/30 text-xs mt-0.5">30-minute psychometric test</div>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-item" id="step-3">
                    <div class="step-num">3</div>
                    <div>
                        <div class="text-white/70 font-semibold text-sm">Discover your path</div>
                        <div class="text-white/30 text-xs mt-0.5">AI-powered career & university match</div>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="counter-wrap">
                <div class="counter-card" style="--d:4s;--dl:0s;">
                    <div class="text-[#F5A623] font-extrabold text-xl leading-none">30</div>
                    <div class="text-white/40 text-[10px] mt-1 uppercase tracking-widest">Min Test</div>
                </div>
                <div class="counter-card" style="--d:5s;--dl:0.5s;">
                    <div class="text-[#43A047] font-extrabold text-xl leading-none">100%</div>
                    <div class="text-white/40 text-[10px] mt-1 uppercase tracking-widest">Free</div>
                </div>
                <div class="counter-card" style="--d:4.5s;--dl:1s;">
                    <div class="text-white font-extrabold text-xl leading-none">AI</div>
                    <div class="text-white/40 text-[10px] mt-1 uppercase tracking-widest">Powered</div>
                </div>
            </div>
        </div>

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
                <h1 class="text-2xl font-extrabold text-navy">Create Your Account</h1>
                <p class="text-dark/60 text-sm mt-1">Join PathFindr and discover your academic path</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <i class="fas fa-exclamation-circle"></i> Please fix the following:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-dark mb-1.5">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="e.g. Amara Okonkwo"
                               class="w-full pl-10 pr-4 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent @error('name') border-red-400 @enderror">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-dark mb-1.5">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="e.g. amara@email.com"
                               class="w-full pl-10 pr-4 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent @error('email') border-red-400 @enderror">
                    </div>
                </div>

                <div>
                    <label for="school_name" class="block text-sm font-medium text-dark mb-1.5">
                        School Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-school absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="text" id="school_name" name="school_name" value="{{ old('school_name') }}" required
                               placeholder="e.g. Crystalfield Academy"
                               class="w-full pl-10 pr-4 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent @error('school_name') border-red-400 @enderror">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-dark mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="password" id="password" name="password" required
                               placeholder="Min. 8 characters"
                               class="w-full pl-10 pr-10 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('password',this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-dark/40 hover:text-dark text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-dark mb-1.5">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-dark/40 text-sm"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="Repeat your password"
                               class="w-full pl-10 pr-10 py-3 bg-white border border-sky rounded-xl text-dark text-sm focus:outline-none focus:ring-2 focus:ring-navy focus:border-transparent">
                        <button type="button" onclick="togglePassword('password_confirmation',this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-dark/40 hover:text-dark text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                        class="w-full bg-green hover:bg-emerald-600 text-white font-semibold py-3.5 rounded-xl transition-all duration-200 text-base shadow-lg shadow-green/25 flex items-center justify-center gap-2 mt-2">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>
            </form>

            <p class="text-center text-sm text-dark/60 mt-6">
                Already have an account?
                <a href="{{ route('login') }}" class="text-navy font-semibold hover:text-gold transition-colors ml-1">Login here</a>
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
    const phrases = ['Your Journey Starts Here.', 'Discover Your Strengths.', 'Choose Your Future.', 'Science, Arts or Commerce?'];
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

    // Step cycling animation
    const steps = [1,2,3];
    let activeStep = 1;
    setInterval(() => {
        steps.forEach(i => {
            const el = document.getElementById('step-' + i);
            el.classList.remove('active','done');
            if (i < activeStep) el.classList.add('done');
            else if (i === activeStep) el.classList.add('active');
        });
        activeStep = activeStep >= 3 ? 1 : activeStep + 1;
    }, 2000);
</script>
</body>
</html>
