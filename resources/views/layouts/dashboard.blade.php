<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PathFindr')</title>
    @auth
        @if(auth()->user()->hasAdminAccess())
            <link rel="icon" type="image/png" href="{{ asset('Admin Compass Navigation Icon.png') }}">
        @else
            <link rel="icon" type="image/png" href="{{ asset('User dashboard favico icon.png') }}">
        @endif
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @endauth
    @auth
        @if(auth()->user()->hasAdminAccess())
            <link rel="icon" type="image/png" href="{{ asset('Admin Compass Navigation Icon.png') }}">
        @else
            <link rel="icon" type="image/png" href="{{ asset('User dashboard favico icon.png') }}">
        @endif
    @else
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @endauth
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <x-dark-mode />
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1A2A6C; }
        ::-webkit-scrollbar-thumb { background: #43A047; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #2e7d32; }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px; border-radius: 12px;
            font-size: 0.875rem; font-weight: 500;
            color: rgba(255,255,255,0.82);
            transition: all 0.18s ease;
            position: relative;
        }
        .sidebar-link:hover  { background: rgba(255,255,255,0.12); color: #fff; }
        .sidebar-link.active { background: rgba(67,160,71,0.22); color: #fff; font-weight: 600; box-shadow: inset 0 0 0 1px rgba(67,160,71,0.3); }
        .sidebar-link.active::before {
            content: ''; position: absolute; left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 65%;
            background: #43A047; border-radius: 0 4px 4px 0;
            box-shadow: 0 0 8px rgba(67,160,71,0.6);
        }
        .sidebar-link:focus-visible { outline: 2px solid #43A047; outline-offset: 2px; }
        .sidebar-link .icon { width: 20px; text-align: center; font-size: 0.88rem; }

        /* Mobile overlay */
        #sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 40;
            backdrop-filter: blur(2px);
        }
        #sidebar-overlay.show { display: block; }

        /* Sidebar: hidden off-screen on mobile, always visible on lg+ */
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.25s cubic-bezier(0.4,0,0.2,1);
        }
        #sidebar.open { transform: translateX(0); }
        @media (min-width: 1024px) {
            #sidebar { transform: translateX(0) !important; position: relative !important; }
        }

        html { min-height: 100%; scroll-behavior: smooth; }
        body { min-height: 100vh; min-height: 100dvh; overflow-x: hidden; }
        button, a, input, select, textarea { touch-action: manipulation; }
        button, [role="button"], input, select, textarea { max-width: 100%; }
        img, video, canvas, svg { max-width: 100%; }
        main, section, article, form, fieldset, .flex-1, .min-w-0 { min-width: 0; }
        main table { max-width: 100%; }
        main :where(input:not([type="checkbox"]):not([type="radio"]):not([type="range"]), select, textarea) { min-height: 44px; }
        main :where(button, [type="button"], [type="submit"], [type="reset"]) { min-height: 44px; }
        #sidebar { width: min(16rem, calc(100vw - 2.5rem)); padding-bottom: env(safe-area-inset-bottom); }
        #sidebar-overlay { touch-action: manipulation; }
        @media (max-width: 640px) {
            #sidebar { padding-top: env(safe-area-inset-top); }
            .flex.h-full.overflow-hidden { min-height: 100vh; min-height: 100dvh; height: 100vh; height: 100dvh; }
            .flex-1.flex.flex-col.min-w-0.overflow-hidden > main { overscroll-behavior-y: contain; }
            main.overflow-y-auto { padding-left: max(1rem, env(safe-area-inset-left)); padding-right: max(1rem, env(safe-area-inset-right)); }
            main :where(.overflow-x-auto) { -webkit-overflow-scrolling: touch; }
            main :where(.grid) { min-width: 0; }
            main :where(h1, h2, h3, p, a, button, label, td, th) { overflow-wrap: anywhere; }
            header { padding-top: env(safe-area-inset-top); }
        }
        @media (max-width: 380px) {
            main.overflow-y-auto { padding-left: max(.75rem, env(safe-area-inset-left)); padding-right: max(.75rem, env(safe-area-inset-right)); }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
    @yield('styles')
</head>
<body class="h-full font-sans antialiased" style="background:var(--pf-bg);color:var(--pf-text);">

<div id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- Full-height flex shell — never scrolls itself --}}
<div class="flex h-full overflow-hidden">

    {{-- ══════════════════════════════════════
         SIDEBAR  — fixed on mobile, in-flow on desktop
    ══════════════════════════════════════ --}}
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 w-64 bg-[#1A2A6C] flex flex-col z-50 shadow-2xl
                  lg:relative lg:inset-auto lg:flex-shrink-0">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10 shrink-0">
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo.png') }}" alt="PathFindr"
                     class="h-8 w-auto transition-transform duration-200 group-hover:scale-105"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                <span style="display:none" class="items-center gap-1.5 text-lg font-extrabold text-white">
                    PathFindr
                </span>
            </a>
            <button onclick="closeSidebar()"
                    class="ml-auto lg:hidden w-7 h-7 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center text-white text-sm transition-all">
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- User info --}}
        <div class="px-4 py-4 border-b border-white/10 shrink-0">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}"
                     class="w-10 h-10 rounded-full object-cover border-2 border-[#43A047]/50 shrink-0">
                <div class="min-w-0">
                    <div class="text-white font-semibold text-sm truncate">{{ auth()->user()->name }}</div>
                    <div class="text-white/40 text-xs truncate">
                        @if(auth()->user()->isSuperAdmin())
                            <i class="fas fa-crown text-purple-300 mr-1"></i>Super Admin
                        @elseif(auth()->user()->isAdmin())
                            <i class="fas fa-user-shield text-[#F5A623] mr-1"></i>School Admin
                        @else
                            <i class="fas fa-user-graduate text-[#43A047] mr-1"></i>Student
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Nav — scrolls on its own if needed --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">
            @auth
            @if(auth()->user()->isStudent())
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-1">Main</p>
                <a href="{{ route('student.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-th-large"></i></span> Dashboard
                </a>
                <a href="{{ route('student.dashboard') }}#take-test"
                   class="sidebar-link">
                    <span class="icon"><i class="fas fa-pencil-alt"></i></span> Take Test
                </a>
                <a href="{{ route('student.results') }}"
                   class="sidebar-link {{ request()->routeIs('student.results') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-chart-bar"></i></span> My Results
                </a>
                <a href="{{ route('student.reports') }}"
                   class="sidebar-link {{ request()->routeIs('student.reports') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-file-pdf"></i></span> PDF Report
                </a>
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-4">Account</p>
                <a href="{{ route('profile.edit') }}"
                   class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-user-edit"></i></span> Edit Profile
                </a>
            @else
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-1">Overview</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-th-large"></i></span> Dashboard
                </a>
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-4">Students</p>
                <a href="{{ route('admin.students.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.students.index') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-users"></i></span> All Students
                </a>
                <a href="{{ route('admin.students.create') }}"
                   class="sidebar-link {{ request()->routeIs('admin.students.create') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-user-plus"></i></span> Add Student
                </a>
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-4">Test Bank</p>
                <a href="{{ route('admin.questions.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.questions.*') && !request()->routeIs('admin.questions.create') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-question-circle"></i></span> Questions
                </a>
                <a href="{{ route('admin.questions.create') }}"
                   class="sidebar-link {{ request()->routeIs('admin.questions.create') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-plus-circle"></i></span> Add Question
                </a>
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-4">Reports</p>
                <a href="{{ route('admin.export.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.export.*') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-file-export"></i></span> Export Results
                </a>
                <p class="text-white/30 text-[10px] font-bold uppercase tracking-widest px-3 pb-2 pt-4">Account</p>
                <a href="{{ route('profile.edit') }}"
                   class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <span class="icon"><i class="fas fa-cog"></i></span> Profile Settings
                </a>
            @endif
            @endauth
        </nav>

        {{-- Logout — always at bottom --}}
        <div class="px-3 py-4 border-t border-white/10 shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="sidebar-link w-full hover:bg-red-500/20 hover:text-red-300 group">
                    <span class="icon text-red-400/70 group-hover:text-red-300"><i class="fas fa-sign-out-alt"></i></span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ══════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Sticky top bar --}}
        <header class="border-b shrink-0 flex items-center gap-3 px-4 sm:px-6 h-14 transition-colors duration-200"
                style="background:var(--pf-header-bg);border-color:var(--pf-border);box-shadow:0 1px 12px var(--pf-shadow);">
            <button onclick="openSidebar()"
                    class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl transition-all border focus:outline-none focus:ring-2 focus:ring-[#43A047]"
                    style="color:var(--pf-navy);border-color:var(--pf-border);"
                    onmouseover="this.style.background='rgba(67,160,71,0.1)'" onmouseout="this.style.background='transparent'">
                <i class="fas fa-bars text-base"></i>
            </button>
            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-sm truncate" style="color:var(--pf-navy);">@yield('page-title', 'Dashboard')</h2>
            </div>
            <x-dark-toggle />
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 group focus:outline-none focus:ring-2 focus:ring-[#43A047] rounded-xl p-1">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}"
                     class="w-8 h-8 rounded-full object-cover border-2 transition-all"
                     style="border-color:var(--pf-border);"
                     onmouseover="this.style.borderColor='#43A047'" onmouseout="this.style.borderColor='var(--pf-border)'">
                <span class="hidden sm:block text-xs font-medium transition-colors truncate max-w-[120px]"
                      style="color:var(--pf-text2);">
                    {{ auth()->user()->name }}
                </span>
            </a>
        </header>

        {{-- Scrollable page content --}}
        <main class="flex-1 overflow-y-auto">
            @yield('content')
        </main>
    </div>

</div>{{-- end flex shell --}}

<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('open');
        document.getElementById('sidebar-overlay').classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('sidebar-overlay').classList.remove('show');
        document.body.style.overflow = '';
    }
    // Close on Escape
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
</script>

@yield('scripts')
<x-toast />

{{-- ── Global iOS 26 Glassmorphic Confirm Modal ── --}}
<div id="pf-confirm-overlay" style="display:none;position:fixed;inset:0;z-index:99998;
     background:rgba(0,0,0,0.35);backdrop-filter:blur(10px);-webkit-backdrop-filter:blur(10px);
     display:none;align-items:center;justify-content:center;padding:16px;">
    <div id="pf-confirm-box" style="
        width:100%;max-width:380px;
        background:rgba(255,255,255,0.22);
        backdrop-filter:blur(32px) saturate(180%);
        -webkit-backdrop-filter:blur(32px) saturate(180%);
        border:1px solid rgba(255,255,255,0.4);
        border-radius:26px;
        padding:28px 24px;
        box-shadow:0 20px 60px rgba(0,0,0,0.25),inset 0 1px 0 rgba(255,255,255,0.6);
        animation:pfCIn .4s cubic-bezier(.34,1.56,.64,1) forwards;
        text-align:center;">
        <div id="pf-confirm-icon" style="width:52px;height:52px;border-radius:50%;
             display:flex;align-items:center;justify-content:center;
             margin:0 auto 16px;font-size:1.3rem;"></div>
        <div id="pf-confirm-msg" style="font-size:0.92rem;font-weight:600;color:#111827;margin-bottom:6px;line-height:1.45;"></div>
        <div id="pf-confirm-sub" style="font-size:0.78rem;color:#6B7280;margin-bottom:22px;"></div>
        <div style="display:flex;gap:10px;">
            <button onclick="pfConfirmResolve(false)"
                    style="flex:1;padding:12px;border-radius:14px;border:1.5px solid rgba(0,0,0,0.1);
                           background:rgba(255,255,255,0.6);font-size:0.85rem;font-weight:600;
                           color:#374151;cursor:pointer;font-family:inherit;transition:all .15s;">
                Cancel
            </button>
            <button id="pf-confirm-ok" onclick="pfConfirmResolve(true)"
                    style="flex:1;padding:12px;border-radius:14px;border:none;
                           font-size:0.85rem;font-weight:700;color:#fff;
                           cursor:pointer;font-family:inherit;transition:all .15s;">
                Confirm
            </button>
        </div>
    </div>
</div>

<style>
@keyframes pfCIn  { from{opacity:0;transform:scale(.88) translateY(16px)} to{opacity:1;transform:scale(1) translateY(0)} }
@keyframes pfCOut { from{opacity:1;transform:scale(1)}  to{opacity:0;transform:scale(.92) translateY(8px)} }
html.dark #pf-confirm-box {
    background:rgba(22,27,34,0.75);
    border-color:rgba(255,255,255,0.12);
    box-shadow:0 20px 60px rgba(0,0,0,0.6),inset 0 1px 0 rgba(255,255,255,0.06);
}
html.dark #pf-confirm-msg { color:#e6edf3; }
html.dark #pf-confirm-sub { color:#8b949e; }
html.dark #pf-confirm-box button:first-child {
    background:rgba(255,255,255,0.08);
    border-color:rgba(255,255,255,0.1);
    color:#c9d1d9;
}
</style>

