<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>PathFindr Assessment</title>
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*,*::before,*::after { box-sizing: border-box; margin: 0; padding: 0; }
html, body { height: 100%; font-family: 'Inter', sans-serif; overflow-x: hidden; }

/* ══ THEME ══ */
:root {
    --bg: #EBF4FF; --bg2: #fff; --border: #EBF4FF;
    --navy: #1A2A6C; --royal: #2D5F9E; --green: #43A047;
    --text: #2D3748; --text2: #6B7280; --text3: #9CA3AF;
    --opt-bg: rgba(255,255,255,0.9); --opt-hover: #EBF4FF;
    --topbar-bg: rgba(255,255,255,0.95);
    --shadow: rgba(26,42,108,0.07);
    --dot-bg: rgba(26,42,108,0.15);
}
html.dark {
    --bg: #0d1117; --bg2: #161b22; --border: #30363d;
    --navy: #58a6ff; --royal: #79c0ff; --green: #56d364;
    --text: #e6edf3; --text2: #8b949e; --text3: #6e7681;
    --opt-bg: rgba(22,27,34,0.95); --opt-hover: #1c2128;
    --topbar-bg: rgba(13,17,23,0.97);
    --shadow: rgba(0,0,0,0.4);
    --dot-bg: rgba(88,166,255,0.2);
}
body { background: var(--bg); color: var(--text); transition: background 0.3s, color 0.3s; }

/* ══ COUNTDOWN ══ */
#countdown-overlay {
    position: fixed; inset: 0; z-index: 9999;
    background: linear-gradient(135deg, #0a1540, #1A2A6C, #0d3320);
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 20px;
}
.cd-logo  { height: 60px; animation: popIn .6s ease; }
.cd-label { color: rgba(255,255,255,.5); font-size: .9rem; letter-spacing: .15em; text-transform: uppercase; }
.cd-num {
    font-size: clamp(5rem,20vw,9rem); font-weight: 900; color: #fff; line-height: 1;
    text-shadow: 0 0 60px rgba(67,160,71,.7);
    animation: popIn .35s cubic-bezier(.34,1.5,.64,1);
}
.cd-go {
    font-size: clamp(2.5rem,10vw,5rem); font-weight: 900; color: #43A047;
    text-shadow: 0 0 40px rgba(67,160,71,.9);
    animation: popIn .35s cubic-bezier(.34,1.5,.64,1);
}
.cd-bar { width: min(260px,80vw); height: 4px; background: rgba(255,255,255,.15); border-radius: 99px; overflow: hidden; }
.cd-bar-fill { height: 100%; background: #43A047; border-radius: 99px; transition: width .9s linear; }
@keyframes popIn { from { opacity:0; transform:scale(.5); } to { opacity:1; transform:scale(1); } }

/* ══ EXIT HINT — only shows on mouse hover near top, z higher than topbar ══ */
#exit-hint {
    position: fixed; top: 0; left: 0; right: 0; z-index: 9000;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    background: rgba(239,68,68,.97); color: #fff;
    padding: 12px 20px; font-size: .85rem; font-weight: 700;
    transform: translateY(-100%); transition: transform .25s ease;
    cursor: pointer; user-select: none;
}
#exit-hint.show { transform: translateY(0); }

/* ══ TOP BAR ══ */
#top-bar {
    position: sticky; top: 0; z-index: 200;
    background: var(--topbar-bg); backdrop-filter: blur(14px);
    border-bottom: 1px solid var(--border);
    padding: 10px 16px;
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    box-shadow: 0 2px 12px var(--shadow);
    transition: background .3s;
}
.top-logo { height: 32px; width: auto; }
.top-div  { width: 1px; height: 26px; background: var(--border); flex-shrink: 0; }
.prog-track { flex: 1; min-width: 100px; max-width: 220px; height: 6px; background: var(--border); border-radius: 99px; overflow: hidden; }
.prog-fill  { height: 100%; border-radius: 99px; background: linear-gradient(90deg,var(--navy),var(--green)); transition: width .4s ease; }
#progress-text { font-size: .72rem; font-weight: 700; color: var(--navy); white-space: nowrap; }
.timer-wrap { display: flex; align-items: center; gap: 6px; background: var(--border); padding: 6px 12px; border-radius: 12px; }
#timer { font-family: 'Courier New',monospace; font-weight: 800; font-size: 1rem; }
.t-normal  { color: var(--navy); }
.t-warning { color: #F59E0B; animation: blink 1s ease infinite; }
.t-danger  { color: #EF4444; animation: blink .5s ease infinite; }
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.35} }

