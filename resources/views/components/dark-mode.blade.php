{{--
    Drop this component into every layout's <head>.
    It applies the saved dark preference before paint (no flash),
    and exposes toggleDark() globally.
--}}
<style>
/* ── Light mode tokens ── */
:root {
    --pf-bg:         #EBF4FF;
    --pf-surface:    #ffffff;
    --pf-surface2:   #f8fafc;
    --pf-border:     #e5e7eb;
    --pf-border2:    #EBF4FF;
    --pf-text:       #2D3748;
    --pf-text2:      #4B5563;
    --pf-text3:      #9CA3AF;
    --pf-navy:       #1A2A6C;
    --pf-royal:      #2D5F9E;
    --pf-green:      #43A047;
    --pf-gold:       #F5A623;
    --pf-header-bg:  rgba(255,255,255,0.95);
    --pf-input-bg:   #ffffff;
    --pf-shadow:     rgba(26,42,108,0.08);
}
/* ── Dark mode tokens ── */
html.dark {
    --pf-bg:         #0d1117;
    --pf-surface:    #161b22;
    --pf-surface2:   #1c2128;
    --pf-border:     #30363d;
    --pf-border2:    #21262d;
    --pf-text:       #e6edf3;
    --pf-text2:      #8b949e;
    --pf-text3:      #6e7681;
    --pf-navy:       #79c0ff;
    --pf-royal:      #58a6ff;
    --pf-green:      #56d364;
    --pf-gold:       #e3b341;
    --pf-header-bg:  rgba(13,17,23,0.97);
    --pf-input-bg:   #161b22;
    --pf-shadow:     rgba(0,0,0,0.4);
}

/* ── Apply tokens to common elements ── */
body {
    background-color: var(--pf-bg);
    color: var(--pf-text);
    transition: background-color 0.25s ease, color 0.25s ease;
}

/* Cards / surfaces */
.pf-card, .pf-surface {
    background: var(--pf-surface);
    border-color: var(--pf-border);
    color: var(--pf-text);
}

/* Inputs */
input, textarea, select {
    background-color: var(--pf-input-bg) !important;
    color: var(--pf-text) !important;
    border-color: var(--pf-border) !important;
    transition: background-color 0.25s, color 0.25s, border-color 0.25s;
}
input::placeholder, textarea::placeholder { color: var(--pf-text3) !important; }

/* Tables */
html.dark table thead tr { background: var(--pf-surface2) !important; }
html.dark table td, html.dark table th { color: var(--pf-text) !important; border-color: var(--pf-border) !important; }
html.dark table tbody tr:hover td { background: var(--pf-surface2) !important; }

/* Links */
html.dark a { color: var(--pf-royal); }
html.dark a:hover { color: var(--pf-green); }

/* Scrollbar */
html.dark ::-webkit-scrollbar-track { background: #161b22; }

/* Tailwind bg/text overrides for dark */
html.dark .bg-white    { background-color: var(--pf-surface) !important; }
html.dark .bg-\[\#EBF4FF\], html.dark .bg-\[\#f8fafc\], html.dark .bg-sky-50 { background-color: var(--pf-surface2) !important; }
html.dark .border-\[\#EBF4FF\], html.dark .border-gray-100, html.dark .border-gray-200 { border-color: var(--pf-border) !important; }
html.dark .text-\[\#2D3748\]  { color: var(--pf-text) !important; }
html.dark .text-\[\#1A2A6C\]  { color: var(--pf-navy) !important; }
html.dark .text-\[\#2D5F9E\]  { color: var(--pf-royal) !important; }
html.dark .text-gray-500, html.dark .text-gray-400 { color: var(--pf-text2) !important; }
html.dark .text-gray-600 { color: var(--pf-text2) !important; }
html.dark .shadow-sm, html.dark .shadow-md { box-shadow: 0 2px 12px var(--pf-shadow) !important; }

/* Header / topbar */
html.dark header,
html.dark #top-bar { background: var(--pf-header-bg) !important; border-color: var(--pf-border) !important; }

/* Form inputs specific */
html.dark .bg-\[\#f8fafc\] { background-color: var(--pf-surface2) !important; }

/* ── Global contrast fixes for all modes ── */

/* Fix all /40 and lower opacity text — boost to readable levels */
.text-\[\#2D3748\]\/40, .text-\[\#2D3748\]\/50 { color: #4B5563 !important; }
html.dark .text-\[\#2D3748\]\/40,
html.dark .text-\[\#2D3748\]\/50,
html.dark .text-\[\#2D3748\]\/60 { color: #adbac7 !important; }

/* gray-300 and gray-400 on light backgrounds */
.text-gray-300 { color: #6B7280 !important; }
html.dark .text-gray-300 { color: #8b949e !important; }

/* Boost gray-400 in light mode */
.text-gray-400 { color: #6B7280 !important; }
html.dark .text-gray-400 { color: #8b949e !important; }

/* Fix gray-500 in dark mode */
html.dark .text-gray-500 { color: #adbac7 !important; }

/* Fix white at very low opacity on dark backgrounds */
/* These are used inside dark navy/glass sections */
.text-white\/30 { color: rgba(255,255,255,0.65) !important; }
.text-white\/40 { color: rgba(255,255,255,0.72) !important; }
.text-white\/50 { color: rgba(255,255,255,0.82) !important; }
.text-blue-200\/70 { color: #bfdbfe !important; }

/* Fix blue-200/70 links in footer on dark bg */
a.text-blue-200\/70 { color: #bfdbfe !important; }
a.text-blue-200\/70:hover { color: #fff !important; }

/* Super admin glass labels — rgba low opacity */
/* Applied via inline styles, we override via parent selectors in specific files */

/* Pending badge: #b07d0a on light orange is bad — darker brown */
.text-\[\#b07d0a\] { color: #92400e !important; }
html.dark .text-\[\#b07d0a\] { color: #fbbf24 !important; }

/* Navy on EBF4FF backgrounds — fine in light but boost in dark */
html.dark .bg-\[\#EBF4FF\]\/50 { background-color: rgba(26,42,108,0.2) !important; }
html.dark .text-\[\#1A2A6C\] { color: #79c0ff !important; }

/* Table headers on EBF4FF backgrounds */
.text-\[\#2D3748\]\/50 { color: #374151 !important; }
html.dark .text-\[\#2D3748\]\/50 { color: #adbac7 !important; }
</style>

{{-- Apply before paint — no FOUC --}}
<script>
(function(){
    if(localStorage.getItem('pfDark')==='1'){
        document.documentElement.classList.add('dark');
    }
})();
</script>
