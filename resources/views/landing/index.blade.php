@extends('layouts.app')
@section('title', 'PathFindr — Know Your Strengths. Choose Your Future.')

@section('styles')
<style>
/* ══════════════════════════════════════════════════════
   DESIGN TOKENS
══════════════════════════════════════════════════════ */
:root {
    --navy:       #1A2A6C;
    --navy-dark:  #0f1f5c;
    --blue:       #2D5F9E;
    --green:      #2E7D32;
    --green-lt:   #43A047;
    --ink:        #0f172a;
    --body:       #334155;
    --muted:      #64748b;
    --border:     #e5e7eb;
}

/* ── Focus ring ── */
a:focus-visible, button:focus-visible {
    outline: 3px solid var(--navy);
    outline-offset: 3px;
    border-radius: 8px;
}
.cta-section a:focus-visible { outline-color: #fff; }

/* ── Hero ── */
.hero-section {
    background: linear-gradient(145deg, #f0f6ff 0%, #eaf4ea 50%, #f4f0ff 100%);
    position: relative;
    overflow: hidden;
}
.hero-section::before {
    content: '';
    position: absolute; inset: 0; pointer-events: none; z-index: 0;
    background-image: radial-gradient(circle, #94a3b8 1px, transparent 1px);
    background-size: 30px 30px;
    opacity: 0.14;
}
.blob {
    position: absolute; border-radius: 50%; pointer-events: none; z-index: 0;
    filter: blur(70px);
}

/* ── Gradient headline accent — removed, using solid colours instead ── */

/* ── Floating stat card ── */
.stat-float {
    background: rgba(255,255,255,0.97);
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 8px 32px rgba(15,31,92,0.12);
    border-radius: 16px;
    padding: 12px 16px;
    position: absolute;
}

/* ── Section label pill ── */
.section-label {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.09em; color: var(--green);
    background: rgba(46,125,50,0.08);
    border: 1px solid rgba(46,125,50,0.25);
    padding: 5px 14px; border-radius: 99px; margin-bottom: 14px;
}

/* ── Feature cards ── */
.feat-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 22px;
    padding: 28px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
    overflow: hidden;
}
.feat-card::after {
    content: '';
    position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    opacity: 0;
    transition: opacity 0.3s ease;
}
.feat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(15,31,92,0.10); }
.feat-card:hover::after { opacity: 1; }

/* ── Step number circle ── */
.step-num {
    width: 46px; height: 46px; border-radius: 50%;
    color: #fff; font-size: 1.1rem; font-weight: 900;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 6px 18px rgba(15,31,92,0.25);
}

/* ── CTA section ── */
.cta-section {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--navy) 50%, var(--blue) 100%);
    position: relative; overflow: hidden;
}
.cta-section::before {
    content: '';
    position: absolute; inset: 0; pointer-events: none;
    background-image: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
    background-size: 26px 26px;
}

/* ── Path badge (subject combos) ── */
.path-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 16px; border-radius: 99px;
    font-size: 0.78rem; font-weight: 700;
    border: 2px solid; transition: transform 0.2s, box-shadow 0.2s;
}
.path-badge:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }

/* ── Benefit icon box ── */
.benefit-icon {
    width: 46px; height: 46px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}

/* ── Partner card ── */
.partner-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 18px 24px;
    display: flex; align-items: center; gap: 14px;
    transition: transform 0.25s, box-shadow 0.25s;
}
.partner-card:hover { box-shadow: 0 8px 28px rgba(15,31,92,0.09); transform: translateY(-2px); }

/* ── Testimonial card ── */
.testimonial-card {
    flex-shrink: 0; width: 288px;
    background: #f8faff;
    border: 1px solid #e5eaf5;
    border-radius: 20px;
    padding: 22px;
    transition: transform 0.3s, box-shadow 0.3s;
}
.testimonial-card:hover { box-shadow: 0 12px 36px rgba(15,31,92,0.10); transform: translateY(-3px); }

/* ── Testimonial marquee ── */
.testimonial-track { display: flex; gap: 18px; width: max-content; }
.track-left  { animation: scrollLeft  42s linear infinite; }
.track-right { animation: scrollRight 42s linear infinite; }
.track-left:hover, .track-right:hover { animation-play-state: paused; }
@keyframes scrollLeft  { from { transform: translateX(0); } to { transform: translateX(-50%); } }
@keyframes scrollRight { from { transform: translateX(-50%); } to { transform: translateX(0); } }

