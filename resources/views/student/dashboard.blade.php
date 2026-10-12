@extends('layouts.dashboard')

@section('title', 'My Dashboard — PathFindr')
@section('page-title', 'Student Dashboard')

@section('styles')
<style>
/* ── Typewriter ── */
.typewriter-wrap { display:inline-block; max-width:100%; }
.typewriter-text {
    display:inline-block; overflow:hidden; white-space:nowrap;
    border-right:3px solid #F5A623; width:0; vertical-align:bottom;
    animation: typing .6s steps(30,end) .1s forwards,
               blink .6s step-end 3,
               hide-cursor 0s forwards 1.5s;
}
@keyframes typing     { from{width:0}  to{width:max-content} }
@keyframes blink      { 50%{border-color:transparent} }
@keyframes hide-cursor{ to{border-color:transparent} }

/* ── Entrance animations ── */
.fade-in-sub  { opacity:0; animation:fadeUp .35s ease forwards .8s; }
.cta-pulse    { opacity:0; animation:fadeUp .3s  ease forwards 1.0s; }
.card-in      { opacity:0; transform:translateY(14px); animation:cardIn .4s ease forwards; }
@keyframes fadeUp { from{opacity:0;transform:translateY(6px)} to{opacity:1;transform:translateY(0)} }
@keyframes cardIn { to{opacity:1;transform:translateY(0)} }

/* ── Shared button base ── */
.btn {
    display:inline-flex; align-items:center; gap:8px;
    font-weight:700; font-size:.82rem; letter-spacing:.01em;
    border-radius:12px; padding:10px 20px;
    border:none; cursor:pointer; transition:all .2s ease;
    white-space:nowrap; text-decoration:none;
    box-shadow:0 2px 8px rgba(0,0,0,.08);
}
.btn:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(0,0,0,.13); }
.btn:active{ transform:translateY(0);    box-shadow:0 2px 6px rgba(0,0,0,.10); }

