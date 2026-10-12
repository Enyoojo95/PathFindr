<style>
    .nav-link {
        position: relative; padding-bottom: 3px;
        transition: color 0.18s ease;
    }
    .nav-link::after {
        content: ''; position: absolute; bottom: -1px; left: 50%;
        transform: translateX(-50%); width: 0; height: 2.5px;
        background: #43A047; border-radius: 99px;
        transition: width 0.22s cubic-bezier(0.4,0,0.2,1);
    }
    .nav-link:hover::after, .nav-link.active::after { width: 100%; }
    .nav-link.active { color: #1A2A6C !important; font-weight: 700; }
    .nav-link:hover { color: #1A2A6C !important; }
    .nav-link:focus-visible { outline: 2px solid #43A047; outline-offset: 3px; border-radius: 6px; }

    /* Dark mode nav */
    html.dark .nav-link { color: rgba(230,237,243,0.75); }
    html.dark .nav-link:hover, html.dark .nav-link.active { color: #79c0ff !important; }
    html.dark .nav-link::after { background: #56d364; }

    .navbar-glow::after {
        content: ''; position: absolute; bottom: -14px; left: 50%;
        transform: translateX(-50%); width: 55%; height: 28px;
        background: radial-gradient(ellipse at center, rgba(67,160,71,0.3) 0%, transparent 70%);
        filter: blur(6px); pointer-events: none; z-index: -1;
    }

    /* Dark mode navbar */
    html.dark nav.navbar-glow {
        background: rgba(13,17,23,0.96) !important;
        border-color: #30363d !important;
    }
    html.dark .mobile-divider { border-color: #30363d !important; }
    html.dark .mobile-link { color: rgba(230,237,243,0.8) !important; }
    html.dark .mobile-link:hover { background: rgba(255,255,255,0.06) !important; color: #fff !important; }
    html.dark .mobile-icon-wrap { background: rgba(255,255,255,0.08) !important; }
</style>

<nav class="navbar-glow backdrop-blur-lg border-b sticky top-0 z-50 relative transition-colors duration-200"
     style="background:var(--pf-header-bg);border-color:var(--pf-border);"
     x-data="{ mobileOpen: false }">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0 group focus:outline-none focus:ring-2 focus:ring-[#43A047] rounded-lg">
                <img src="{{ asset('images/logo.png') }}" alt="PathFindr"
                     class="h-9 w-auto transition-all duration-200 group-hover:scale-105 group-hover:drop-shadow-md"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <span style="display:none" class="items-center gap-2 text-xl font-extrabold" style="color:var(--pf-navy);">PathFindr</span>
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-0.5">
                @php
                    $currentPath = request()->path();
                    $navLinks = [
                        ['Home',         '/',                        ''],
                        ['Features',     '/#features',               ''],
                        ['How It Works', '/#how-it-works',           ''],
                        ['About',        '/#about',                  ''],
                        ['For Schools',  route('school-registration'), 'fa-school'],
                    ];
                @endphp
                @foreach($navLinks as [$label, $href, $icon])
                <a href="{{ $href }}"
                   class="nav-link px-3.5 py-2 text-sm font-semibold rounded-lg transition-colors duration-150 flex items-center gap-1.5 {{ $currentPath === ltrim($href, '/') ? 'active' : '' }}"
                   style="color:var(--pf-text2);">
                    @if($icon)<i class="fas {{ $icon }} text-[10px] text-[#43A047]"></i>@endif
                    {{ $label }}
                </a>
                @endforeach
            </div>

            {{-- Desktop Right --}}
            <div class="hidden md:flex items-center gap-2">
                <x-dark-toggle />
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#43A047]"
                       style="color:var(--pf-navy);background:var(--pf-border);">
                        <i class="fas fa-th-large text-xs"></i> Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1.5 bg-[#1A2A6C] hover:bg-[#43A047] active:scale-95 text-white text-sm font-bold px-5 py-2.5 rounded-full transition-all duration-200 shadow-md focus:outline-none focus:ring-2 focus:ring-[#43A047] focus:ring-offset-2">
                            <i class="fas fa-sign-out-alt text-xs"></i> Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-semibold rounded-xl transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#43A047]"
                       style="color:var(--pf-text2);">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center gap-1.5 bg-[#1A2A6C] hover:bg-[#43A047] active:scale-95 text-white text-sm font-bold px-5 py-2.5 rounded-full transition-all duration-200 shadow-md focus:outline-none focus:ring-2 focus:ring-[#43A047] focus:ring-offset-2">
                        <i class="fas fa-rocket text-xs"></i> Get Started
                    </a>
                @endauth
            </div>

            {{-- Mobile Toggle --}}
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl transition-all duration-150 border focus:outline-none focus:ring-2 focus:ring-[#43A047]"
                    style="color:var(--pf-navy);border-color:var(--pf-border);">
                <i class="fas text-lg transition-all duration-200" :class="mobileOpen ? 'fa-times text-[#43A047]' : 'fa-bars'"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div x-show="mobileOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-3"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-3"
             class="md:hidden border-t py-3 space-y-0.5 mobile-divider" style="border-color:var(--pf-border);">

            @foreach ([
                ['Home',         url('/'),                      'fa-home'],
                ['Features',     url('/#features'),            'fa-star'],
                ['How It Works', url('/#how-it-works'),        'fa-list-ol'],
                ['About',        url('/#about'),                'fa-info-circle'],
                ['For Schools',  route('school-registration'), 'fa-school'],
            ] as [$label, $href, $icon])
            <a href="{{ $href }}"
               class="mobile-link flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-150 group focus:outline-none focus:ring-2 focus:ring-[#43A047]"
               style="color:var(--pf-text2);">
                <span class="mobile-icon-wrap w-7 h-7 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                      style="background:var(--pf-border);">
                    <i class="fas {{ $icon }} text-[#43A047] text-xs"></i>
                </span>
                {{ $label }}
            </a>
            @endforeach

            <div class="pt-3 mt-1 border-t space-y-1.5 px-1 mobile-divider" style="border-color:var(--pf-border);">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-[#43A047]"
                       style="color:var(--pf-navy);">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:var(--pf-border);">
                            <i class="fas fa-th-large text-[#1A2A6C] text-xs"></i>
                        </span>
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-3 text-sm font-semibold text-red-500 hover:bg-red-50 rounded-xl transition-all duration-150">
                            <span class="w-7 h-7 rounded-lg bg-red-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-sign-out-alt text-red-400 text-xs"></i>
                            </span>
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-150"
                       style="color:var(--pf-navy);">
                        <span class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background:var(--pf-border);">
                            <i class="fas fa-sign-in-alt text-[#43A047] text-xs"></i>
                        </span>
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="flex items-center justify-center gap-2 bg-[#1A2A6C] hover:bg-[#43A047] text-white text-sm font-bold py-3 rounded-xl transition-all duration-200 shadow-md">
                        <i class="fas fa-rocket text-xs"></i> Get Started Free
                    </a>
                    <div class="flex justify-center pt-1">
                        <x-dark-toggle />
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