/* ── Entrance animation ── */
.fade-up { opacity: 0; transform: translateY(22px); transition: opacity 0.55s ease, transform 0.55s ease; }
.fade-up.visible { opacity: 1; transform: translateY(0); }

/* ── Stats strip (dark) ── */
.stats-strip {
    background: linear-gradient(135deg, var(--navy-dark) 0%, var(--blue) 100%);
    border-radius: 24px;
}
.stats-strip .stat-num  { font-size: 2.4rem; font-weight: 900; color: #fff; line-height: 1; }
.stats-strip .stat-lbl  { font-size: 0.82rem; font-weight: 600; color: rgba(255,255,255,0.65); margin-top: 4px; }
.stats-strip .stat-div  { width: 1px; background: rgba(255,255,255,0.18); align-self: stretch; }

/* ══════════════════════════════════════════════════════
   DARK MODE OVERRIDES — landing page
   All hardcoded hex colours that don't go through
   the pf- token system need explicit dark overrides.
══════════════════════════════════════════════════════ */

/* Hero section background */
html.dark .hero-section {
    background: linear-gradient(145deg, #e8edf2 0%, #e6ede6 50%, #eae8f0 100%) !important;
}
html.dark .hero-section::before { opacity: 0.06; }

/* Hero headline — keep dark on the dim-white bg, override global dark token */
html.dark .hero-headline-dark  { color: #0f172a !important; -webkit-text-fill-color: #0f172a !important; }
html.dark .hero-headline-navy  { color: #1A2A6C !important; -webkit-text-fill-color: #1A2A6C !important; }
html.dark .hero-headline-green { color: #2E7D32 !important; -webkit-text-fill-color: #2E7D32 !important; }

/* Hero body copy — dark on dim-white */
html.dark .hero-section .text-\[\#334155\] { color: #1e293b !important; }
html.dark .hero-section .text-\[\#475569\] { color: #334155 !important; }
html.dark .hero-section strong.text-\[\#1A2A6C\] { color: #1A2A6C !important; }

/* Body copy — all the #334155 / #475569 / #64748b shades */
html.dark .text-\[\#334155\]  { color: #cdd9e5 !important; }
html.dark .text-\[\#475569\]  { color: #adbac7 !important; }
html.dark .text-\[\#64748b\]  { color: #8b949e !important; }
html.dark .text-\[\#374151\]  { color: #cdd9e5 !important; }
html.dark .text-\[\#4B5563\]  { color: #adbac7 !important; }
html.dark .text-\[\#94a3b8\]  { color: #6e7681 !important; }

/* Headings */
html.dark .text-\[\#0f172a\]  { color: #e6edf3 !important; }
html.dark .text-\[\#111827\]  { color: #e6edf3 !important; }

/* Accent greens on light bg */
html.dark .text-\[\#2E7D32\]  { color: #56d364 !important; }
html.dark .text-\[\#1b5e20\]  { color: #56d364 !important; }
html.dark .text-\[\#14532d\]  { color: #56d364 !important; }

/* Accent purples / oranges on light bg */
html.dark .text-\[\#4c1d95\]  { color: #c4b5fd !important; }
html.dark .text-\[\#7c2d12\]  { color: #fb923c !important; }

/* Section label pill */
html.dark .section-label {
    background: rgba(86,211,100,0.1) !important;
    border-color: rgba(86,211,100,0.25) !important;
    color: #56d364 !important;
}

/* Academy badge in hero */
html.dark .hero-section .inline-flex.bg-white\/90 {
    background: rgba(22,27,34,0.9) !important;
    border-color: rgba(121,192,255,0.2) !important;
    color: #cdd9e5 !important;
}

/* Stat float cards */
html.dark .stat-float {
    background: rgba(22,27,34,0.97) !important;
    border-color: #30363d !important;
}
html.dark .stat-float .text-\[\#0f172a\] { color: #e6edf3 !important; }

/* Feature cards */
html.dark .feat-card {
    background: #161b22 !important;
    border-color: #30363d !important;
}

/* How It Works step cards */
html.dark .bg-\[\#f8fafc\].rounded-2xl {
    background: #161b22 !important;
    border-color: #30363d !important;
}

/* Analysis / score boxes */
html.dark .an-box {
    background: #161b22 !important;
    border-color: #30363d !important;
}

/* Path cards (Science, Commercial, Arts) */
html.dark [style*="background:#e8f5e9"] {
    background: rgba(46,125,50,0.12) !important;
}
html.dark [style*="background:#fff3e0"] {
    background: rgba(194,65,12,0.12) !important;
}
html.dark [style*="background:#ede9fe"] {
    background: rgba(124,58,237,0.12) !important;
}

/* Partner card */
html.dark .partner-card {
    background: #161b22 !important;
    border-color: #30363d !important;
}

/* Testimonial cards */
html.dark .testimonial-card {
    background: #161b22 !important;
    border-color: #30363d !important;
}
html.dark .testimonial-card .border-t { border-color: #30363d !important; }

/* Testimonial edge fades match dark bg */
html.dark .testimonial-track + .pointer-events-none,
html.dark .pointer-events-none.from-\[\#f8fafc\] {
    background: transparent !important;
}

/* Trusted By / Partner Schools sections */
html.dark .border-y.border-gray-100  { border-color: #30363d !important; }

/* CTA section — already dark, fine as-is */

/* Stats strip — already dark gradient, fine */

/* Benefit icon boxes */
html.dark .benefit-icon { background: rgba(255,255,255,0.06) !important; }

/* Path badge pills (hero) */
html.dark .path-badge[style*="background:#e8f5e9"] {
    background: rgba(46,125,50,0.15) !important;
    color: #56d364 !important;
    border-color: #2E7D32 !important;
}
html.dark .path-badge[style*="background:#fff3e0"] {
    background: rgba(234,88,12,0.15) !important;
    color: #fb923c !important;
    border-color: #ea580c !important;
}
html.dark .path-badge[style*="background:#ede9fe"] {
    background: rgba(124,58,237,0.15) !important;
    color: #c4b5fd !important;
    border-color: #7C3AED !important;
}

/* "How It Works" section white bg */
html.dark #how-it-works { background: #0d1117 !important; }

/* Features section bg */
html.dark #features { background: #0d1117 !important; }

/* About section bg */
html.dark #about { background: #0d1117 !important; }

/* Partner + trusted sections */
html.dark section.bg-white { background: #0d1117 !important; }
html.dark section.bg-\[\#f8fafc\] { background: #0d1117 !important; }

/* Testimonials section */
html.dark section.py-20.bg-\[\#f8fafc\] { background: #0d1117 !important; }
html.dark .pointer-events-none.from-\[\#f8fafc\] {
    background-image: linear-gradient(to right, #0d1117, transparent) !important;
}
html.dark .pointer-events-none.from-white {
    background-image: linear-gradient(to right, #0d1117, transparent) !important;
}

/* ── Reduce motion ── */
@media (prefers-reduced-motion: reduce) {
    .fade-up { opacity: 1; transform: none; transition: none; }
    .feat-card, .partner-card, .path-badge { transition: none; }
    .feat-card:hover, .partner-card:hover { transform: none; }
    .track-left, .track-right { animation: none; }
    .testimonial-track { width: auto; flex-wrap: wrap; justify-content: center; }
    .is-dup { display: none; }
}
</style>
<noscript><style>.fade-up { opacity: 1; transform: none; }</style></noscript>
@endsection

@section('content')

{{-- ══════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════ --}}
<section class="hero-section min-h-[90vh] flex items-center">
    <div class="blob" style="width:500px;height:500px;top:-100px;left:-120px;background:radial-gradient(circle,rgba(67,160,71,0.18),transparent);"></div>
    <div class="blob" style="width:420px;height:420px;bottom:-80px;right:-100px;background:radial-gradient(circle,rgba(45,95,158,0.18),transparent);"></div>
    <div class="blob" style="width:260px;height:260px;top:30%;left:48%;background:radial-gradient(circle,rgba(99,102,241,0.12),transparent);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left: copy --}}
            <div class="fade-up">
                {{-- Academy badge --}}
                <div class="inline-flex items-center gap-2 bg-white/90 border border-[#1A2A6C]/15 shadow-sm px-4 py-2 rounded-full text-sm font-semibold text-[#1A2A6C] mb-6">
                    <i class="fas fa-school text-[#1A2A6C] text-xs" aria-hidden="true"></i>
                    An initiative of <strong class="text-[#2E7D32] ml-1">Crystalfield Academy</strong>
                </div>

                <div class="section-label mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32] animate-pulse inline-block" aria-hidden="true"></span>
                    AI-Powered Career Guidance
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-[3.15rem] font-extrabold leading-[1.1] mb-5 tracking-tight">
                    <span class="hero-headline-dark text-[#0f172a]">Know Your </span><span class="hero-headline-navy text-[#1A2A6C]">Strengths.</span><br>
                    <span class="hero-headline-navy text-[#1A2A6C]">Choose Your</span><br>
                    <span class="hero-headline-green text-[#2E7D32]">Right Path.</span>
                </h1>

                <p class="text-[#334155] text-lg leading-relaxed mb-8 max-w-lg">
                    PathFindr helps JSS3 students discover their academic strengths and choose the right SS1 subject combination —
                    <strong class="text-[#1A2A6C]">Science, Commercial, or Arts</strong> —
                    through a 30-minute psychometric assessment powered by AI.
                </p>

                {{-- Subject pills --}}
                <div class="flex flex-wrap gap-2 mb-8">
                    <span class="path-badge" style="background:linear-gradient(135deg,#e8f5e9,#f0fdf4);color:#1b5e20;border-color:#2E7D32;box-shadow:0 2px 8px rgba(46,125,50,0.15);">
                        <i class="fas fa-flask text-[10px]" aria-hidden="true"></i> Science
                    </span>
                    <span class="path-badge" style="background:linear-gradient(135deg,#fff3e0,#fff7ed);color:#7c2d12;border-color:#ea580c;box-shadow:0 2px 8px rgba(234,88,12,0.15);">
                        <i class="fas fa-chart-line text-[10px]" aria-hidden="true"></i> Commercial
                    </span>
                    <span class="path-badge" style="background:linear-gradient(135deg,#ede9fe,#f5f3ff);color:#4c1d95;border-color:#7C3AED;box-shadow:0 2px 8px rgba(124,58,237,0.15);">
                        <i class="fas fa-pen-nib text-[10px]" aria-hidden="true"></i> Arts
                    </span>
                </div>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-3 mb-10">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#0f1f5c] text-white font-bold px-7 py-3.5 rounded-full transition-all duration-200 shadow-lg shadow-[#1A2A6C]/30 text-sm">
                        <i class="fas fa-rocket text-xs" aria-hidden="true"></i> Get Started Free
                    </a>
                    <a href="#how-it-works"
                       class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-[#1A2A6C] border border-gray-200 font-semibold px-7 py-3.5 rounded-full transition-all duration-200 shadow-sm text-sm">
                        <i class="fas fa-play-circle text-[#2E7D32] text-xs" aria-hidden="true"></i> How It Works
                    </a>
                </div>

                {{-- Social proof --}}
                <div class="flex items-center gap-3">
                    <div class="flex -space-x-2" aria-hidden="true">
                        @foreach(['A','C','F','N'] as $initial)
                            <div class="w-8 h-8 rounded-full border-2 border-white flex items-center justify-center text-white text-[11px] font-bold shadow-sm"
                                 style="background:{{ ['#1A2A6C','#2E7D32','#2D5F9E','#c2410c'][$loop->index] }};">
                                {{ $initial }}
                            </div>
                        @endforeach
                    </div>
                    <div>
                        <div class="flex gap-0.5" aria-hidden="true">
                            @for($i=0;$i<5;$i++)<i class="fas fa-star text-amber-500 text-xs"></i>@endfor
                        </div>
                        <p class="text-[#475569] text-sm mt-0.5">
                            <strong class="text-[#1A2A6C]">{{ number_format($totalStudents) }}</strong> registered student{{ $totalStudents === 1 ? '' : 's' }} finding their path
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right: hero image + stats --}}
            <div class="relative flex justify-center items-end min-h-[380px] fade-up" style="transition-delay:0.15s;">
                <div class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-hidden="true">
                    <div style="width:340px;height:340px;border-radius:50%;
                                background:radial-gradient(circle,rgba(67,160,71,0.20) 0%,rgba(45,95,158,0.12) 55%,transparent 72%);
                                filter:blur(4px);"></div>
                </div>

                <img src="{{ asset('images/hero-student.png') }}"
                     alt="A student using PathFindr"
                     class="relative z-10 w-64 sm:w-80 lg:w-[22rem] object-contain"
                     style="mix-blend-mode:multiply;"
                     onerror="this.style.display='none';">

                <div class="stat-float z-20" style="top:8px;right:0;">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#1A2A6C] flex items-center justify-center shrink-0">
                            <i class="fas fa-users text-white text-xs" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="text-[#0f172a] text-lg font-extrabold leading-none">{{ number_format($totalStudents) }}</div>
                            <div class="text-[#64748b] text-xs font-medium">Students</div>
                        </div>
                    </div>
                </div>
                <div class="stat-float z-20" style="top:38%;right:-10px;">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#2E7D32] flex items-center justify-center shrink-0">
                            <i class="fas fa-school text-white text-xs" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="text-[#0f172a] text-lg font-extrabold leading-none">{{ number_format($totalSchools) }}</div>
                            <div class="text-[#64748b] text-xs font-medium">Schools Represented</div>
                        </div>
                    </div>
                </div>
                <div class="stat-float z-20" style="bottom:16px;right:4px;">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-[#b45309] flex items-center justify-center shrink-0">
                            <i class="fas fa-briefcase text-white text-xs" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="text-[#0f172a] text-lg font-extrabold leading-none">200+</div>
                            <div class="text-[#64748b] text-xs font-medium">Careers</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     TRUSTED BY
══════════════════════════════════════════════════════ --}}
<section class="bg-white border-y border-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-center gap-6 sm:gap-12">
        <span class="text-[#94a3b8] text-xs font-bold uppercase tracking-widest">Trusted by</span>
        <div class="partner-card">
            <div class="w-10 h-10 bg-[#1A2A6C] rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-school text-amber-400" aria-hidden="true"></i>
            </div>
            <div>
                <div class="font-bold text-[#1A2A6C] text-sm">Crystalfield Academy</div>
                <div class="text-[#64748b] text-xs">Karshi, Abuja</div>
            </div>
        </div>
        <div class="text-[#64748b] text-sm italic font-medium">+ more schools joining soon</div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     FEATURES
══════════════════════════════════════════════════════ --}}
<section id="features" class="py-20 bg-[#f8fafc]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 fade-up">
            <div class="section-label">Why PathFindr?</div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0f172a] mb-3 tracking-tight">
                Empowering Students to Grow Smarter
            </h2>
            <p class="text-[#475569] text-lg max-w-xl mx-auto">
                Built specifically for Nigerian JSS3 students transitioning to senior secondary school.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $features = [
                    ['fa-brain',   '#1A2A6C', 'Psychometric Testing',
                     'A scientifically designed 30-minute assessment measuring numerical, verbal, spatial, and personal interest strengths.',
                     'Science-backed'],
                    ['fa-robot',   '#2E7D32', 'AI-Powered Recommendations',
                     'Axel analyses your scores to match you with the right subject combination, career paths, and Nigerian universities.',
                     'Personalised to you'],
                    ['fa-file-pdf','#2D5F9E', 'Downloadable PDF Reports',
                     'Get a professional report your parents and school counsellors can review — ready to download after completing the test.',
                     'Share with your school'],
                ];
            @endphp
            @foreach($features as [$icon, $color, $title, $desc, $tag])
            <div class="feat-card fade-up" style="transition-delay:{{ $loop->index * 0.1 }}s;">
                <div class="feat-card::after" style="background:linear-gradient(90deg,{{ $color }},{{ $color }}88);"></div>
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-5 shadow-md"
                     style="background:{{ $color }};">
                    <i class="fas {{ $icon }} text-white text-xl" aria-hidden="true"></i>
                </div>
                <h3 class="text-[#0f172a] font-bold text-lg mb-2">{{ $title }}</h3>
                <p class="text-[#475569] text-sm leading-relaxed mb-4">{{ $desc }}</p>
                <span class="inline-flex items-center gap-1.5 text-sm font-semibold" style="color:{{ $color }};">
                    <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i> {{ $tag }}
                </span>
            </div>
            @endforeach
        </div>

        {{-- Stats strip --}}
        <div class="mt-12 stats-strip p-8 md:p-10 fade-up">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-0 items-center">
                @php $stats = [[$totalStudents,'Registered Students'],[$totalSchools,'Schools Represented'],['200+','Career Pathways']]; @endphp
                @foreach($stats as $i => [$num,$lbl])
                <div class="text-center {{ $i < 2 ? 'sm:border-r sm:border-white/20' : '' }} sm:px-8">
                    <div class="stat-num">{{ is_numeric($num) ? number_format($num) : $num }}</div>
                    <div class="stat-lbl">{{ $lbl }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════════════════ --}}
<section id="how-it-works" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 fade-up">
            <div class="section-label">Simple Process</div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0f172a] mb-3 tracking-tight">How It Works</h2>
            <p class="text-[#475569] text-lg max-w-lg mx-auto">
                Three simple steps to discover your ideal subject combination and career path.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $steps = [
                    ['Register & Login',   'Create your free account in minutes — just your name, email, and school name.', 'fa-user-plus',     '#1A2A6C'],
                    ['Take the Test',      'Complete a 30-minute psychometric assessment across 4 key areas of ability and interest.', 'fa-pencil-alt', '#2E7D32'],
                    ['Get Your Path',      'Receive AI-powered subject, career, and university recommendations — plus a downloadable PDF report.', 'fa-map-marked-alt', '#2D5F9E'],
                ];
            @endphp
            @foreach($steps as $i => [$title, $desc, $icon, $color])
            <div class="relative fade-up bg-[#f8fafc] rounded-2xl p-7 border border-gray-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                 style="transition-delay:{{ $i * 0.1 }}s;">
                <div class="flex items-center gap-3 mb-4">
                    <div class="step-num" style="background:{{ $color }};">{{ $i+1 }}</div>
                    <div class="flex-1 h-px" style="background:{{ $color }}33;"></div>
                </div>
                <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4" style="background:{{ $color }}18;">
                    <i class="fas {{ $icon }} text-lg" style="color:{{ $color }};" aria-hidden="true"></i>
                </div>
                <h3 class="text-[#0f172a] font-bold text-lg mb-2">{{ $title }}</h3>
                <p class="text-[#475569] text-sm leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     BENEFITS / ABOUT
══════════════════════════════════════════════════════ --}}
<section id="about" class="py-20 bg-[#f8fafc]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div class="fade-up">
                <div class="section-label">About PathFindr</div>
                <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0f172a] mb-5 tracking-tight">
                    The Smarter Way to Choose Your<br>
                    <span class="text-[#1A2A6C]">Academic Future</span>
                </h2>
                <p class="text-[#475569] leading-relaxed mb-8">
                    Too many Nigerian students choose SS1 subject combinations based on peer pressure or guesswork. PathFindr changes that — giving every JSS3 student access to a data-driven, AI-powered career guidance tool that was previously only available to students at elite schools.
                </p>
                <div class="space-y-4">
                    @php
                        $benefits = [
                            ['fa-check-circle','#2E7D32','Built for Nigerian Curriculum',
                             'Aligned with WAEC and NECO pathways for Science, Commercial, and Arts.'],
                            ['fa-check-circle','#1A2A6C','Psychometric Science',
                             'Four-area assessment: Numerical, Verbal, Spatial, and Personal Interest.'],
                            ['fa-check-circle','#2E7D32','Instant AI Analysis',
                             'Axel — our in-built AI — processes your scores and generates personalised recommendations.'],
                            ['fa-check-circle','#1A2A6C','Free for Students',
                             'No cost to students. Schools get a dedicated admin portal.'],
                        ];
                    @endphp
                    @foreach($benefits as [$icon, $color, $title, $desc])
                    <div class="flex items-start gap-4">
                        <div class="benefit-icon mt-0.5" style="background:{{ $color }}16;">
                            <i class="fas {{ $icon }}" style="color:{{ $color }};font-size:1rem;" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="font-bold text-[#0f172a] text-sm">{{ $title }}</div>
                            <div class="text-[#475569] text-sm mt-0.5 leading-relaxed">{{ $desc }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Subject path cards --}}
            <div class="grid grid-cols-1 gap-4 fade-up" style="transition-delay:0.15s;">
                @php
                    $paths = [
                        ['Science',    'fa-flask',      '#2E7D32', '#e8f5e9', '#f0fdf4',
                         'Physics · Chemistry · Biology · Mathematics',
                         'Doctor, Engineer, Architect, Computer Scientist',
                         '#14532d'],
                        ['Commercial', 'fa-chart-line', '#c2410c', '#fff3e0', '#fff7ed',
                         'Economics · Accounting · Commerce · Business Studies',
                         'Accountant, Banker, Entrepreneur, Analyst',
                         '#7c2d12'],
                        ['Arts',       'fa-pen-nib',    '#7C3AED', '#ede9fe', '#f5f3ff',
                         'Literature · Government · History · CRK / IRS',
                         'Lawyer, Journalist, Teacher, Designer',
                         '#4c1d95'],
                    ];
                @endphp
                @foreach($paths as [$name, $icon, $accent, $bg, $bgHover, $subjects, $careers, $textColor])
                <div class="rounded-2xl p-5 border-l-4 flex items-start gap-4 hover:shadow-md transition-all duration-200"
                     style="background:{{ $bg }};border-left-color:{{ $accent }};">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                         style="background:{{ $accent }};">
                        <i class="fas {{ $icon }} text-white text-base" aria-hidden="true"></i>
                    </div>
                    <div>
                        <div class="font-bold text-[#0f172a] text-base mb-1">{{ $name }}</div>
                        <div class="text-[13px] font-semibold mb-1" style="color:{{ $textColor }};">{{ $subjects }}</div>
                        <div class="text-[#475569] text-[13px] leading-snug">{{ $careers }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     PARTNER SCHOOLS
══════════════════════════════════════════════════════ --}}
<section class="py-14 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center fade-up">
        <div class="section-label" style="width:fit-content;margin:0 auto 16px;">Our Partner Schools</div>
        <h3 class="text-2xl font-extrabold text-[#0f172a] mb-8 tracking-tight">Schools That Trust PathFindr</h3>
        <div class="flex flex-wrap justify-center gap-5">
            <div class="partner-card">
                <div class="w-12 h-12 bg-[#1A2A6C] rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-school text-amber-400 text-lg" aria-hidden="true"></i>
                </div>
                <div class="text-left">
                    <div class="font-bold text-[#0f172a]">Crystalfield Academy</div>
                    <div class="text-[#64748b] text-xs mt-0.5">Karshi, Abuja</div>
                </div>
            </div>
        </div>
        <p class="text-[#64748b] text-sm mt-8">
            <i class="fas fa-handshake text-[#2E7D32] mr-2" aria-hidden="true"></i>
            Is your school interested?
            <a href="{{ route('school-registration') }}"
               class="text-[#1A2A6C] font-semibold underline underline-offset-2 hover:text-[#2E7D32] transition-colors ml-1">
                Register your school →
            </a>
        </p>
    </div>
</section>

{{-- ══════════════════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════════════════ --}}
@if($testimonials->count())
<section class="py-20 bg-[#f8fafc] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 fade-up">
            <div class="section-label" style="width:fit-content;margin:0 auto 14px;">Real Students. Real Stories.</div>
            <h2 class="text-3xl sm:text-4xl font-extrabold text-[#0f172a] mb-3 tracking-tight">
                Voices of the <span class="text-[#1A2A6C]">PathFindr</span> Community
            </h2>
            <p class="text-[#475569] text-lg max-w-xl mx-auto">
                Hear directly from students who discovered their direction with PathFindr.
            </p>
        </div>

        @php
            $useMarquee = $testimonials->count() >= 4;
            // Always use exactly 2 copies so translateX(-50%) lands perfectly
            $copies = 2;
        @endphp

        @if(!$useMarquee)
        {{-- Static grid for small counts — no animation, no duplication --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($testimonials as $t)
            <div class="testimonial-card">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex gap-0.5" role="img" aria-label="Rated {{ $t->rating }} out of 5">
                        @for($s=1;$s<=5;$s++)
                        <i class="fa-star text-xs {{ $s <= $t->rating ? 'fas text-amber-500' : 'far text-gray-300' }}" aria-hidden="true"></i>
                        @endfor
                    </div>
                    <i class="fas fa-quote-right text-[#1A2A6C]/12 text-2xl" aria-hidden="true"></i>
                </div>
                <p class="text-[#374151] text-sm leading-relaxed mb-5 line-clamp-4">
                    "{{ $t->body }}"
                </p>
                <div class="flex items-center gap-3 pt-3 border-t border-gray-200">
                    <img src="{{ $t->user->avatarUrl() }}" alt="{{ $t->user->name }}"
                         class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                    <div>
                        <div class="font-semibold text-[#0f172a] text-sm">{{ $t->user->name }}</div>
                        <div class="text-[#64748b] text-xs">{{ $t->user->school_name ?? 'PathFindr Student' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @else
        {{-- Marquee for 4+ testimonials --}}
        <div class="relative">
            @php
                $rows = [
                    ['track-left',  $testimonials->take(6)],
                    ['track-right', $testimonials->skip(3)->take(6)],
                ];
            @endphp
            @foreach($rows as $rowIndex => [$trackClass, $items])
                @continue($rowIndex === 1 && $testimonials->count() <= 6)
                <div class="testimonial-track {{ $trackClass }} {{ $rowIndex === 0 ? 'mb-5' : '' }}">
                    @for($copy = 0; $copy < $copies; $copy++)
                        @foreach($items as $t)
                        <div class="testimonial-card {{ $copy > 0 ? 'is-dup' : '' }}"
                             @if($copy > 0) aria-hidden="true" @endif>
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex gap-0.5" role="img" aria-label="Rated {{ $t->rating }} out of 5">
                                    @for($s=1;$s<=5;$s++)
                                    <i class="fa-star text-xs {{ $s <= $t->rating ? 'fas text-amber-500' : 'far text-gray-300' }}" aria-hidden="true"></i>
                                    @endfor
                                </div>
                                <i class="fas fa-quote-right text-[#1A2A6C]/12 text-2xl" aria-hidden="true"></i>
                            </div>
                            <p class="text-[#374151] text-sm leading-relaxed mb-5 line-clamp-4">
                                "{{ $t->body }}"
                            </p>
                            <div class="flex items-center gap-3 pt-3 border-t border-gray-200">
                                <img src="{{ $t->user->avatarUrl() }}" alt="{{ $t->user->name }}"
                                     class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm">
                                <div>
                                    <div class="font-semibold text-[#0f172a] text-sm">{{ $t->user->name }}</div>
                                    <div class="text-[#64748b] text-xs">{{ $t->user->school_name ?? 'PathFindr Student' }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endfor
                </div>
            @endforeach

            <div class="pointer-events-none absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-[#f8fafc] to-transparent" aria-hidden="true"></div>
            <div class="pointer-events-none absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-[#f8fafc] to-transparent" aria-hidden="true"></div>
        </div>
        @endif
    </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════
     CTA
══════════════════════════════════════════════════════ --}}
<section class="cta-section py-24 fade-up">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-compass text-white text-2xl" aria-hidden="true"></i>
        </div>
        <h2 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 tracking-tight">
            Ready to Discover Your Path?
        </h2>
        <p class="text-blue-100 text-lg mb-8 max-w-xl mx-auto leading-relaxed">
            Join {{ number_format($totalStudents) }} Nigerian student{{ $totalStudents === 1 ? '' : 's' }} finding their way with PathFindr's AI-powered career guidance —
            <strong class="text-white">free for every student.</strong>
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('register') }}"
               class="inline-flex items-center justify-center gap-2 bg-[#2E7D32] hover:bg-[#1B5E20] text-white font-bold px-10 py-4 rounded-full text-base transition-all duration-200 shadow-xl shadow-black/20 border border-white/15">
                <i class="fas fa-rocket" aria-hidden="true"></i> Get Started Now
            </a>
            <a href="{{ route('school-registration') }}"
               class="inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/30 text-white font-semibold px-8 py-4 rounded-full text-base transition-all duration-200">
                <i class="fas fa-school text-white/80" aria-hidden="true"></i> Register Your School
            </a>
        </div>
        <p class="text-blue-200/70 text-sm mt-6">
            <i class="fas fa-lock mr-1.5" aria-hidden="true"></i> Free to use. No credit card required.
        </p>
    </div>
</section>

@endsection

@section('scripts')
<script>
(function () {
    var items = document.querySelectorAll('.fade-up');
    if (!('IntersectionObserver' in window)) {
        items.forEach(function(el){ el.classList.add('visible'); });
        return;
    }
    var obs = new IntersectionObserver(function(entries){
        entries.forEach(function(e){
            if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
        });
    }, { threshold: 0.1 });
    items.forEach(function(el){ obs.observe(el); });
})();
</script>
@endsection