.top-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 13px; border-radius: 11px; border: none;
    font-size: .78rem; font-weight: 700; cursor: pointer;
    font-family: inherit; transition: all .2s;
}
.btn-calc { background: var(--navy); color: #fff; }
.btn-calc:hover { opacity: .85; }
.btn-dark { background: var(--border); color: var(--text); }
.btn-dark:hover { opacity: .8; }
.top-spacer { flex: 1; }

/* ══ CAMERA PIP ══ */
#cam-pip {
    position: fixed; bottom: 16px; right: 16px; z-index: 500;
    width: 110px; height: 82px; border-radius: 14px; overflow: hidden;
    border: 2px solid rgba(26,42,108,.3); background: #0a1540;
    box-shadow: 0 4px 20px rgba(0,0,0,.3); cursor: move;
    transition: border-color .2s;
}
#cam-pip:hover { border-color: var(--green); }
#cam-pip video { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
.cam-off { width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; color:rgba(255,255,255,.35); font-size:.52rem; }
.cam-off i { font-size:1.1rem; }
@keyframes recBlink { 0%,100%{opacity:1} 50%{opacity:0.2} }
@media(max-width:480px){ #cam-pip { width:72px; height:54px; bottom:8px; right:8px; } }
@media(max-width:640px){ .exam-inner { padding-right: 0; } }

/* ══ MOBILE EXAM RESPONSIVE ══ */
@media(max-width:768px){
    #top-bar { padding: 8px 10px; gap:6px; flex-wrap:nowrap; }
    .top-logo { height:26px; }
    .top-div  { display:none; }
    .prog-track { min-width:80px; max-width:140px; }
    #progress-text { font-size:.65rem; }
    .timer-wrap { padding:5px 8px; }
    #timer { font-size:.88rem; }
    .top-btn { padding:6px 10px; font-size:.72rem; }
    #exam-content { padding:12px 8px 80px; }
    .exam-inner { padding-right:0; max-width:100%; }
    .dots-wrap { gap:4px; margin-bottom:12px; }
    .nav-dot { width:8px; height:8px; }
    .q-head  { padding:12px 14px; }
    .q-body  { padding:16px 14px; }
    .q-text  { font-size:1rem; margin-bottom:16px; }
    .opt-btn { padding:11px 12px; font-size:.84rem; gap:10px; }
    .opt-letter { width:28px; height:28px; font-size:.75rem; }
    .q-foot  { padding:12px 14px; }
    .nav-btn { padding:9px 14px; font-size:.78rem; }
    .calc-wrap { max-width:100%; }
    .calc-btn  { padding:13px 6px; font-size:.92rem; }
    .calc-disp { font-size:1.5rem; }
    #calc-ctx  { font-size:.75rem; }
}
@media(max-width:380px){
    .top-btn span { display:none; }
    .nav-btn { padding:8px 10px; font-size:.72rem; }
    .q-text  { font-size:.92rem; }
}

/* ══ CHEAT WARNING ══ */
#cheat-warning {
    position:fixed; top:0; left:0; right:0; z-index:9500;
    background:rgba(239,68,68,0.97); color:#fff;
    display:flex; align-items:center; justify-content:center; gap:12px;
    padding:14px 20px; font-size:.9rem; font-weight:700;
    transform:translateY(-100%); transition:transform .3s ease;
    backdrop-filter:blur(8px); text-align:center;
}
#cheat-warning.show { transform:translateY(0); }
#cheat-warning i { font-size:1.2rem; flex-shrink:0; }


/* ══ CONTENT ══ */
#exam-content { flex:1; display:flex; flex-direction:column; align-items:center; padding:22px 16px 60px; }
.exam-inner   { width:100%; max-width:860px; padding-right:130px; }

/* ══ NAV DOTS ══ */
.dots-wrap { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:18px; }
.nav-dot {
    width:10px; height:10px; border-radius:50%;
    background: var(--dot-bg); border:1px solid rgba(26,42,108,.2);
    cursor:pointer; transition:all .18s; flex-shrink:0;
}
.nav-dot.answered { background: var(--green); border-color: var(--green); }
.nav-dot.current  { background: var(--navy); border-color: var(--navy); transform:scale(1.4); box-shadow:0 0 8px rgba(26,42,108,.5); }

/* ══ QUESTION CARD ══ */
.question-card { display:none; animation:slideIn .35s cubic-bezier(.34,1.2,.64,1) forwards; }
.question-card.active { display:block; }
@keyframes slideIn { from{opacity:0;transform:translateX(50px) scale(.98)} to{opacity:1;transform:none} }

