@extends('layouts.dashboard')
@section('title', 'Your Results — PathFindr')
@section('page-title', 'Assessment Results')

@section('styles')
<style>
.result-page { background: var(--pf-bg); min-height: 100vh; }

/* ── Score circle ── */
.score-ring {
    width: 130px; height: 130px; border-radius: 50%;
    position: relative; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.score-ring svg { position: absolute; inset: 0; transform: rotate(-90deg); }
.score-ring .score-num { font-size: 1.8rem; font-weight: 900; color: var(--pf-navy); line-height: 1; }
.score-ring .score-pct { font-size: 0.75rem; color: var(--pf-text3); margin-top: 2px; }

/* ── Bar meter ── */
.bar-track { height: 8px; border-radius: 99px; background: var(--pf-border); overflow: hidden; }
.bar-fill  { height: 100%; border-radius: 99px; transition: width 1.2s ease; }

/* ── Combo card ── */
.combo-card {
    background: linear-gradient(135deg, #1A2A6C, #2D5F9E);
    border-radius: 20px; padding: 32px;
    text-align: center; color: #fff;
    box-shadow: 0 12px 40px rgba(26,42,108,0.35);
}

/* ── Generic result card (uses CSS tokens — auto dark) ── */
.result-card {
    background: var(--pf-surface);
    border: 1px solid var(--pf-border);
    border-radius: 20px;
    box-shadow: 0 4px 20px var(--pf-shadow);
    transition: background 0.25s, border 0.25s;
}

/* ── Inner recommendation item cards ── */
.rec-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px; border-radius: 12px;
    background: #f8faff;
    border: 1px solid #e8f0fe;
    transition: background 0.25s, border 0.25s;
}
html.dark .rec-item {
    background: #0d1117 !important;
    border-color: #30363d !important;
}
html.dark .rec-item-title  { color: #79c0ff !important; }
html.dark .rec-item-sub    { color: #8b949e !important; }

.rec-item-uni {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 14px; border-radius: 12px;
    background: #f8faff;
    border: 1px solid #e8f0fe;
    transition: background 0.25s, border 0.25s;
}
html.dark .rec-item-uni {
    background: #0d1117 !important;
    border-color: #30363d !important;
}

/* Score breakdown card */
.score-breakdown-card {
    background: #fff;
    border: 1px solid #EBF4FF;
    border-radius: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    transition: background 0.25s, border 0.25s;
}
html.dark .score-breakdown-card {
    background: var(--pf-surface) !important;
    border-color: var(--pf-border) !important;
}

/* ── Entrance animation ── */
.result-enter { opacity: 0; transform: translateY(20px); animation: rEnter 0.5s ease forwards; }
@keyframes rEnter { to { opacity: 1; transform: translateY(0); } }

/* ══════════════════════════════════════════
   AXEL CHAT BUBBLE
══════════════════════════════════════════ */
.axel-wrap {
    border-radius: 20px; overflow: hidden;
    box-shadow: 0 8px 32px rgba(26,42,108,0.12);
}
.axel-header {
    background: linear-gradient(135deg, #0f1f5c, #1A2A6C, #2D5F9E);
    padding: 14px 18px;
    display: flex; align-items: center; gap: 12px;
}
.axel-avatar {
    width: 46px; height: 46px; border-radius: 50%;
    background: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; overflow: hidden;
    box-shadow: 0 3px 12px rgba(0,0,0,0.3);
    border: 2px solid rgba(255,255,255,0.35);
}
.axel-avatar img   { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
.axel-avatar span  { font-size: 1.2rem; font-weight: 900; color: #1A2A6C; line-height: 1; }
.axel-name         { font-weight: 800; color: #fff; font-size: 0.92rem; line-height: 1; }
.axel-status       { font-size: 0.7rem; color: rgba(255,255,255,0.55); margin-top: 2px; display: flex; align-items: center; gap: 4px; }
.axel-dot          { width: 6px; height: 6px; border-radius: 50%; background: #43A047; display: inline-block; }

.axel-body { padding: 18px 18px 14px; background: #f1f5f9; }
html.dark .axel-body { background: #161b22 !important; }

.axel-bubble {
    background: #fff; border: 1px solid #e8f0fe;
    border-radius: 4px 18px 18px 18px;
    padding: 14px 16px; max-width: 100%;
    box-shadow: 0 2px 10px rgba(26,42,108,0.07);
}
html.dark .axel-bubble { background: #1c2128 !important; border-color: #30363d !important; }

.axel-typing {
    display: flex; align-items: center; gap: 4px;
    padding: 12px 16px; width: fit-content;
    background: #fff; border: 1px solid #e8f0fe;
    border-radius: 4px 18px 18px 18px;
    box-shadow: 0 2px 10px rgba(26,42,108,0.07);
}
html.dark .axel-typing { background: #1c2128 !important; border-color: #30363d !important; }

.typing-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: #94a3b8; display: inline-block;
    animation: typingBounce 1.2s infinite ease-in-out;
}
.typing-dot:nth-child(2) { animation-delay: 0.2s; }
.typing-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes typingBounce {
    0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
    30%            { transform: translateY(-5px); opacity: 1; }
}

#axel-message-text { font-size: 0.88rem; line-height: 1.75; color: #1e293b; font-style: italic; }
html.dark #axel-message-text { color: #cdd9e5 !important; }

.axel-cursor {
    display: inline-block; width: 2px; height: 1em;
    background: #1A2A6C; margin-left: 1px;
    vertical-align: text-bottom;
    animation: cursorBlink 0.75s step-end infinite;
}
html.dark .axel-cursor { background: #79c0ff !important; }
@keyframes cursorBlink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

.axel-meta {
    display: flex; align-items: center; gap: 6px;
    margin-top: 10px; font-size: 0.7rem; color: #94a3b8; font-style: normal;
}

@media (max-width: 640px) {
    .result-page { min-height: 100dvh; padding-top: max(1rem, env(safe-area-inset-top)); padding-bottom: max(1rem, env(safe-area-inset-bottom)); }
    .score-ring { width: 104px; height: 104px; }
    .score-ring .score-num { font-size: 1.5rem; }
    .combo-card { padding: 24px 18px; border-radius: 16px; }
    .result-card, .score-breakdown-card { border-radius: 16px; }
    .axel-header { gap: 10px; padding: 12px; }
    .axel-status { line-height: 1.4; }
    .axel-body { padding: 14px 12px; }
    .result-page h1 { font-size: 1.75rem; line-height: 1.15; }
    .rec-item, .rec-item-uni { min-width: 0; }
    .rec-item-title, .rec-item-sub { overflow-wrap: anywhere; }
    .result-page > div > div:last-child { align-items: stretch; }
    .result-page > div > div:last-child > a { justify-content: center; min-height: 48px; flex: 1 1 100%; text-align: center; }
}
@media (max-width: 380px) {
    .result-page { padding-left: .75rem; padding-right: .75rem; }
    .result-card.p-6, .score-breakdown-card.p-6 { padding: 1rem; }
    .score-ring { width: 88px; height: 88px; }
    .score-ring .score-num { font-size: 1.25rem; }
    .result-page .flex.items-center.gap-5 { gap: .75rem; }
    .axel-name { font-size: .85rem; }
    .axel-status { max-width: 180px; font-size: .65rem; }
    .axel-body { padding: 12px 10px; }
    .axel-bubble { padding: 12px; }
}
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
}
</style>
@endsection

@section('content')
<div class="result-page p-4 sm:p-6">
<div class="max-w-3xl mx-auto">

    {{-- ── Header ── --}}
    <div class="text-center mb-8 result-enter">
        <div class="w-16 h-16 rounded-full bg-[#43A047] flex items-center justify-center mx-auto mb-4 shadow-lg shadow-green-200">
            <i class="fas fa-check text-white text-2xl"></i>
        </div>
        <h1 class="text-[#1A2A6C] text-3xl font-extrabold mb-2">Assessment Complete!</h1>
        <p class="text-gray-500">Here's how you performed across all four areas.</p>
    </div>

    {{-- ── Overall + Recommended ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">

        {{-- Total score --}}
        <div class="result-card p-6 result-enter flex items-center gap-5" style="animation-delay:0.1s;">
            @php
                $totalPct      = $result->total_score ?? 0;
                $circumference = 2 * pi() * 54;
                $dashOffset    = $circumference * (1 - $totalPct / 100);
                $ringColor     = $totalPct >= 70 ? '#43A047' : ($totalPct >= 50 ? '#F59E0B' : '#EF4444');
            @endphp
            <div class="score-ring">
                <svg viewBox="0 0 120 120" fill="none">
                    <circle cx="60" cy="60" r="54" stroke="#e5e7eb" stroke-width="10"/>
                    <circle cx="60" cy="60" r="54"
                            stroke="{{ $ringColor }}" stroke-width="10"
                            stroke-linecap="round"
                            stroke-dasharray="{{ $circumference }}"
                            stroke-dashoffset="{{ $dashOffset }}"
                            style="transition:stroke-dashoffset 1.5s ease;"/>
                </svg>
                <div style="text-align:center;">
                    <div class="score-num">{{ $totalPct }}<span style="font-size:1rem;color:#9CA3AF;">%</span></div>
                    <div class="score-pct">Overall</div>
                </div>
            </div>
            <div>
                <div class="text-[#1A2A6C] font-bold text-lg">Total Score</div>
                <div class="text-gray-500 text-sm mt-1">
                    @if($totalPct >= 70) Excellent performance!
                    @elseif($totalPct >= 50) Good effort — keep it up!
                    @else Keep practising, you'll improve!
                    @endif
                </div>
                <div class="mt-3 text-xs text-gray-400">Completed {{ $result->created_at->format('d M Y, h:ia') }}</div>
            </div>
        </div>

        {{-- Recommended path --}}
        <div class="combo-card result-enter" style="animation-delay:0.2s;">
            <div class="text-xs font-bold uppercase tracking-widest text-white/50 mb-2">Recommended Path</div>
            @if($result->recommended_combination)
            <div class="text-4xl font-extrabold mb-3">{{ $result->recommended_combination }}</div>
            @php
                $comboDesc = match($result->recommended_combination) {
                    'Science'    => 'Physics · Chemistry · Biology · Mathematics',
                    'Commercial' => 'Economics · Accounting · Commerce · Business Studies',
                    'Arts'       => 'Literature · Government · History · CRK',
                    default      => '',
                };
            @endphp
            <div class="text-white/70 text-sm">{{ $comboDesc }}</div>
            @else
            <div class="text-2xl font-extrabold mb-3">Not Determined</div>
            <div class="text-white/60 text-sm leading-relaxed">
                You answered fewer than 10 questions. Please retake the assessment for a recommendation.
            </div>
            @endif
            <div class="mt-5 inline-flex items-center gap-2 bg-white/15 px-4 py-2 rounded-full text-sm font-semibold">
                <i class="fas fa-robot text-[#F5A623]"></i> Matched by Axel
            </div>
        </div>
    </div>

    {{-- ── Category Scores ── --}}
    <div class="score-breakdown-card p-6 mb-6 result-enter" style="animation-delay:0.3s;">
        <h2 class="text-[#1A2A6C] font-bold text-base mb-5 flex items-center gap-2">
            <i class="fas fa-chart-bar text-[#F5A623] text-sm"></i> Score Breakdown
        </h2>
        @php
            $cats = [
                ['label'=>'Numerical Reasoning', 'score'=>$result->numerical_score ?? 0, 'color'=>'#1A2A6C', 'icon'=>'fa-calculator'],
                ['label'=>'Verbal Reasoning',    'score'=>$result->verbal_score    ?? 0, 'color'=>'#2D5F9E', 'icon'=>'fa-comment-dots'],
                ['label'=>'Spatial Awareness',   'score'=>$result->spatial_score   ?? 0, 'color'=>'#7C3AED', 'icon'=>'fa-cube'],
                ['label'=>'Personal Interest',   'score'=>$result->interest_score  ?? 0, 'color'=>'#43A047', 'icon'=>'fa-heart'],
            ];
        @endphp
        <div class="space-y-5">
            @foreach($cats as $cat)
            <div>
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center text-xs"
                             style="background:{{ $cat['color'] }}18; color:{{ $cat['color'] }};">
                            <i class="fas {{ $cat['icon'] }}"></i>
                        </div>
                        <span class="font-semibold text-sm" style="color:var(--pf-text);">{{ $cat['label'] }}</span>
                    </div>
                    <span class="font-bold text-sm" style="color:var(--pf-navy);">{{ $cat['score'] }}%</span>
                </div>
                <div class="bar-track">
                    <div class="bar-fill" style="width:{{ $cat['score'] }}%; background:{{ $cat['color'] }};"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ── AI Career Recommendations ── --}}
    @if(!empty($result->careers) && count($result->careers) > 0)
    @php
        function careerIcon(string $name): array {
            $n = strtolower($name);
            if (str_contains($n, 'doctor') || str_contains($n, 'physician') || str_contains($n, 'medical'))
                return ['fa-user-md', '#EF4444'];
            if (str_contains($n, 'nurse') || str_contains($n, 'nursing'))
                return ['fa-notes-medical', '#ec4899'];
            if (str_contains($n, 'pharmacist') || str_contains($n, 'pharmacy'))
                return ['fa-pills', '#8b5cf6'];
            if (str_contains($n, 'software') && str_contains($n, 'engineer'))
                return ['fa-code', '#2D5F9E'];
            if ((str_contains($n, 'civil') || str_contains($n, 'structural')) && str_contains($n, 'engineer'))
                return ['fa-hard-hat', '#f97316'];
            if (str_contains($n, 'electrical') && str_contains($n, 'engineer'))
                return ['fa-bolt', '#F5A623'];
            if (str_contains($n, 'mechanical') && str_contains($n, 'engineer'))
                return ['fa-cogs', '#64748b'];
            if (str_contains($n, 'petroleum') && str_contains($n, 'engineer'))
                return ['fa-oil-can', '#92400e'];
            if (str_contains($n, 'engineer'))
                return ['fa-wrench', '#f97316'];
            if (str_contains($n, 'architect'))
                return ['fa-drafting-compass', '#b45309'];
            if (str_contains($n, 'lawyer') || str_contains($n, 'attorney') || str_contains($n, 'legal'))
                return ['fa-gavel', '#7C3AED'];
            if (str_contains($n, 'judge'))
                return ['fa-balance-scale', '#7C3AED'];
            if (str_contains($n, 'accountant') || str_contains($n, 'accounting') || str_contains($n, 'auditor'))
                return ['fa-file-invoice-dollar', '#2E7D32'];
            if (str_contains($n, 'banker') || str_contains($n, 'banking'))
                return ['fa-landmark', '#1A2A6C'];
            if (str_contains($n, 'finance') || str_contains($n, 'financial'))
                return ['fa-coins', '#2E7D32'];
            if (str_contains($n, 'economist') || str_contains($n, 'economics'))
                return ['fa-chart-line', '#2D5F9E'];
            if (str_contains($n, 'analyst') || str_contains($n, 'data'))
                return ['fa-chart-bar', '#2D5F9E'];
            if (str_contains($n, 'entrepreneur') || str_contains($n, 'business'))
                return ['fa-store', '#F5A623'];
            if (str_contains($n, 'teacher') || str_contains($n, 'educator') || str_contains($n, 'lecturer'))
                return ['fa-chalkboard-teacher', '#43A047'];
            if (str_contains($n, 'journalist') || str_contains($n, 'reporter'))
                return ['fa-newspaper', '#0284c7'];
            if (str_contains($n, 'writer') || str_contains($n, 'author'))
                return ['fa-pen-fancy', '#7C3AED'];
            if (str_contains($n, 'designer') || str_contains($n, 'graphic'))
                return ['fa-paint-brush', '#ec4899'];
            if (str_contains($n, 'scientist') || str_contains($n, 'researcher') || str_contains($n, 'biologist'))
                return ['fa-microscope', '#0284c7'];
            if (str_contains($n, 'computer') || str_contains($n, 'developer') || str_contains($n, 'programmer'))
                return ['fa-laptop-code', '#2D5F9E'];
            if (str_contains($n, 'diplomat') || str_contains($n, 'foreign'))
                return ['fa-globe-africa', '#1A2A6C'];
            if (str_contains($n, 'pilot') || str_contains($n, 'aviation'))
                return ['fa-plane', '#0284c7'];
            if (str_contains($n, 'marketer') || str_contains($n, 'marketing'))
                return ['fa-bullhorn', '#F5A623'];
            if (str_contains($n, 'psychologist') || str_contains($n, 'therapist'))
                return ['fa-brain', '#7C3AED'];
            if (str_contains($n, 'agricultur') || str_contains($n, 'farmer'))
                return ['fa-seedling', '#2E7D32'];
            if (str_contains($n, 'chef') || str_contains($n, 'catering'))
                return ['fa-utensils', '#f97316'];
            if (str_contains($n, 'public relation') || str_contains($n, 'communication'))
                return ['fa-comments', '#0284c7'];
            return ['fa-briefcase', '#64748b'];
        }
    @endphp
    <div class="result-card p-6 mb-6 result-enter" style="animation-delay:0.35s;">
        <h2 class="font-bold text-base mb-5 flex items-center gap-2" style="color:var(--pf-navy);">
            <i class="fas fa-briefcase text-[#F5A623] text-sm"></i>
            Career Paths Matched For You
            <span class="ml-auto text-[10px] font-semibold text-white bg-[#2D5F9E] px-2 py-0.5 rounded-full uppercase tracking-wide">Axel</span>
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            @foreach($result->careers as $career)
            @php
                $careerName = is_array($career) ? ($career['title'] ?? $career['name'] ?? '') : $career;
                [$cIcon, $cColor] = careerIcon($careerName);
            @endphp
            <div class="rec-item">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                     style="background:{{ $cColor }}18;">
                    <i class="fas {{ $cIcon }} text-sm" style="color:{{ $cColor }};"></i>
                </div>
                <span class="rec-item-title font-semibold text-sm text-[#1A2A6C]">{{ $careerName }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── University Recommendations ── --}}
    @if(!empty($result->universities) && count($result->universities) > 0)
    <div class="result-card p-6 mb-6 result-enter" style="animation-delay:0.4s;">
        <h2 class="font-bold text-base mb-5 flex items-center gap-2" style="color:var(--pf-navy);">
            <i class="fas fa-university text-[#F5A623] text-sm"></i>
            Suggested Nigerian Universities
            <span class="ml-auto text-[10px] font-semibold text-white bg-[#2D5F9E] px-2 py-0.5 rounded-full uppercase tracking-wide">Axel</span>
        </h2>
        <div class="space-y-3">
            @foreach($result->universities as $i => $uni)
            @php
                $uniName = is_array($uni) ? ($uni['name'] ?? '') : $uni;
                $course  = is_array($uni) ? ($uni['course'] ?? '') : '';
            @endphp
            <div class="rec-item-uni">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 text-white font-extrabold text-sm"
                     style="background:linear-gradient(135deg,#1A2A6C,#2D5F9E);">
                    {{ $i + 1 }}
                </div>
                <div>
                    <div class="rec-item-title font-bold text-sm text-[#1A2A6C]">{{ $uniName }}</div>
                    @if($course)
                    <div class="rec-item-sub text-xs mt-0.5 flex items-center gap-1 text-[#64748b]">
                        <i class="fas fa-book-open text-[10px]"></i> {{ $course }}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ══ AXEL TYPING ANIMATION ══ --}}
    @if(!empty($result->motivational_message))
    <div class="axel-wrap mb-6 result-enter" style="animation-delay:0.45s;">

        {{-- Header --}}
        <div class="axel-header">
            <div class="axel-avatar" id="axel-avatar-wrap">
                {{-- Image with JS fallback to letter --}}
                <img src="{{ asset('images/axel.png') }}" alt="Axel"
                     id="axel-img"
                     style="width:100%;height:100%;object-fit:cover;border-radius:50%;"
                     onerror="this.style.display='none';document.getElementById('axel-fallback').style.display='flex';">
                <span id="axel-fallback"
                      style="display:none;font-size:1.2rem;font-weight:900;color:#1A2A6C;">A</span>
            </div>
            <div>
                <div class="axel-name">Axel</div>
                <div class="axel-status">
                    <span class="axel-dot"></span> In-Built AI Career Counsellor · PathFindr
                </div>
            </div>
            <div class="ml-auto">
                <span style="font-size:0.7rem;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);color:rgba(255,255,255,0.8);padding:3px 10px;border-radius:99px;font-weight:700;">
                    <i class="fas fa-robot text-[10px] mr-1"></i> Axel AI
                </span>
            </div>
        </div>

        {{-- Chat body --}}
        <div class="axel-body">
            {{-- Typing indicator --}}
            <div id="axel-typing-indicator" class="axel-typing mb-3">
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
                <span class="typing-dot"></span>
            </div>
            {{-- Message bubble --}}
            <div id="axel-message-bubble" class="axel-bubble" style="display:none;">
                <p id="axel-message-text"></p>
                <div class="axel-meta">
                    <i class="fas fa-check-double" style="color:#2D5F9E;"></i>
                    <span>Axel · PathFindr's In-Built AI</span>
                </div>
            </div>
        </div>
    </div>

    <script>
    (function () {
        var message   = {{ Js::from($result->motivational_message) }};
        var indicator = document.getElementById('axel-typing-indicator');
        var bubble    = document.getElementById('axel-message-bubble');
        var textEl    = document.getElementById('axel-message-text');

        // Also check the image loaded correctly
        var img = document.getElementById('axel-img');
        if (img && img.complete && img.naturalWidth === 0) {
            img.style.display = 'none';
            document.getElementById('axel-fallback').style.display = 'flex';
        }

        setTimeout(function () {
            indicator.style.display = 'none';
            bubble.style.display    = 'block';

            var i      = 0;
            var cursor = document.createElement('span');
            cursor.className = 'axel-cursor';
            textEl.appendChild(cursor);

            var speed = Math.max(18, Math.min(40, Math.round(3000 / message.length)));

            function type() {
                if (i < message.length) {
                    textEl.insertBefore(document.createTextNode(message[i]), cursor);
                    i++;
                    setTimeout(type, speed);
                } else {
                    setTimeout(function () {
                        cursor.style.animation = 'none';
                        cursor.style.opacity   = '0';
                    }, 1200);
                }
            }
            type();
        }, 2500);
    })();
    </script>
    @endif

    {{-- ── Actions ── --}}
    <div class="flex flex-wrap gap-3 result-enter" style="animation-delay:0.5s;">
        <a href="{{ route('student.dashboard') }}"
           class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#2D5F9E] text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all duration-200 shadow-md">
            <i class="fas fa-th-large text-xs"></i> Back to Dashboard
        </a>
        <a href="{{ route('exam.result.pdf', $result->id) }}"
           class="inline-flex items-center gap-2 bg-[#43A047] hover:bg-emerald-600 text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all duration-200 shadow-md">
            <i class="fas fa-file-pdf text-xs"></i> Download PDF Report
        </a>
        <a href="{{ route('profile.edit') }}"
           class="inline-flex items-center gap-2 border border-[#2D5F9E] text-[#2D5F9E] hover:bg-[#EBF4FF] font-semibold px-6 py-3 rounded-xl text-sm transition-all duration-200">
            <i class="fas fa-user-edit text-xs"></i> View Profile
        </a>
    </div>

</div>
</div>
@endsection