/* Primary — deep navy */
.btn-primary { background:#1A2A6C; color:#fff; }
.btn-primary:hover { background:#0f1f5c; }

/* Success — forest green */
.btn-success { background:#2E7D32; color:#fff; }
.btn-success:hover { background:#1b5e20; }

/* Gold — attention/CTA */
.btn-gold { background:#F5A623; color:#0f172a; }
.btn-gold:hover { background:#e09516; }

/* Outlined navy */
.btn-outline {
    background:transparent; color:#1A2A6C;
    border:2px solid #1A2A6C; box-shadow:none;
}
.btn-outline:hover { background:#1A2A6C; color:#fff; box-shadow:0 4px 14px rgba(26,42,108,.25); }

/* Ghost — subtle secondary */
.btn-ghost {
    background:#f1f5f9; color:#334155;
    border:1px solid #e2e8f0; box-shadow:none;
}
.btn-ghost:hover { background:#e2e8f0; color:#1A2A6C; }

/* Disabled */
.btn-disabled {
    background:#f1f5f9; color:#94a3b8;
    border:1px solid #e2e8f0; cursor:not-allowed;
    box-shadow:none; pointer-events:none;
}

/* Pill shape variant */
.btn-pill { border-radius:999px; padding:10px 24px; }

/* Large */
.btn-lg { padding:13px 28px; font-size:.9rem; border-radius:14px; }

/* ── Welcome banner CTA buttons ── */
.banner-btn-primary {
    display:inline-flex; align-items:center; gap:8px;
    background:#F5A623; color:#0f172a;
    font-weight:800; font-size:.85rem;
    padding:11px 26px; border-radius:999px;
    transition:all .2s ease;
    box-shadow:0 4px 16px rgba(245,166,35,.35);
}
.banner-btn-primary:hover { background:#e09516; transform:translateY(-1px); box-shadow:0 8px 24px rgba(245,166,35,.4); }

.banner-btn-blue {
    display:inline-flex; align-items:center; gap:8px;
    background:#2D5F9E; color:#fff;
    font-weight:800; font-size:.85rem;
    padding:11px 26px; border-radius:999px;
    transition:all .2s ease;
    box-shadow:0 4px 16px rgba(45,95,158,.35);
}
.banner-btn-blue:hover { background:#1A2A6C; transform:translateY(-1px); box-shadow:0 8px 24px rgba(26,42,108,.4); }

.banner-btn-green {
    display:inline-flex; align-items:center; gap:8px;
    background:#2E7D32; color:#fff;
    font-weight:800; font-size:.85rem;
    padding:11px 26px; border-radius:999px;
    transition:all .2s ease;
    box-shadow:0 4px 16px rgba(46,125,50,.3);
}
.banner-btn-green:hover { background:#1b5e20; transform:translateY(-1px); box-shadow:0 8px 24px rgba(46,125,50,.35); }

/* ── Score preview / no-result info box ── */
.score-preview-box { background:#f8faff; }

/* ── Testimonial card ── */
.testimonial-box { background:#fff; }

/* ── Dark mode overrides — student dashboard cards ── */
html.dark .action-card  {
    background:#161b22 !important;
    border-color:#30363d !important;
    color:#e6edf3 !important;
}
html.dark .action-card:hover { border-color:#388bfd !important; box-shadow:0 12px 40px rgba(0,0,0,.4) !important; }
html.dark .action-card h2   { color:#e6edf3 !important; }
html.dark .action-card p    { color:#8b949e !important; }
html.dark .info-card {
    background:#161b22 !important;
    border-color:#30363d !important;
}
html.dark .info-card:hover  { border-color:#388bfd !important; }
html.dark .info-card .text-\[\#0f172a\] { color:#e6edf3 !important; }
html.dark .info-card .text-\[\#64748b\] { color:#8b949e !important; }
html.dark .score-preview-box {
    background:#0d1117 !important;
    border-color:#30363d !important;
}
html.dark .testimonial-box  {
    background:#161b22 !important;
    border-color:#30363d !important;
}
html.dark .btn-disabled { background:#21262d !important; color:#6e7681 !important; border-color:#30363d !important; }
html.dark .status-badge[style*="background:#f8fafc"] {
    background:#21262d !important;
    border-color:#30363d !important;
    color:#8b949e !important;
}

/* ── Action card ── */
.action-card {
    background:#fff;
    border:1.5px solid #e8f0fe;
    border-radius:20px;
    padding:24px;
    transition:all .25s ease;
    position:relative; overflow:hidden;
}
.action-card::before {
    content:''; position:absolute; inset:0; opacity:0;
    background:linear-gradient(135deg,rgba(26,42,108,.02),rgba(67,160,71,.02));
    transition:opacity .25s;
}
.action-card:hover { border-color:#c7d7fd; box-shadow:0 12px 40px rgba(26,42,108,.09); transform:translateY(-2px); }
.action-card:hover::before { opacity:1; }

/* ── Info strip card ── */
.info-card {
    background:#fff;
    border:1.5px solid #e8f0fe;
    border-radius:16px; padding:16px;
    display:flex; align-items:center; gap:12px;
    transition:all .2s ease;
}
.info-card:hover { border-color:#c7d7fd; box-shadow:0 4px 16px rgba(26,42,108,.07); }

/* ── Status badge ── */
.status-badge {
    display:inline-flex; align-items:center; gap:5px;
    font-size:.7rem; font-weight:700; letter-spacing:.04em;
    padding:4px 10px; border-radius:999px; border:1.5px solid;
}
</style>
@endsection

@section('content')
@php
    $hasResult = \App\Models\TestResult::where('user_id', auth()->id())->exists();
    $result    = $hasResult ? \App\Models\TestResult::where('user_id', auth()->id())->latest()->first() : null;
@endphp
<div class="p-4 sm:p-6 lg:p-8">

    {{-- ══ WELCOME BANNER ══ --}}
    <div class="rounded-2xl p-7 mb-6 relative overflow-hidden"
         style="background:linear-gradient(135deg,#0f1f5c 0%,#1A2A6C 55%,#2D5F9E 100%);">

        {{-- Decorative blobs --}}
        <div class="absolute top-0 right-0 w-52 h-52 rounded-full pointer-events-none"
             style="background:radial-gradient(circle,rgba(255,255,255,.06),transparent);transform:translate(30%,-30%);"></div>
        <div class="absolute bottom-0 left-1/4 w-40 h-40 rounded-full pointer-events-none"
             style="background:radial-gradient(circle,rgba(67,160,71,.12),transparent);transform:translateY(40%);"></div>
        <div class="absolute top-1/2 right-1/4 w-24 h-24 rounded-full pointer-events-none"
             style="background:radial-gradient(circle,rgba(245,166,35,.10),transparent);"></div>

        <div class="relative z-10 flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1.5 bg-white/10 border border-white/15 text-white/70 text-[10px] font-semibold uppercase tracking-widest px-3 py-1 rounded-full">
                        <i class="fas fa-calendar-alt text-[9px]"></i>{{ now()->format('d F Y') }}
                    </span>
                </div>
                <p class="text-white/60 text-sm font-medium mb-0.5">Welcome back,</p>
                <div class="overflow-hidden mb-2">
                    <div class="typewriter-wrap">
                        <h1 class="typewriter-text text-white text-2xl sm:text-3xl font-extrabold tracking-tight">
                            {{ auth()->user()->name }}
                        </h1>
                    </div>
                </div>
                <p class="fade-in-sub font-semibold text-sm mb-5"
                   style="color:#F5A623;">
                    {{ $hasResult ? '🎉 Your results are ready to review!' : '✨ Ready to discover your path today?' }}
                </p>
                <div class="cta-pulse flex flex-wrap gap-3">
                    @if($hasResult)
                    <a href="{{ route('student.results') }}" class="banner-btn-blue">
                        <i class="fas fa-chart-bar text-xs"></i> View My Results
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                    <a href="{{ route('student.reports') }}" class="banner-btn-green">
                        <i class="fas fa-file-pdf text-xs"></i> PDF Reports
                    </a>
                    @else
                    <a href="{{ route('exam.start') }}" class="banner-btn-green">
                        <i class="fas fa-rocket text-xs"></i> Start Assessment
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Avatar --}}
            <a href="{{ route('profile.edit') }}" class="shrink-0 group relative" title="Update Profile">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}"
                     class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover shadow-xl transition-all duration-200"
                     style="border:2.5px solid rgba(255,255,255,.25);"
                     onmouseover="this.style.borderColor='#F5A623'" onmouseout="this.style.borderColor='rgba(255,255,255,.25)'">
                <div class="absolute -bottom-1.5 -right-1.5 w-7 h-7 rounded-full flex items-center justify-center shadow-md"
                     style="background:#F5A623;">
                    <i class="fas fa-camera text-[#0f172a] text-[9px]"></i>
                </div>
            </a>
        </div>
    </div>

    {{-- ══ ACTION CARDS ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">

        {{-- Take / Retake Test --}}
        <div class="action-card card-in" style="animation-delay:.1s;">
            <div class="flex items-start justify-between mb-5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md"
                     style="background:linear-gradient(135deg,#1A2A6C,#2D5F9E);">
                    <i class="fas fa-pencil-alt text-white text-lg"></i>
                </div>
                <span class="status-badge" style="color:#2E7D32;background:#e8f5e9;border-color:#a5d6a7;">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32] animate-pulse inline-block"></span>
                    Available
                </span>
            </div>
            <h2 class="text-[#0f172a] text-lg font-extrabold mb-1.5 tracking-tight">
                {{ $hasResult ? 'Retake Assessment' : 'Take the Test' }}
            </h2>
            <p class="text-[#64748b] text-sm leading-relaxed mb-4">
                Complete the 30-minute psychometric assessment across 4 key areas — Numerical, Verbal, Spatial, and Interest.
            </p>
            <div class="flex gap-3 mb-5 text-xs text-[#94a3b8]">
                <span class="flex items-center gap-1.5 bg-[#fef9ec] border border-[#fde68a] text-[#92400e] px-2.5 py-1 rounded-full font-semibold">
                    <i class="fas fa-clock text-[10px]"></i> 30 min
                </span>
                <span class="flex items-center gap-1.5 bg-[#eff6ff] border border-[#bfdbfe] text-[#1e40af] px-2.5 py-1 rounded-full font-semibold">
                    <i class="fas fa-layer-group text-[10px]"></i> 4 sections
                </span>
                <span class="flex items-center gap-1.5 bg-[#f0fdf4] border border-[#bbf7d0] text-[#166534] px-2.5 py-1 rounded-full font-semibold">
                    <i class="fas fa-certificate text-[10px]"></i> Free
                </span>
            </div>
            <a href="{{ route('exam.start') }}" class="btn btn-success">
                <i class="fas fa-play-circle"></i>
                {{ $hasResult ? 'Retake Now' : 'Begin Assessment' }}
            </a>
        </div>

        {{-- My Results --}}
        <div class="action-card card-in" style="animation-delay:.2s;">
            <div class="flex items-start justify-between mb-5">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-md"
                     style="background:{{ $hasResult ? 'linear-gradient(135deg,#1A2A6C,#3b5bdb)' : 'linear-gradient(135deg,#e2e8f0,#cbd5e1)' }};">
                    <i class="fas fa-chart-bar text-lg {{ $hasResult ? 'text-white' : 'text-slate-400' }}"></i>
                </div>
                @if($hasResult)
                <span class="status-badge" style="color:#1A2A6C;background:#eff6ff;border-color:#bfdbfe;">
                    <i class="fas fa-check-circle text-[9px]"></i> Ready
                </span>
                @else
                <span class="status-badge" style="color:#64748b;background:#f8fafc;border-color:#e2e8f0;">
                    <i class="fas fa-lock text-[9px]"></i> Locked
                </span>
                @endif
            </div>
            <h2 class="text-[#0f172a] text-lg font-extrabold mb-1.5 tracking-tight">My Results</h2>
            <p class="text-[#64748b] text-sm leading-relaxed mb-4">
                View your scores, AI-generated career recommendations, subject combination, and full assessment history.
            </p>

            @if($hasResult && $result)
            <div class="score-preview-box flex items-center gap-3 mb-5 p-3 rounded-xl"
                 style="border:1.5px solid #e8f0fe;">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                     style="background:linear-gradient(135deg,#1A2A6C,#2D5F9E);">
                    <span class="text-white font-extrabold text-sm leading-none">{{ round($result->total_score,0) }}<span class="text-[9px]">%</span></span>
                </div>
                <div>
                    <div class="text-[#1A2A6C] text-sm font-bold">Score: {{ round($result->total_score,1) }}%</div>
                    <div class="text-[#64748b] text-xs mt-0.5">
                        {{ $result->recommended_combination ?? 'Processing...' }}
                        &nbsp;·&nbsp; {{ $result->created_at->format('d M Y') }}
                    </div>
                </div>
            </div>
            <a href="{{ route('student.results') }}" class="btn btn-primary">
                <i class="fas fa-chart-bar"></i> View Full Results
            </a>
            @else
            <div class="score-preview-box flex items-start gap-3 mb-5 p-3 rounded-xl"
                 style="border:1.5px solid #e8f0fe;">
                <i class="fas fa-info-circle text-[#2D5F9E] mt-0.5 text-sm shrink-0"></i>
                <div>
                    <div class="text-[#1A2A6C] text-sm font-semibold">No Results Yet</div>
                    <div class="text-[#64748b] text-xs mt-0.5">Complete the assessment to unlock your results.</div>
                </div>
            </div>
            <button class="btn btn-disabled">
                <i class="fas fa-lock"></i> Results Locked
            </button>
            @endif
        </div>
    </div>

    {{-- ══ INFO STRIP ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- PDF Report --}}
        @if($hasResult)
        <a href="{{ route('student.reports') }}" class="info-card group" style="text-decoration:none;">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#f97316,#ea580c);">
                <i class="fas fa-file-pdf text-white text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm" style="color:var(--pf-text);">PDF Report</div>
                <div class="text-xs mt-0.5" style="color:var(--pf-text2);">Download &amp; share</div>
            </div>
            <i class="fas fa-chevron-right text-xs group-hover:text-[#1A2A6C] transition-colors" style="color:var(--pf-text3);"></i>
        </a>
        @else
        <div class="info-card opacity-60">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                 style="background:#f1f5f9;">
                <i class="fas fa-file-pdf text-[#94a3b8] text-sm"></i>
            </div>
            <div>
                <div class="font-bold text-sm" style="color:var(--pf-text);">PDF Report</div>
                <div class="text-xs mt-0.5" style="color:var(--pf-text2);">Available after test</div>
            </div>
        </div>
        @endif

        {{-- AI Recommendations --}}
        <div class="info-card">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#2E7D32,#43A047);">
                <i class="fas fa-robot text-white text-sm"></i>
            </div>
            <div>
                <div class="font-bold text-sm" style="color:var(--pf-text);">AI Recommendations</div>
                <div class="text-xs mt-0.5" style="color:var(--pf-text2);">Powered by Axel</div>
            </div>
        </div>

        {{-- University Matches --}}
        <div class="info-card">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#7C3AED,#6d28d9);">
                <i class="fas fa-university text-white text-sm"></i>
            </div>
            <div>
                <div class="font-bold text-sm" style="color:var(--pf-text);">University Matches</div>
                <div class="text-xs mt-0.5" style="color:var(--pf-text2);">Nigerian universities</div>
            </div>
        </div>
    </div>

    {{-- ══ TESTIMONIAL ══ --}}
    @php $existing = auth()->user()->testimonial ?? null; @endphp
    <div class="testimonial-box rounded-2xl p-6" style="border:1.5px solid #e8f0fe;box-shadow:0 1px 4px rgba(0,0,0,.04);">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#F5A623,#f97316);">
                <i class="fas fa-comment-dots text-white text-sm"></i>
            </div>
            <div>
                <div class="font-bold text-sm" style="color:var(--pf-text);">Share Your Experience</div>
                <div class="text-xs mt-0.5" style="color:var(--pf-text2);">Your review may appear on the PathFindr homepage</div>
            </div>
        </div>

        @if(session('testimonial_success'))
        <div class="flex items-center gap-2 bg-[#f0fdf4] border border-[#bbf7d0] text-[#166534] rounded-xl px-4 py-3 mb-4 text-sm">
            <i class="fas fa-check-circle"></i> {{ session('testimonial_success') }}
        </div>
        @endif

        <form method="POST" action="{{ route('testimonials.store') }}">
            @csrf
            {{-- Stars --}}
            <div class="flex items-center gap-1 mb-4">
                @for($s = 1; $s <= 5; $s++)
                <button type="button" onclick="setRating({{ $s }})"
                        class="star-btn text-2xl transition-transform duration-100 hover:scale-110"
                        data-val="{{ $s }}">
                    <i class="fa-star text-xl {{ ($existing && $existing->rating >= $s) ? 'fas text-amber-400' : 'far text-gray-300' }}"></i>
                </button>
                @endfor
                <input type="hidden" name="rating" id="rating-input" value="{{ $existing->rating ?? 5 }}">
            </div>

            <textarea name="body" rows="3"
                      placeholder="Tell us how PathFindr helped you find your path..."
                      class="w-full rounded-xl px-4 py-3 text-sm text-[#334155] resize-none focus:outline-none focus:ring-2 transition-all"
                      style="border:1.5px solid #e2e8f0;background:#f8fafc;focus-ring-color:#1A2A6C;"
                      maxlength="500">{{ $existing->body ?? '' }}</textarea>

            <div class="flex items-center justify-between mt-4">
                @if($existing)
                <span class="flex items-center gap-1.5 text-xs font-medium"
                      style="{{ $existing->approved ? 'color:#2E7D32' : 'color:#92400e' }}">
                    <i class="fas {{ $existing->approved ? 'fa-check-circle' : 'fa-clock' }}"></i>
                    {{ $existing->approved ? 'Published on homepage' : 'Pending review' }}
                </span>
                @else
                <span class="text-xs text-[#94a3b8]">Reviews are moderated before publishing</span>
                @endif

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane text-xs"></i>
                    {{ $existing ? 'Update Review' : 'Submit Review' }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script>
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('.star-btn').forEach(btn => {
        const i = btn.querySelector('i');
        const v = parseInt(btn.dataset.val);
        i.className = v <= val
            ? 'fas fa-star text-xl text-amber-400'
            : 'far fa-star text-xl text-gray-300';
    });
}
</script>
@endsection