<script>
let _pfConfirmCb = null;

window.pfConfirm = function(message, onConfirm, type, subText) {
    type    = type    || 'warning';
    subText = subText || 'This action cannot be undone.';

    const colors = {
        warning: { bg:'rgba(245,158,11,0.15)', color:'#D97706', icon:'fa-exclamation-triangle', btn:'#D97706' },
        error:   { bg:'rgba(239,68,68,0.15)',  color:'#EF4444', icon:'fa-trash-alt',            btn:'#EF4444' },
        info:    { bg:'rgba(26,42,108,0.12)',   color:'#1A2A6C', icon:'fa-info-circle',          btn:'#1A2A6C' },
    };
    const c = colors[type] || colors.warning;

    document.getElementById('pf-confirm-icon').style.background = c.bg;
    document.getElementById('pf-confirm-icon').style.color      = c.color;
    document.getElementById('pf-confirm-icon').innerHTML        = `<i class="fas ${c.icon}"></i>`;
    document.getElementById('pf-confirm-ok').style.background   = c.btn;
    document.getElementById('pf-confirm-msg').textContent       = message;
    document.getElementById('pf-confirm-sub').textContent       = subText;

    _pfConfirmCb = onConfirm;

    const overlay = document.getElementById('pf-confirm-overlay');
    overlay.style.display = 'flex';
    document.getElementById('pf-confirm-box').style.animation = 'pfCIn .4s cubic-bezier(.34,1.56,.64,1) forwards';
};

window.pfConfirmResolve = function(confirmed) {
    const overlay = document.getElementById('pf-confirm-overlay');
    const box     = document.getElementById('pf-confirm-box');
    box.style.animation = 'pfCOut .25s ease forwards';
    setTimeout(() => {
        overlay.style.display = 'none';
        if (confirmed && typeof _pfConfirmCb === 'function') _pfConfirmCb();
        _pfConfirmCb = null;
    }, 240);
};

// Close on backdrop click
document.getElementById('pf-confirm-overlay').addEventListener('click', function(e) {
    if (e.target === this) pfConfirmResolve(false);
});
</script>
</body>
</html>
