 {{--
    Base glass layout — wraps the super-admin authenticated shell.
    Provides: gradient background, dot-grid overlay, sidebar slot,
    header slot, and scrollable content slot.

    Usage:
    <x-layouts.glass-app>
        <x-slot:sidebar>...</x-slot:sidebar>
        <x-slot:header>...</x-slot:header>
        <x-slot:content>...</x-slot:content>
    </x-layouts.glass-app>
--}}
<!DOCTYPE html>
<html lang="en" style="height:100%;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PathFindr Master' }}</title>

    {{-- FontAwesome --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    {{-- Inter font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    {{-- Compiled CSS (tokens + glass utilities) --}}
    @vite(['resources/css/app.css'])

    <style>
        html, body { height: 100%; margin: 0; font-family: 'Inter', sans-serif; }
        body { color: #fff; overflow: hidden; }
        /* Scrollbar on the main content area */
        #sa-main-content::-webkit-scrollbar { width: 5px; }
        #sa-main-content::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
        #sa-main-content::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius:99px; }
        #sa-main-content::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,0.5); }
    </style>

    {{-- Per-page styles --}}
    @stack('styles')
</head>

<body class="sa-page" style="height:100%;overflow:hidden;">

    {{-- Background layers (::before dot-grid, ::after colour blobs) are CSS-only --}}

    {{-- Mobile sidebar overlay --}}
    <div id="sa-overlay"
         style="display:none;position:fixed;inset:0;z-index:40;
                background:rgba(0,0,0,0.65);
                backdrop-filter:blur(3px);-webkit-backdrop-filter:blur(3px);"
         onclick="saCloseSidebar()">
    </div>

    {{-- App shell --}}
    <div style="display:flex;height:100%;position:relative;z-index:1;">

        {{-- ── SIDEBAR SLOT ────────────────────────────────── --}}
        {{-- The sidebar component positions itself; this just provides the slot --}}
        {{ $sidebar ?? '' }}

        {{-- ── RIGHT COLUMN ──────────────────────────────────── --}}
        <div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden;">

            {{-- ── HEADER SLOT ──────────────────────────────── --}}
            @isset($header)
            <header style="flex-shrink:0;display:flex;align-items:center;gap:12px;
                           padding:0 24px;height:56px;
                           background:rgba(11,17,36,0.65);
                           backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
                           border-bottom:1px solid rgba(255,255,255,0.07);">
                {{-- Hamburger (visible on mobile) --}}
                <button id="sa-hamburger"
                        onclick="saOpenSidebar()"
                        aria-label="Open menu"
                        style="display:none;flex-shrink:0;width:34px;height:34px;
                               cursor:pointer;background:var(--glass-bg);
                               border:1px solid var(--glass-border);border-radius:10px;
                               color:#fff;font-size:0.85rem;
                               align-items:center;justify-content:center;
                               transition:var(--transition-fast);"
                        onmouseover="this.style.background='var(--glass-bg-hover)';"
                        onmouseout="this.style.background='var(--glass-bg)';">
                    <i class="fas fa-bars"></i>
                </button>

                {{ $header }}
            </header>
            @endisset

            {{-- ── FLASH MESSAGES ──────────────────────────── --}}
            @if(session('success') || session('error'))
            <div style="padding:12px 24px 0;flex-shrink:0;">
                @if(session('success'))
                <div class="glass-card static"
                     style="padding:12px 16px;display:flex;align-items:center;gap:8px;
                            font-size:0.82rem;color:#6EE7B7;
                            border-color:rgba(16,185,129,0.3);">
                    <i class="fas fa-check-circle" style="flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="glass-card static"
                     style="padding:12px 16px;display:flex;align-items:center;gap:8px;
                            font-size:0.82rem;color:#FCA5A5;
                            border-color:rgba(239,68,68,0.3);margin-top:8px;">
                    <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                    {{ session('error') }}
                </div>
                @endif
            </div>
            @endif

            {{-- ── PAGE CONTENT ─────────────────────────────── --}}
            <main id="sa-main-content"
                  class="sa-page-enter"
                  style="flex:1;overflow-y:auto;padding:24px;">
                {{ $content ?? $slot }}
            </main>

        </div>
    </div>

    {{-- Responsive: show hamburger below lg --}}
    <style>
        @media (max-width: 1023px) {
            #sa-hamburger { display: inline-flex !important; }
        }
    </style>

    {{-- Sidebar JS helpers (used by the sidebar component) --}}
    <script>
        function saOpenSidebar() {
            var sb = document.getElementById('sa-sidebar');
            var ov = document.getElementById('sa-overlay');
            if (sb) sb.classList.add('sa-sidebar-open');
            if (ov) ov.style.display = 'block';
            document.body.style.overflow = 'hidden';
        }
        function saCloseSidebar() {
            var sb = document.getElementById('sa-sidebar');
            var ov = document.getElementById('sa-overlay');
            if (sb) sb.classList.remove('sa-sidebar-open');
            if (ov) ov.style.display = 'none';
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') saCloseSidebar();
        });
    </script>

    @stack('scripts')
</body>
</html>