.q-inner { background:var(--bg2); border-radius:24px; border:1px solid var(--border); box-shadow:0 6px 32px var(--shadow); overflow:hidden; transition:background .3s,border .3s; }
.q-head  { padding:18px 28px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px; }
.cat-badge { display:inline-flex; align-items:center; gap:6px; padding:5px 16px; border-radius:99px; font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.06em; }
.q-num { font-size:.8rem; font-weight:700; color:var(--text3); }
.q-body  { padding:32px 28px; }
.q-text  { color:var(--navy); font-weight:700; font-size:clamp(1.05rem,2.8vw,1.25rem); line-height:1.65; margin-bottom:24px; }
.options { display:flex; flex-direction:column; gap:12px; }
.opt-btn {
    display:flex; align-items:center; gap:14px;
    width:100%; padding:15px 20px;
    background:var(--opt-bg); border:2px solid var(--border);
    border-radius:16px; cursor:pointer; text-align:left;
    font-size:.95rem; color:var(--text); font-family:inherit;
    transition:all .18s;
}
.opt-btn:hover { border-color:var(--navy); background:var(--opt-hover); transform:translateX(4px); }
.opt-btn.selected { border-color:var(--green); background:linear-gradient(135deg,#e8f5e9,#f0fff4); }
html.dark .opt-btn.selected { background:linear-gradient(135deg,#0d2b0d,#0a1f0a); }
.opt-btn.selected .opt-letter { background:var(--green); color:#fff; }
.opt-letter {
    width:34px; height:34px; border-radius:10px;
    background:var(--border); color:var(--navy);
    font-weight:800; font-size:.82rem;
    display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all .18s;
}
.q-foot { padding:18px 28px; border-top:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
.nav-btn { display:inline-flex; align-items:center; gap:7px; padding:11px 24px; border-radius:13px; border:none; font-size:.88rem; font-weight:700; cursor:pointer; font-family:inherit; transition:all .18s; }
.nb-prev { background:var(--border); color:var(--navy); }
.nb-prev:hover { background:var(--navy); color:#fff; }
.nb-next { background:var(--navy); color:#fff; }
.nb-next:hover { opacity:.85; }
.nb-submit { background:var(--green); color:#fff; box-shadow:0 4px 14px rgba(67,160,71,.3); }
.nb-submit:hover { filter:brightness(.9); }

/* ══ CALC MODAL ══ */
#calc-modal {
    position:fixed; inset:0; z-index:6000;
    background:rgba(10,21,64,.65); backdrop-filter:blur(8px);
    display:none; align-items:center; justify-content:center;
    flex-direction:column; gap:16px; padding:16px;
}
#calc-modal.show { display:flex; }
.calc-wrap { background:var(--bg2); border-radius:22px; overflow:hidden; width:100%; max-width:360px; box-shadow:0 24px 70px rgba(0,0,0,.5); transition:background .3s; }
.calc-head { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; background:var(--navy); }
.calc-title { color:rgba(255,255,255,.8); font-size:.8rem; font-weight:700; letter-spacing:.05em; }
.calc-x {
    width:30px; height:30px; border-radius:50%;
    background:rgba(255,255,255,.15); border:none; color:#fff;
    cursor:pointer; font-size:.85rem;
    display:flex; align-items:center; justify-content:center;
    transition:background .15s;
}
.calc-x:hover { background:rgba(239,68,68,.7); }
.calc-disp {
    background:#0B1124; color:#fff;
    padding:16px 18px; font-size:1.8rem;
    font-family:'Courier New',monospace;
    text-align:right; min-height:66px;
    word-break:break-all; line-height:1.3;
}
.calc-grid { padding:12px; display:grid; grid-template-columns:repeat(4,1fr); gap:9px; background:var(--bg); }
.calc-btn {
    display:flex; align-items:center; justify-content:center;
    padding:15px 8px; border-radius:12px;
    font-size:1rem; font-weight:700;
    cursor:pointer; border:none; font-family:inherit; transition:all .12s;
}
.calc-btn:hover { filter:brightness(1.12); transform:scale(1.04); }
.calc-btn:active { transform:scale(.94); }
/* Q context below calc */
#calc-ctx {
    width:100%; max-width:360px;
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.18);
    border-radius:14px; padding:12px 16px;
    color:#fff; font-size:.82rem; line-height:1.6;
    text-align:left;
}
#calc-ctx .ctx-label { font-size:.65rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.45); margin-bottom:5px; }

/* ══ CONFIRM MODAL (step 1 — are you sure?) ══ */
#confirm-modal {
    position:fixed; inset:0; z-index:7000;
    display:none; align-items:center; justify-content:center;
    background:rgba(10,21,64,.6); backdrop-filter:blur(8px); padding:16px;
}
#confirm-modal.show { display:flex; }
.confirm-box {
    background:var(--bg2); border-radius:22px;
    max-width:380px; width:100%; padding:36px 28px;
    text-align:center; box-shadow:0 24px 70px rgba(0,0,0,.35);
    transition:background .3s;
}
.confirm-icon { width:60px; height:60px; border-radius:50%; background:var(--border); display:flex; align-items:center; justify-content:center; margin:0 auto 18px; }
.confirm-icon i { color:var(--navy); font-size:1.4rem; }
.confirm-title { color:var(--navy); font-weight:800; font-size:1.15rem; margin-bottom:8px; }
.confirm-msg   { color:var(--text2); font-size:.88rem; margin-bottom:24px; line-height:1.6; }
.confirm-btns  { display:flex; gap:10px; }
.cbtn { flex:1; padding:13px; border-radius:13px; font-size:.88rem; font-weight:700; font-family:inherit; cursor:pointer; transition:all .18s; border:none; }
.cbtn-review { background:var(--border); color:var(--text); }
.cbtn-review:hover { opacity:.8; }
.cbtn-ok { background:var(--green); color:#fff; }
.cbtn-ok:hover { filter:brightness(.9); }

/* ══ SUBMITTING MODAL (step 2 — countdown with cancel) ══ */
#submitting-modal {
    position:fixed; inset:0; z-index:8000;
    display:none; align-items:center; justify-content:center;
    background:rgba(10,21,64,.7); backdrop-filter:blur(10px); padding:16px;
}
#submitting-modal.show { display:flex; }
.sub-box {
    background:var(--bg2); border-radius:22px;
    max-width:380px; width:100%; padding:36px 28px;
    text-align:center; box-shadow:0 24px 70px rgba(0,0,0,.4);
    transition:background .3s;
}
.sub-spinner { width:56px; height:56px; border-radius:50%; border:4px solid var(--border); border-top-color:var(--green); animation:spin .8s linear infinite; margin:0 auto 18px; }
@keyframes spin { to{ transform:rotate(360deg); } }
.sub-title { color:var(--navy); font-weight:800; font-size:1.1rem; margin-bottom:6px; }
.sub-msg   { color:var(--text2); font-size:.82rem; margin-bottom:20px; }
.sub-bar-track { height:8px; background:var(--border); border-radius:99px; overflow:hidden; margin-bottom:20px; }
.sub-bar-fill  { height:100%; background:linear-gradient(90deg,var(--navy),var(--green)); border-radius:99px; width:100%; transition:width .1s linear; }
.cbtn-cancel { width:100%; background:var(--border); color:var(--text2); padding:11px; border-radius:12px; font-size:.85rem; font-weight:700; font-family:inherit; cursor:pointer; border:none; }
.cbtn-cancel:hover { background:#fee2e2; color:#dc2626; }

#exam-page { min-height:100vh; display:flex; flex-direction:column; }
#exam-page { min-height:100dvh; }
button, [role="button"] { touch-action:manipulation; }
button:focus-visible, .nav-dot:focus-visible { outline:3px solid var(--green); outline-offset:2px; }
@media(max-width:640px){
    #top-bar { padding-left:max(8px,env(safe-area-inset-left)); padding-right:max(8px,env(safe-area-inset-right)); }
    #exam-content { padding-bottom:calc(88px + env(safe-area-inset-bottom)); }
    .exam-inner { padding-right:0; }
    .q-inner { border-radius:18px; }
    .q-foot { gap:10px; }
    .nav-btn { min-height:44px; justify-content:center; }
    .opt-btn { min-height:48px; text-align:left; }
    #cam-pip { width:64px; height:48px; bottom:calc(8px + env(safe-area-inset-bottom)); right:calc(8px + env(safe-area-inset-right)); }
    .calc-wrap { max-height:calc(100dvh - 32px); overflow-y:auto; }
    #calc-modal, #confirm-modal, #submitting-modal { padding:max(12px,env(safe-area-inset-top)) max(12px,env(safe-area-inset-right)) max(12px,env(safe-area-inset-bottom)) max(12px,env(safe-area-inset-left)); }
    .confirm-box, .sub-box { max-height:calc(100dvh - 32px); overflow-y:auto; padding:28px 20px; }
}
@media(max-width:380px){
    #top-bar { gap:5px; }
    .top-logo { height:24px; }
    .prog-track { min-width:52px; }
    #progress-text { font-size:.6rem; }
    .timer-wrap { gap:4px; padding:5px 6px; border-radius:9px; }
    #timer { font-size:.78rem; }
    .top-btn { min-width:36px; min-height:36px; justify-content:center; padding:6px 8px; }
    #exam-content { padding-left:8px; padding-right:8px; }
    .q-head, .q-foot { padding-left:12px; padding-right:12px; }
    .q-body { padding:16px 12px; }
    .nav-btn { min-width:0; padding:8px 10px; }
    .confirm-btns { flex-direction:column; }
}
@media(hover:none){
    .opt-btn:hover, .calc-btn:hover { transform:none; }
}
@media(prefers-reduced-motion:reduce){
    *,*::before,*::after { scroll-behavior:auto!important; animation-duration:.01ms!important; animation-iteration-count:1!important; transition-duration:.01ms!important; }
}
</style>
</head>
<body>

{{-- ══ COUNTDOWN ══ --}}
<div id="countdown-overlay">
    <img src="{{ asset('images/logo.png') }}" class="cd-logo" alt="PathFindr">
    <div class="cd-label">Assessment begins in</div>
    <div id="cd-num" class="cd-num">3</div>
    <div class="cd-bar"><div id="cd-bar-fill" class="cd-bar-fill" style="width:0%"></div></div>
</div>

{{-- ══ EXIT HINT (top hover strip) ══ --}}
<div id="exit-hint" onclick="exitFullscreen()">
    <i class="fas fa-times-circle"></i>
    Click to exit fullscreen &amp; restore browser tabs
</div>

{{-- ══ CAMERA PIP ══ --}}
<div id="cam-pip">
    <div class="cam-off" id="cam-off"><i class="fas fa-video-slash"></i><span>Camera</span></div>
    <video id="cam-video" autoplay muted playsinline style="display:none;"></video>
</div>

{{-- ══ CHEAT WARNING ══ --}}
<div id="cheat-warning">
    <i class="fas fa-exclamation-triangle"></i>
    <span id="cheat-msg">Please keep your eyes on the screen. Looking away may result in exam termination.</span>
</div>

{{-- ══ EXAM PAGE ══ --}}
<div id="exam-page">

    <div id="top-bar">
        <img src="{{ asset('images/logo.png') }}" class="top-logo" alt="PathFindr">
        <div class="top-div"></div>
        <div class="prog-track"><div class="prog-fill" id="progress-bar" style="width:0%"></div></div>
        <span id="progress-text">0 / {{ $questions->count() }}</span>
        <div class="top-div"></div>
        <div class="timer-wrap">
            <i class="fas fa-clock" style="color:var(--navy);font-size:.8rem;"></i>
            <span id="timer" class="t-normal">30:00</span>
        </div>
        <div class="top-spacer"></div>
        <button class="top-btn btn-dark" onclick="toggleDark()" title="Toggle dark mode" id="dark-btn">
            <i class="fas fa-moon"></i>
        </button>
        <button class="top-btn" id="fs-btn" onclick="reenterFullscreen()" title="Re-enter focus mode"
                style="background:#F5A623;color:#fff;display:none;">
            <i class="fas fa-expand"></i>
        </button>
        <button class="top-btn btn-calc" onclick="openCalc()">
            <i class="fas fa-calculator"></i> <span style="display:none;" id="calc-label">Calc</span>
        </button>
    </div>

    <div id="exam-content">
        <div class="exam-inner">

            <div class="dots-wrap" id="dots-wrap">
                @foreach($questions as $i => $q)
                <div class="nav-dot {{ $i===0?'current':'' }}" onclick="goToQuestion({{ $i }})" title="Q{{ $i+1 }}"></div>
                @endforeach
            </div>

            <form id="exam-form" method="POST" action="{{ route('exam.submit') }}">
            @csrf

            @foreach($questions as $i => $q)
            @php
                $catMeta = [
                    'numerical'=>['label'=>'Numerical Reasoning','icon'=>'fa-calculator','bg'=>'#EBF4FF','text'=>'#1A2A6C'],
                    'verbal'   =>['label'=>'Verbal Reasoning',   'icon'=>'fa-font',      'bg'=>'#e8f5e9','text'=>'#2e7d32'],
                    'spatial'  =>['label'=>'Spatial Awareness',  'icon'=>'fa-shapes',    'bg'=>'#ede9fe','text'=>'#7C3AED'],
                    'interest' =>['label'=>'Personal Interest',  'icon'=>'fa-heart',     'bg'=>'#fff3e0','text'=>'#e65100'],
                ];
                $cm = $catMeta[$q->category];
            @endphp
            <div class="question-card {{ $i===0?'active':'' }}" id="qcard-{{ $i }}">
                <div class="q-inner">
                    <div class="q-head">
                        <span class="cat-badge" style="background:{{ $cm['bg'] }};color:{{ $cm['text'] }};">
                            <i class="fas {{ $cm['icon'] }}"></i> {{ $cm['label'] }}
                        </span>
                        <span class="q-num">{{ $i+1 }} / {{ $questions->count() }}</span>
                    </div>
                    <div class="q-body">
                        <p class="q-text">{{ $q->question_text }}</p>
                        <div class="options">
                            @foreach(['A'=>$q->option_a,'B'=>$q->option_b,'C'=>$q->option_c,'D'=>$q->option_d] as $letter=>$text)
                            <button type="button" class="opt-btn" data-q="{{ $q->id }}" data-letter="{{ $letter }}" onclick="selectAnswer(this)">
                                <span class="opt-letter">{{ $letter }}</span><span>{{ $text }}</span>
                            </button>
                            @endforeach
                            <input type="hidden" name="answers[{{ $q->id }}]" id="ans-{{ $q->id }}" value="">
                        </div>
                    </div>
                    <div class="q-foot">
                        <button type="button" onclick="prevQuestion()" class="nav-btn nb-prev {{ $i===0?'invisible':'' }}">
                            <i class="fas fa-arrow-left"></i> Prev
                        </button>
                        @if($i < $questions->count()-1)
                        <button type="button" onclick="nextQuestion()" class="nav-btn nb-next">
                            Next <i class="fas fa-arrow-right"></i>
                        </button>
                        @else
                        <button type="button" onclick="openConfirm()" class="nav-btn nb-submit">
                            <i class="fas fa-paper-plane"></i> Submit
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach

            </form>
        </div>
    </div>
</div>

{{-- ══ CALCULATOR MODAL ══ --}}
<div id="calc-modal">
    <div class="calc-wrap">
        <div class="calc-head">
            <span class="calc-title"><i class="fas fa-calculator" style="margin-right:6px;"></i>Calculator</span>
            <button class="calc-x" onclick="closeCalc()"><i class="fas fa-times"></i></button>
        </div>
        <div class="calc-disp" id="calc-display">0</div>
        <div class="calc-grid">
            @php
            $cb=[
                ['C','#EF4444','#fff'],['±','#6B7280','#fff'],['%','#6B7280','#fff'],['÷','#1A2A6C','#fff'],
                ['7','#f1f5f9','#1A2A6C'],['8','#f1f5f9','#1A2A6C'],['9','#f1f5f9','#1A2A6C'],['×','#1A2A6C','#fff'],
                ['4','#f1f5f9','#1A2A6C'],['5','#f1f5f9','#1A2A6C'],['6','#f1f5f9','#1A2A6C'],['-','#1A2A6C','#fff'],
                ['1','#f1f5f9','#1A2A6C'],['2','#f1f5f9','#1A2A6C'],['3','#f1f5f9','#1A2A6C'],['+','#1A2A6C','#fff'],
                ['0','#f1f5f9','#1A2A6C'],['.','#f1f5f9','#1A2A6C'],['⌫','#6B7280','#fff'],['=','#43A047','#fff'],
            ];
            @endphp
            @foreach($cb as [$lbl,$bg,$col])
            <button type="button" class="calc-btn" style="background:{{ $bg }};color:{{ $col }};" onclick="calcPress('{{ $lbl }}')">{{ $lbl }}</button>
            @endforeach
        </div>
    </div>
    {{-- Current question context --}}
    <div id="calc-ctx">
        <div class="ctx-label">Current Question</div>
        <div id="calc-ctx-text">—</div>
    </div>
</div>

{{-- ══ CONFIRM MODAL (step 1) ══ --}}
<div id="confirm-modal">
    <div class="confirm-box">
        <div class="confirm-icon"><i class="fas fa-paper-plane"></i></div>
        <div class="confirm-title">Submit Assessment?</div>
        <div class="confirm-msg" id="confirm-msg">Once submitted your answers cannot be changed.</div>
        <div class="confirm-btns">
            <button class="cbtn cbtn-review" onclick="closeConfirm()">Review Answers</button>
            <button class="cbtn cbtn-ok" onclick="proceedSubmit()">Yes, Submit</button>
        </div>
    </div>
</div>

{{-- ══ SUBMITTING MODAL (step 2) ══ --}}
<div id="submitting-modal">
    <div class="sub-box">
        <div class="sub-spinner"></div>
        <div class="sub-title">Submitting...</div>
        <div class="sub-msg" id="sub-msg">Your assessment is being submitted. Please wait.</div>
        <div class="sub-bar-track"><div class="sub-bar-fill" id="sub-bar"></div></div>
        <button class="cbtn-cancel" id="cancel-sub-btn" onclick="cancelSubmit()">
            <i class="fas fa-times" style="margin-right:6px;"></i>Cancel Submission
        </button>
    </div>
</div>

<script>
const TOTAL = {{ $questions->count() }};
const savedAnswers = @json($savedAnswers ?? []);
let current = 0, answered = {}, timeLeft = 1800;
let timerInt, subInt, subCancelled = false;
const timerEl = document.getElementById('timer');

Object.entries(savedAnswers).forEach(([questionId, selected]) => {
    const input = document.getElementById('ans-' + questionId);
    if (!input) return;
    input.value = selected;
    answered[Number(questionId)] = selected;

    const button = document.querySelector('.opt-btn[data-q="' + questionId + '"][data-letter="' + selected + '"]');
    if (button) {
        button.classList.add('selected');
    }
});

// ══ DARK MODE ══════════════════════════════════════════════
function toggleDark() {
    const html = document.getElementById('html-root');
    const isDark = html.classList.toggle('dark');
    document.getElementById('dark-btn').innerHTML = isDark
        ? '<i class="fas fa-sun"></i>'
        : '<i class="fas fa-moon"></i>';
    localStorage.setItem('pfDark', isDark ? '1' : '0');
}
// Apply saved preference
if (localStorage.getItem('pfDark') === '1') {
    document.getElementById('html-root').classList.add('dark');
    document.getElementById('dark-btn').innerHTML = '<i class="fas fa-sun"></i>';
}

// ══ COUNTDOWN ══════════════════════════════════════════════
(function runCountdown() {
    const overlay = document.getElementById('countdown-overlay');
    const numEl   = document.getElementById('cd-num');
    const bar     = document.getElementById('cd-bar-fill');
    let n = 3;
    function tick() {
        if (n > 0) {
            numEl.className = ''; void numEl.offsetWidth; numEl.className = 'cd-num';
            numEl.textContent = n;
            bar.style.transition = 'none';
            bar.style.width = ((3-n)/3*100)+'%';
            requestAnimationFrame(() => {
                bar.style.transition = 'width .9s linear';
                bar.style.width = ((3-n+1)/3*100)+'%';
            });
            n--; setTimeout(tick, 1000);
        } else {
            numEl.className = ''; numEl.innerHTML = '<div class="cd-go">GO!</div>';
            bar.style.width = '100%';
            // Must wait for user click to enter fullscreen (browser security requirement)
            const clickPrompt = document.createElement('div');
            clickPrompt.style.cssText = 'margin-top:16px;color:rgba(255,255,255,.7);font-size:.9rem;font-weight:600;letter-spacing:.05em;animation:popIn .4s ease;cursor:pointer;background:rgba(67,160,71,.2);border:1px solid rgba(67,160,71,.4);padding:12px 28px;border-radius:14px;';
            clickPrompt.textContent = 'Tap anywhere to begin';
            document.getElementById('countdown-overlay').appendChild(clickPrompt);

            document.getElementById('countdown-overlay').addEventListener('click', function handler() {
                document.getElementById('countdown-overlay').removeEventListener('click', handler);
                overlay.style.transition = 'opacity .6s ease';
                overlay.style.opacity = '0';
                setTimeout(() => { overlay.style.display = 'none'; startExam(); }, 600);
            }, { once: true });
        }
    }
    tick();
})();

function startExam() {
    startTimer();
    enterFullscreen();
    startCamera();
    // Show calc label on larger screens
    if (window.innerWidth > 480) document.getElementById('calc-label').style.display = 'inline';
}

// ══ TIMER ══════════════════════════════════════════════════
function startTimer() {
    timerInt = setInterval(() => {
        timeLeft--;
        const m = Math.floor(timeLeft/60), s = timeLeft%60;
        timerEl.textContent = (m<10?'0':'')+m+':'+(s<10?'0':'')+s;
        timerEl.className = timeLeft<=120?'t-danger':timeLeft<=300?'t-warning':'t-normal';
        if (timeLeft <= 0) { clearInterval(timerInt); forceSubmit(); }
    }, 1000);
}

// ══ FULLSCREEN ═════════════════════════════════════════════
// True fullscreen via Fullscreen API — hides browser UI including tabs
function enterFullscreen() {
    const el = document.documentElement;
    const fn = el.requestFullscreen || el.webkitRequestFullscreen || el.mozRequestFullScreen || el.msRequestFullscreen;
    if (fn) fn.call(el).catch(() => {});
}
function exitFullscreen() {
    const fn = document.exitFullscreen || document.webkitExitFullscreen || document.mozCancelFullScreen || document.msExitFullscreen;
    if (fn) fn.call(document).catch(() => {});
    document.getElementById('exit-hint').classList.remove('show');
}

function reenterFullscreen() {
    enterFullscreen();
}

// Show re-enter button when fullscreen is exited
document.addEventListener('fullscreenchange', () => {
    const btn = document.getElementById('fs-btn');
    if (!document.fullscreenElement) {
        btn.style.display = 'inline-flex';
        document.getElementById('exit-hint').classList.remove('show');
    } else {
        btn.style.display = 'none';
    }
});
document.addEventListener('webkitfullscreenchange', () => {
    const btn = document.getElementById('fs-btn');
    if (!document.webkitFullscreenElement) {
        btn.style.display = 'inline-flex';
        document.getElementById('exit-hint').classList.remove('show');
    } else {
        btn.style.display = 'none';
    }
});
// Mouse near top → show exit strip (only when NOT hovering calc modal)
let hintTimeout;
document.addEventListener('mousemove', e => {
    if (document.getElementById('calc-modal').classList.contains('show')) return;
    if (!document.fullscreenElement && !document.webkitFullscreenElement) return;
    const hint = document.getElementById('exit-hint');
    if (e.clientY <= 36) {
        hint.classList.add('show');
        clearTimeout(hintTimeout);
        hintTimeout = setTimeout(() => hint.classList.remove('show'), 2500);
    }
});
// Touch swipe down from top edge
let tsY = 0;
document.addEventListener('touchstart', e => { tsY = e.touches[0].clientY; }, { passive:true });
document.addEventListener('touchmove',  e => {
    if (document.getElementById('calc-modal').classList.contains('show')) return;
    if (!document.fullscreenElement && !document.webkitFullscreenElement) return;
    if (e.touches[0].clientY - tsY > 40 && tsY < 50)
        document.getElementById('exit-hint').classList.add('show');
}, { passive:true });

// ══ CAMERA + FACE-API.JS HEAD POSE DETECTION ═══════════════
// Strategy:
// 1. Request camera. If denied/unavailable → skip all detection,
//    show a fake "recording" indicator (student sees camera pip with REC dot).
// 2. If camera works → load face-api.js models, run detection every 1.5s.
// 3. Detect head yaw using nose/chin/ear landmark positions.
// 4. 3 sideways-look warnings → auto-submit.

let faceStream     = null;
let faceDetecting  = false;
let cheatCount     = 0;
let cheatCooldown  = false;
const CHEAT_MAX    = 3;

// ── Fake REC indicator (shown regardless of camera state) ──
function showRecIndicator() {
    const pip = document.getElementById('cam-pip');
    const rec = document.createElement('div');
    rec.id = 'rec-dot';
    rec.style.cssText = 'position:absolute;top:5px;left:6px;z-index:10;' +
        'display:flex;align-items:center;gap:3px;' +
        'background:rgba(0,0,0,0.5);border-radius:99px;padding:2px 6px;';
    rec.innerHTML = '<span style="width:6px;height:6px;border-radius:50%;background:#ef4444;display:inline-block;animation:recBlink 1s ease infinite;"></span>' +
                    '<span style="font-size:7px;color:#fff;font-weight:700;font-family:monospace;">REC</span>';
    pip.appendChild(rec);
}

// ── Main camera start ──
function startCamera() {
    showRecIndicator();

    if (!navigator.mediaDevices?.getUserMedia) {
        // No camera API — skip detection silently
        return;
    }

    navigator.mediaDevices.getUserMedia({ video:{ facingMode:'user', width:{ ideal:320 }, height:{ ideal:240 } }, audio:false })
        .then(stream => {
            faceStream = stream;
            const v = document.getElementById('cam-video');
            v.srcObject = stream;
            v.style.display = 'block';
            document.getElementById('cam-off').style.display = 'none';

            v.addEventListener('loadeddata', () => {
                loadFaceApiAndDetect(v);
            }, { once: true });
        })
        .catch(() => {
            // Camera denied or unavailable — skip detection, fake REC stays
            console.log('[PathFindr] Camera unavailable. Proctoring skipped.');
        });
}

// ── Load face-api.js then start detection ──
function loadFaceApiAndDetect(video) {
    // Load face-api.js from CDN
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js';
    script.onload = async () => {
        try {
            const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
            await Promise.all([
                faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL),
                faceapi.nets.faceLandmark68TinyNet.loadFromUri(MODEL_URL),
            ]);
            faceDetecting = true;
            runDetectionLoop(video);
        } catch(e) {
            // Model load failed — skip silently
            console.log('[PathFindr] Face model load failed. Detection skipped.');
        }
    };
    script.onerror = () => {}; // CDN unreachable — skip silently
    document.head.appendChild(script);
}

// ── Detection loop ──
async function runDetectionLoop(video) {
    const opts = new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.4 });

    setInterval(async () => {
        if (!faceDetecting || !faceStream || video.readyState < 2) return;
        try {
            const result = await faceapi
                .detectSingleFace(video, opts)
                .withFaceLandmarks(true);

            if (!result) {
                // No face detected
                onSuspiciousActivity('No face detected. Please stay in frame.');
                return;
            }

            // ── Head yaw detection using landmarks ──
            // Landmarks: leftEye ~36-41, rightEye ~42-47, nose tip ~30, chin ~8
            const pts = result.landmarks.positions;

            // Horizontal midpoint of face box
            const faceCenter = result.detection.box.x + result.detection.box.width / 2;

            // Nose tip x position
            const noseTip = pts[30].x;

            // Left ear area (pts[0]) and right ear area (pts[16])
            const leftEdge  = pts[0].x;
            const rightEdge = pts[16].x;
            const faceWidth = rightEdge - leftEdge;

            // Nose offset from face center, normalised by face width
            const noseOffset = (noseTip - faceCenter) / (faceWidth || 1);

            // If nose shifts more than 25% of face width left or right → head turned
            if (Math.abs(noseOffset) > 0.25) {
                const dir = noseOffset > 0 ? 'right' : 'left';
                onSuspiciousActivity('Please face forward. Head turned ' + dir + '.');
            } else {
                clearCheatWarning();
            }
        } catch(e) {}
    }, 1500);
}

// ── Suspicious activity handler ──
function onSuspiciousActivity(msg) {
    if (cheatCooldown) return;

    cheatCount++;
    cheatCooldown = true;
    setTimeout(() => { cheatCooldown = false; }, 5000); // 5s cooldown between warnings

    const el  = document.getElementById('cheat-warning');
    const txt = document.getElementById('cheat-msg');

    if (cheatCount >= CHEAT_MAX) {
        txt.textContent = '🚨 Warning ' + cheatCount + '/' + CHEAT_MAX + ': Exam is being auto-submitted due to repeated violations.';
        el.classList.add('show');
        setTimeout(() => forceSubmit(), 3500);
    } else {
        txt.textContent = '⚠️ Warning ' + cheatCount + '/' + CHEAT_MAX + ': ' + msg + ' Further violations will auto-submit your exam.';
        el.classList.add('show');
        setTimeout(() => el.classList.remove('show'), 5000);
    }
}

function clearCheatWarning() {
    document.getElementById('cheat-warning').classList.remove('show');
}
// Draggable pip
(function() {
    const pip = document.getElementById('cam-pip');
    let ox=0,oy=0,sx=0,sy=0,drag=false;
    pip.addEventListener('mousedown', e => {
        drag=true; sx=e.clientX; sy=e.clientY;
        ox=pip.offsetLeft; oy=pip.offsetTop; pip.style.cursor='grabbing';
    });
    document.addEventListener('mousemove', e => {
        if (!drag) return;
        pip.style.right='auto'; pip.style.bottom='auto';
        pip.style.left=(ox+e.clientX-sx)+'px'; pip.style.top=(oy+e.clientY-sy)+'px';
    });
    document.addEventListener('mouseup', () => { drag=false; pip.style.cursor='move'; });
})();

// ══ NAVIGATION ═════════════════════════════════════════════
function goToQuestion(idx) {
    const cards = document.querySelectorAll('.question-card');
    const dots  = document.querySelectorAll('.nav-dot');
    cards[current].classList.remove('active'); cards[current].style.display='none';
    dots[current].classList.remove('current');
    if (answered[current] !== undefined) dots[current].classList.add('answered');
    current = idx;
    cards[current].style.display = 'block';
    requestAnimationFrame(() => cards[current].classList.add('active'));
    dots[current].classList.add('current');
    updateProgress();
    window.scrollTo({ top:0, behavior:'smooth' });
}
function nextQuestion() { if (current<TOTAL-1) goToQuestion(current+1); }
function prevQuestion() { if (current>0) goToQuestion(current-1); }
function updateProgress() {
    const c = Object.keys(answered).length;
    document.getElementById('progress-bar').style.width = Math.round(c/TOTAL*100)+'%';
    document.getElementById('progress-text').textContent = c+' / '+TOTAL;
}

// ══ ANSWER ═════════════════════════════════════════════════
function saveAnswerToServer(qId, letter) {
    fetch('{{ route('exam.answer') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ question_id: Number(qId), selected_option: letter }),
    }).catch(() => {});
}

function selectAnswer(btn) {
    const qId=btn.dataset.q, letter=btn.dataset.letter;
    btn.closest('.options').querySelectorAll('.opt-btn').forEach(b => {
        b.classList.remove('selected');
        b.querySelector('.opt-letter').style.cssText='';
    });
    btn.classList.add('selected');
    document.getElementById('ans-'+qId).value = letter;
    answered[Number(qId)] = letter;
    document.querySelectorAll('.nav-dot')[current].classList.add('answered');
    saveAnswerToServer(qId, letter);
    updateProgress();
    if (current < TOTAL-1) setTimeout(nextQuestion, 550);
}

// ══ CALCULATOR ═════════════════════════════════════════════
const cs = { d:'0', prev:null, op:null, newNum:true };
function openCalc() {
    const qCard = document.querySelector('.question-card.active');
    const txt   = qCard ? qCard.querySelector('.q-text').textContent.trim() : '';
    document.getElementById('calc-ctx-text').textContent = txt || '—';
    document.getElementById('calc-modal').classList.add('show');
}
function closeCalc() { document.getElementById('calc-modal').classList.remove('show'); }
function calcPress(k) {
    if (k==='C') { cs.d='0'; cs.prev=null; cs.op=null; cs.newNum=true; }
    else if (k==='⌫') { cs.d = cs.d.length>1 ? cs.d.slice(0,-1) : '0'; }
    else if (k==='±') { cs.d = String(-parseFloat(cs.d)); }
    else if (k==='%') { cs.d = String(parseFloat(cs.d)/100); }
    else if (['+','-','×','÷'].includes(k)) { cs.prev=parseFloat(cs.d); cs.op=k; cs.newNum=true; }
    else if (k==='=') {
        if (cs.op&&cs.prev!==null) {
            const a=cs.prev, b=parseFloat(cs.d);
            const r = cs.op==='+'?a+b : cs.op==='-'?a-b : cs.op==='×'?a*b : b!==0?a/b:'Error';
            cs.d=String(r); cs.op=null; cs.prev=null; cs.newNum=true;
        }
    } else if (k==='.') {
        if (cs.newNum) { cs.d='0.'; cs.newNum=false; }
        else if (!cs.d.includes('.')) cs.d+='.';
    } else {
        if (cs.newNum||cs.d==='0') { cs.d=k; cs.newNum=false; }
        else if (cs.d.replace('-','').length<14) cs.d+=k;
    }
    document.getElementById('calc-display').textContent = cs.d;
}

// ══ SUBMIT FLOW ════════════════════════════════════════════
// Step 1 — confirm
function openConfirm() {
    const u = TOTAL - Object.keys(answered).length;
    document.getElementById('confirm-msg').textContent = u > 0
        ? u+' question(s) still unanswered. You can go back and answer them, or submit now.'
        : 'All '+TOTAL+' questions answered. Great job!';
    document.getElementById('confirm-modal').classList.add('show');
}
function closeConfirm() { document.getElementById('confirm-modal').classList.remove('show'); }

// Step 2 — submitting countdown bar (5s cancel window)
function proceedSubmit() {
    closeConfirm();
    subCancelled = false;
    document.getElementById('submitting-modal').classList.add('show');
    const bar = document.getElementById('sub-bar');
    const cancelBtn = document.getElementById('cancel-sub-btn');
    bar.style.width = '100%';
    let remaining = 5000;
    const step = 100;
    cancelBtn.style.display = 'block';

    subInt = setInterval(() => {
        if (subCancelled) { clearInterval(subInt); return; }
        remaining -= step;
        bar.style.width = Math.max(0, (remaining/5000)*100)+'%';
        const secs = Math.ceil(remaining/1000);
        document.getElementById('sub-msg').textContent = 'Submitting in '+secs+' second'+(secs!==1?'s':'')+'. Click cancel to go back.';
        if (remaining <= 0) {
            clearInterval(subInt);
            cancelBtn.style.display = 'none';
            document.getElementById('sub-msg').textContent = 'Submitting your answers...';
            forceSubmit();
        }
    }, step);
}

function cancelSubmit() {
    subCancelled = true;
    clearInterval(subInt);
    document.getElementById('submitting-modal').classList.remove('show');
}

function forceSubmit() {
    clearInterval(timerInt);
    document.getElementById('exam-form').submit();
}

// ══ KEYBOARD ═══════════════════════════════════════════════
document.addEventListener('keydown', e => {
    if (document.getElementById('calc-modal').classList.contains('show')) {
        if (e.key==='Escape') closeCalc();
        return;
    }
    if (e.key==='ArrowRight'||e.key===' ') { e.preventDefault(); nextQuestion(); }
    if (e.key==='ArrowLeft') { e.preventDefault(); prevQuestion(); }
});
</script>
</body>
</html>
