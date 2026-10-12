<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PathFindr Master')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; font-family: 'Inter', sans-serif; }

        /* ── Background ── */
        body {
            background: #0B1124;
            background-image:
                radial-gradient(ellipse 80% 60% at 20% 10%, rgba(37,99,235,0.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 80%, rgba(124,58,237,0.14) 0%, transparent 55%);
            min-height: 100vh;
            color: #fff;
            overflow: hidden;
        }

        /* Animated grid overlay */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* ── Real Glass ── */
        .glass {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.12);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.08);
            border-radius: 20px;
        }
        .glass-sm {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 4px 16px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.06);
            border-radius: 14px;
        }
        .glass-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.10);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.07);
            border-radius: 20px;
            transition: all 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-2px) scale(1.01);
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.18);
            box-shadow: 0 12px 40px rgba(0,0,0,0.5), 0 0 20px rgba(37,99,235,0.12), inset 0 1px 0 rgba(255,255,255,0.10);
        }

        /* ── Inputs ── */
        .glass-input {
            width: 100%;
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 12px;
            padding: 11px 16px;
            color: #fff;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.25s ease;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.3); }
        .glass-input:focus {
            border-color: rgba(37,99,235,0.7);
            background: rgba(255,255,255,0.08);
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15), 0 4px 12px rgba(0,0,0,0.3);
        }

        /* ── Buttons ── */
        .glass-btn {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 10px 20px;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .glass-btn:hover {
            background: rgba(255,255,255,0.14);
            border-color: rgba(255,255,255,0.25);
            box-shadow: 0 4px 20px rgba(0,0,0,0.4), 0 0 16px rgba(37,99,235,0.2);
            transform: scale(1.02);
        }
        .glass-btn-primary {
            background: rgba(37,99,235,0.3);
            border-color: rgba(37,99,235,0.5);
        }
        .glass-btn-primary:hover {
            background: rgba(37,99,235,0.5);
            box-shadow: 0 4px 20px rgba(0,0,0,0.4), 0 0 20px rgba(37,99,235,0.35);
        }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,0.5); }

        /* ── Sidebar nav link ── */
        .sa-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 13px; border-radius: 12px;
            font-size: 0.82rem; font-weight: 500;
            color: rgba(255,255,255,0.45);
            transition: all 0.22s ease;
            position: relative; text-decoration: none;
            border: 1px solid transparent;
        }
        .sa-link:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.10);
            color: rgba(255,255,255,0.9);
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
            transform: translateX(2px);
        }
        .sa-link.active {
            background: rgba(37,99,235,0.2);
            border-color: rgba(37,99,235,0.35);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 16px rgba(37,99,235,0.2), inset 0 1px 0 rgba(255,255,255,0.08);
        }
        .sa-link .sa-icon { width: 17px; text-align: center; font-size: 0.78rem; opacity: 0.7; }
        .sa-link.active .sa-icon, .sa-link:hover .sa-icon { opacity: 1; }

        /* ── Table ── */
        .glass-table th {
            padding: 11px 16px; font-size: 0.68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.06em;
            color: rgba(255,255,255,0.3);
            background: rgba(255,255,255,0.03);
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        .glass-table td {
            padding: 13px 16px; font-size: 0.82rem;
            color: rgba(255,255,255,0.72);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            transition: background 0.15s;
        }
        .glass-table tr:hover td { background: rgba(255,255,255,0.04); }
        .glass-table tr:last-child td { border-bottom: none; }

        /* ── Badge ── */
        .glass-badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 99px;
            font-size: 0.7rem; font-weight: 600;
            backdrop-filter: blur(8px);
            border: 1px solid;
        }

        /* ── Sidebar overlay (mobile) ── */
        #sa-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); z-index:40; backdrop-filter:blur(3px); }
        #sa-overlay.show { display:block; }

        /* Sidebar mobile slide */
        #sa-sidebar { transform: translateX(-100%); transition: transform 0.28s cubic-bezier(0.4,0,0.2,1); }
        #sa-sidebar.open { transform: translateX(0); }
        @media(min-width:1024px) { #sa-sidebar { transform: translateX(0) !important; position: relative !important; } }

        html { min-height: 100%; scroll-behavior: smooth; }
        body { min-height: 100vh; min-height: 100dvh; overflow-x: hidden; }
        button, a, input, select, textarea { touch-action: manipulation; }
        button, [role="button"], input, select, textarea { max-width: 100%; }
        img, video, canvas, svg { max-width: 100%; }
        main, section, article, form, fieldset, .flex-1 { min-width: 0; }
        main table { max-width: 100%; }
        main :where(input:not([type="checkbox"]):not([type="radio"]):not([type="range"]), select, textarea) { min-height: 44px; }
        main :where(button, [type="button"], [type="submit"], [type="reset"]) { min-height: 44px; }
        #sa-sidebar { width: min(220px, calc(100vw - 2.5rem)); padding-top: env(safe-area-inset-top); padding-bottom: env(safe-area-inset-bottom); }
        @media(max-width: 640px) {
            .flex.h-full.overflow-hidden { min-height: 100vh; min-height: 100dvh; height: 100vh; height: 100dvh; }
            main.flex-1.overflow-y-auto { padding-left: max(.75rem, env(safe-area-inset-left)); padding-right: max(.75rem, env(safe-area-inset-right)); padding-bottom: max(1rem, env(safe-area-inset-bottom)); overscroll-behavior-y: contain; }
            main :where(.overflow-x-auto) { -webkit-overflow-scrolling: touch; }
            main :where(h1, h2, h3, p, a, button, label, td, th) { overflow-wrap: anywhere; }
            header { padding-left: max(.75rem, env(safe-area-inset-left)); padding-right: max(.75rem, env(safe-area-inset-right)); padding-top: env(safe-area-inset-top); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        /* ── Progress bar ── */
        .glass-progress-track {
            height: 6px; border-radius: 99px;
            background: rgba(255,255,255,0.07);
            overflow: hidden;
        }
        .glass-progress-fill {
            height: 100%; border-radius: 99px;
            transition: width 1s ease;
        }

        /* ── Modal ── */
        .glass-modal-bg {
            position: fixed; inset: 0; z-index: 60;
            background: rgba(0,0,0,0.65);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center; padding: 16px;
        }

        /* ── Page load animation ── */
        .page-enter {
            animation: pageEnter 0.35s ease forwards;
        }
        @keyframes pageEnter {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
    @yield('styles')
</head>
<body class="h-full">

<div id="sa-overlay" onclick="closeSA()"></div>

{{-- ══ WELCOME ANIMATION ══ --}}
@if(session('sa_show_welcome'))
<div id="welcome-overlay"
     style="position:fixed; inset:0; z-index:200;
            background: radial-gradient(ellipse at center, rgba(37,99,235,0.35) 0%, #0B1124 70%);
            backdrop-filter: blur(4px);
            display:flex; flex-direction:column; align-items:center; justify-content:center;
            transition: opacity 0.8s ease;">
    <div style="text-align:center;">
        <div id="welcome-greeting"
             style="font-size:clamp(1.2rem,3vw,1.5rem); color:rgba(255,255,255,0.5);
                    font-weight:500; margin-bottom:12px; letter-spacing:0.05em;"></div>
        <div style="font-size:clamp(2.2rem,6vw,4rem); font-weight:900; color:#fff;
                    letter-spacing:-0.02em; line-height:1.1; margin-bottom:8px;">
            Welcome, Austin-Great
        </div>
        <div id="welcome-sub"
             style="font-size:clamp(1rem,2.5vw,1.2rem); color:rgba(255,255,255,0.45); font-weight:400;">
        </div>
    </div>
    <div style="margin-top:40px; display:flex; gap:8px;">
        <span style="width:8px;height:8px;border-radius:50%;background:rgba(37,99,235,0.8);animation:dot 1.4s ease infinite 0s;display:inline-block;"></span>
        <span style="width:8px;height:8px;border-radius:50%;background:rgba(37,99,235,0.8);animation:dot 1.4s ease infinite 0.2s;display:inline-block;"></span>
        <span style="width:8px;height:8px;border-radius:50%;background:rgba(37,99,235,0.8);animation:dot 1.4s ease infinite 0.4s;display:inline-block;"></span>
    </div>
    <style>
        @keyframes dot { 0%,80%,100%{transform:scale(0.5);opacity:0.4} 40%{transform:scale(1);opacity:1} }
    </style>
</div>
<script>
(function(){
    var h = new Date().getHours();
    var greeting, sub;
    if(h >= 5 && h < 12)  { greeting = "Good morning"; sub = "How's your morning going? ☀️"; }
    else if(h >= 12 && h < 17){ greeting = "Good afternoon"; sub = "Hope your afternoon is productive 💪"; }
    else if(h >= 17 && h < 21){ greeting = "Good evening"; sub = "Time to review and unwind 🌙"; }
    else { greeting = "Still up? Good night"; sub = "Don't forget to rest 🌌"; }
    document.getElementById('welcome-greeting').textContent = greeting + ', Austin-Great';
    document.getElementById('welcome-sub').textContent = sub;
    setTimeout(function(){
        var el = document.getElementById('welcome-overlay');
        el.style.opacity = '0';
        setTimeout(function(){ el.remove(); }, 800);
    }, 3000);
})();
</script>
@php session()->forget('sa_show_welcome'); @endphp
@endif

<div class="flex h-full overflow-hidden" style="position:relative; z-index:1;">

    {{-- ══ SIDEBAR ══ --}}
    <aside id="sa-sidebar"
           class="fixed inset-y-0 left-0 w-[220px] flex flex-col z-50 lg:relative lg:inset-auto lg:flex-shrink-0"
           style="background: rgba(11,17,36,0.85); backdrop-filter: blur(28px); -webkit-backdrop-filter: blur(28px);
                  border-right: 1px solid rgba(255,255,255,0.07);">

        {{-- Logo --}}
        <div class="flex items-center justify-between px-4 py-4 shrink-0"
             style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="PathFindr" style="height:32px;width:auto;">
                <div>
                    <div style="color:#fff;font-weight:800;font-size:0.9rem;line-height:1;">PathFindr</div>
                    <div style="color:rgba(37,99,235,0.9);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;">Master</div>
                </div>
            </div>
            <button onclick="closeSA()" class="lg:hidden" style="color:rgba(255,255,255,0.4);font-size:0.9rem;background:none;border:none;cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Avatar --}}
        <div class="px-4 py-3 shrink-0" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            @php $sa = auth('super_admin')->user(); @endphp
            <div class="flex items-center gap-2.5">
                <img src="{{ $sa->avatarUrl() }}" alt="{{ $sa->name }}"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid rgba(37,99,235,0.4);">
                <div style="min-width:0;">
                    <div style="color:#fff;font-size:0.78rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $sa->name }}</div>
                    <div style="color:rgba(255,255,255,0.3);font-size:0.65rem;display:flex;align-items:center;gap:4px;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#10B981;display:inline-block;"></span> Online
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-2.5 py-3 space-y-0.5">
            @php $r = request()->route()?->getName() ?? ''; @endphp
            <p style="color:rgba(255,255,255,0.18);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:8px 10px 4px;">Main</p>

            <a href="{{ route('super-admin.dashboard') }}"  class="sa-link {{ $r==='super-admin.dashboard' ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-gauge-high"></i></span> Overview
            </a>

            <p style="color:rgba(255,255,255,0.18);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:12px 10px 4px;">Manage</p>
            <a href="{{ route('super-admin.schools.index') }}" class="sa-link {{ str_contains($r,'schools') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-building"></i></span> Schools
            </a>
            <a href="{{ route('super-admin.students.index') }}" class="sa-link {{ str_contains($r,'students') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-users"></i></span> Students
            </a>
            <a href="{{ route('super-admin.admins.index') }}" class="sa-link {{ str_contains($r,'admins') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-user-shield"></i></span> Admins
            </a>
            <a href="{{ route('super-admin.questions.index') }}" class="sa-link {{ str_contains($r,'questions') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-list"></i></span> Questions
            </a>

            <p style="color:rgba(255,255,255,0.18);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:12px 10px 4px;">Insights</p>
            <a href="{{ route('super-admin.analytics.index') }}" class="sa-link {{ str_contains($r,'analytics') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-chart-line"></i></span> Analytics
            </a>
            <a href="{{ route('super-admin.testimonials.index') }}" class="sa-link {{ str_contains($r,'testimonials') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-comment-dots"></i></span> Testimonials
                @php $pendingCount = \App\Models\Testimonial::where('approved', false)->count(); @endphp
                @if($pendingCount > 0)
                <span style="margin-left:auto;background:rgba(245,158,11,0.25);border:1px solid rgba(245,158,11,0.4);
                             color:#FDE68A;font-size:0.62rem;font-weight:800;
                             padding:2px 7px;border-radius:99px;">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('super-admin.logs.index') }}" class="sa-link {{ str_contains($r,'logs') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-clock-rotate-left"></i></span> Logs
            </a>

            <p style="color:rgba(255,255,255,0.18);font-size:0.6rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;padding:12px 10px 4px;">System</p>
            <a href="{{ route('super-admin.settings.index') }}" class="sa-link {{ str_contains($r,'settings') ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-sliders"></i></span> Settings
            </a>
            <a href="{{ route('super-admin.settings.index') }}#maintenance" class="sa-link">
                <span class="sa-icon"><i class="fas fa-shield-halved"></i></span> Maintenance
            </a>
        </nav>

        {{-- Bottom: Profile + Logout --}}
        <div class="px-2.5 py-3 shrink-0 space-y-0.5" style="border-top:1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('super-admin.profile') }}" class="sa-link {{ $r==='super-admin.profile' ? 'active' : '' }}">
                <span class="sa-icon"><i class="fas fa-user"></i></span> Profile
            </a>
            <form method="POST" action="{{ route('super-admin.logout') }}">
                @csrf
                <button type="submit" class="sa-link w-full" style="background:none;border:none;cursor:pointer;color:rgba(255,100,100,0.6);" onmouseover="this.style.color='rgba(255,100,100,0.9)';this.style.background='rgba(239,68,68,0.1)';" onmouseout="this.style.color='rgba(255,100,100,0.6)';this.style.background='none';">
                    <span class="sa-icon"><i class="fas fa-right-from-bracket"></i></span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ══ MAIN ══ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="shrink-0 flex items-center gap-3 px-5 h-14"
                style="background:rgba(11,17,36,0.6); backdrop-filter:blur(20px); -webkit-backdrop-filter:blur(20px);
                       border-bottom:1px solid rgba(255,255,255,0.06);">
            <button onclick="openSA()" style="display:none;" id="mobile-menu-btn"
                    class="lg:hidden w-8 h-8 rounded-xl flex items-center justify-center glass-btn" style="padding:0;">
                <i class="fas fa-bars" style="font-size:0.85rem;"></i>
            </button>
            <script>
                if(window.innerWidth < 1024) document.getElementById('mobile-menu-btn').style.display='flex';
            </script>
            <div style="flex:1; min-width:0;">
                <h1 style="color:#fff;font-weight:700;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    @yield('page-title', 'Overview Dashboard')
                </h1>
            </div>
            <div style="display:flex;align-items:center;gap:8px;color:rgba(255,255,255,0.35);font-size:0.75rem;">
                <i class="fas fa-clock" style="font-size:0.7rem;"></i>
                <span id="sa-clock"></span>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success') || session('error'))
        <div class="px-5 pt-4">
            @if(session('success'))
            <div class="glass-sm flex items-center gap-3 px-4 py-3 text-sm"
                 style="border-color:rgba(16,185,129,0.3); color:#6EE7B7;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="glass-sm flex items-center gap-3 px-4 py-3 text-sm"
                 style="border-color:rgba(239,68,68,0.3); color:#FCA5A5;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Scrollable content --}}
        <main class="flex-1 overflow-y-auto p-5 sm:p-6 page-enter">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function openSA()  { document.getElementById('sa-sidebar').classList.add('open'); document.getElementById('sa-overlay').classList.add('show'); document.body.style.overflow='hidden'; }
    function closeSA() { document.getElementById('sa-sidebar').classList.remove('open'); document.getElementById('sa-overlay').classList.remove('show'); document.body.style.overflow=''; }
    document.addEventListener('keydown', e => { if(e.key==='Escape') closeSA(); });

    // Live clock
    function updateClock() {
        var d = new Date();
        document.getElementById('sa-clock').textContent = d.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}) + ' — ' + d.toLocaleDateString([], {weekday:'short',month:'short',day:'numeric'});
    }
    updateClock();
    setInterval(updateClock, 30000);
</script>

@yield('scripts')
</body>
</html>
