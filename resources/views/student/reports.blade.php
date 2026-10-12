@extends('layouts.dashboard')
@section('title', 'PDF Reports — PathFindr')
@section('page-title', 'PDF Reports')

@section('styles')
<style>
.card-in { animation: cardIn 0.4s ease forwards; opacity:0; transform:translateY(14px); }
@keyframes cardIn { to { opacity:1; transform:translateY(0); } }

/* ── Report action buttons ── */
.rpt-btn {
    display:inline-flex; align-items:center; gap:8px;
    font-weight:700; font-size:.8rem;
    padding:9px 18px; border-radius:11px;
    border:none; cursor:pointer;
    transition:all .18s ease; text-decoration:none;
    white-space:nowrap; letter-spacing:.01em;
}
.rpt-btn:hover { transform:translateY(-1px); }
.rpt-btn:active { transform:translateY(0); }

/* Download — solid navy */
.rpt-btn-download {
    background:#1A2A6C; color:#fff;
    box-shadow:0 3px 10px rgba(26,42,108,.3);
}
.rpt-btn-download:hover { background:#0f1f5c; box-shadow:0 6px 18px rgba(26,42,108,.4); }

/* WhatsApp — brand green */
.rpt-btn-whatsapp {
    background:#25D366; color:#fff;
    box-shadow:0 3px 10px rgba(37,211,102,.3);
}
.rpt-btn-whatsapp:hover { background:#1aad55; box-shadow:0 6px 18px rgba(37,211,102,.35); }

/* Email — sky blue */
.rpt-btn-email {
    background:#0284c7; color:#fff;
    box-shadow:0 3px 10px rgba(2,132,199,.28);
}
.rpt-btn-email:hover { background:#0369a1; box-shadow:0 6px 18px rgba(2,132,199,.35); }

/* View online — ghost */
.rpt-btn-view {
    background:#f1f5f9; color:#334155;
    border:1.5px solid #e2e8f0;
    box-shadow:none;
}
.rpt-btn-view:hover { background:#e2e8f0; color:#1A2A6C; box-shadow:0 3px 10px rgba(0,0,0,.06); }

/* Dark mode */
html.dark .report-card { background:#161b22 !important; border-color:#30363d !important; }
html.dark .report-card:hover { border-color:#388bfd !important; }
html.dark .rpt-btn-view { background:#21262d !important; color:#cdd9e5 !important; border-color:#30363d !important; }
html.dark .rpt-btn-view:hover { background:#2d333b !important; color:#e6edf3 !important; }
html.dark .score-chip { background:#0d1117 !important; border-color:#30363d !important; }
html.dark .report-divider { border-color:#30363d !important; }
html.dark .info-banner { background:#1c2433 !important; border-color:#1f6feb !important; color:#79c0ff !important; }
html.dark .tip-banner  { background:#0d2b0d !important; border-color:#2ea043 !important; color:#56d364 !important; }
html.dark .empty-card  { background:#161b22 !important; border-color:#30363d !important; }
</style>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-4xl mx-auto">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0f172a]">PDF Reports</h1>
            <p class="text-[#64748b] text-sm mt-0.5">Download or share your assessment reports</p>
        </div>
        <a href="{{ route('student.results') }}"
           class="inline-flex items-center gap-2 font-semibold text-sm px-4 py-2.5 rounded-xl transition-all duration-200"
           style="background:#eff6ff;color:#1A2A6C;border:1.5px solid #bfdbfe;">
            <i class="fas fa-chart-bar text-xs"></i> My Results
        </a>
    </div>

    @if($results->isEmpty())
    <div class="empty-card bg-white rounded-2xl border border-[#e8f0fe] shadow-sm p-14 text-center card-in">
        <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-5"
             style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
            <i class="fas fa-file-pdf text-[#1A2A6C] text-3xl"></i>
        </div>
        <h2 class="text-[#0f172a] font-extrabold text-xl mb-2">No Reports Available</h2>
        <p class="text-[#64748b] text-sm max-w-sm mx-auto mb-6">
            Complete an assessment first to generate your personalised PDF report.
        </p>
        <a href="{{ route('exam.start') }}" class="rpt-btn rpt-btn-download">
            <i class="fas fa-rocket"></i> Start Assessment
        </a>
    </div>
    @else

    {{-- Info banner --}}
    <div class="info-banner flex items-start gap-3 rounded-2xl px-5 py-4 mb-6"
         style="background:#eff6ff;border:1.5px solid #bfdbfe;">
        <i class="fas fa-info-circle mt-0.5 shrink-0" style="color:#1A2A6C;"></i>
        <p class="text-sm leading-relaxed" style="color:#1e40af;">
            Each report is a professional PDF with your scores, recommended subject combination, career matches, and university suggestions.
            Share it with your parents, guardians, or school counsellor.
        </p>
    </div>

    {{-- Report cards --}}
    <div class="space-y-4">
        @foreach($results as $idx => $result)
        @php
            $pdfUrl = route('exam.result.pdf', $result->id);
            $comboColor = match($result->recommended_combination) {
                'Science'    => '#2E7D32',
                'Commercial' => '#ea580c',
                'Arts'       => '#7C3AED',
                default      => '#1A2A6C',
            };
            $comboIcon = match($result->recommended_combination) {
                'Science'    => 'fa-flask',
                'Commercial' => 'fa-chart-line',
                'Arts'       => 'fa-pen-nib',
                default      => 'fa-poll',
            };
            $whatsappText = urlencode('My PathFindr Assessment Report (' . $result->created_at->format('d M Y') . '): ' . $pdfUrl);
            $mailSubject  = urlencode('My PathFindr Assessment Report — ' . $result->created_at->format('d M Y'));
            $mailBody     = urlencode("Hi,\n\nPlease find my PathFindr Psychometric Assessment Report below:\n\n" . $pdfUrl . "\n\nScore: " . round($result->total_score, 1) . "%\nRecommendation: " . ($result->recommended_combination ?? 'N/A'));
        @endphp

        <div class="report-card bg-white rounded-2xl overflow-hidden card-in"
             style="border:1.5px solid #e8f0fe;box-shadow:0 2px 10px rgba(0,0,0,.04);animation-delay:{{ $idx * 0.08 }}s;">

            {{-- Top bar --}}
            <div class="px-5 py-4 flex flex-wrap items-center justify-between gap-3"
                 style="background:linear-gradient(135deg,#0f1f5c,#1A2A6C,#2D5F9E);">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                         style="background:rgba(255,255,255,.12);">
                        <i class="fas fa-file-pdf text-white text-sm"></i>
                    </div>
                    <div>
                        <div class="text-white font-bold text-sm">Assessment Report #{{ $results->count() - $idx }}</div>
                        <div class="text-white/55 text-xs mt-0.5">
                            <i class="fas fa-calendar-alt mr-1"></i>{{ $result->created_at->format('d F Y') }}
                            &nbsp;·&nbsp;
                            <i class="fas fa-clock mr-1"></i>{{ $result->created_at->format('h:i A') }}
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Score chip --}}
                    <div class="score-chip text-center px-3 py-1.5 rounded-xl"
                         style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.2);">
                        <span class="text-white font-extrabold text-base leading-none">{{ round($result->total_score,1) }}<span class="text-xs opacity-70">%</span></span>
                    </div>
                    {{-- Combo badge --}}
                    @if($result->recommended_combination)
                    <span class="inline-flex items-center gap-1.5 font-bold text-xs px-3 py-1.5 rounded-full"
                          style="background:{{ $comboColor }};color:#fff;">
                        <i class="fas {{ $comboIcon }} text-[10px]"></i>
                        {{ $result->recommended_combination }}
                    </span>
                    @endif
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="px-5 py-4 flex flex-wrap gap-2 report-divider" style="border-top:1px solid #e8f0fe;">
                <a href="{{ $pdfUrl }}" class="rpt-btn rpt-btn-download">
                    <i class="fas fa-download text-xs"></i> Download PDF
                </a>
                <a href="https://wa.me/?text={{ $whatsappText }}"
                   target="_blank" rel="noopener"
                   class="rpt-btn rpt-btn-whatsapp">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <a href="mailto:?subject={{ $mailSubject }}&body={{ $mailBody }}"
                   class="rpt-btn rpt-btn-email">
                    <i class="fas fa-envelope text-xs"></i> Email
                </a>
                <a href="{{ route('exam.result') }}" class="rpt-btn rpt-btn-view">
                    <i class="fas fa-eye text-xs"></i> View Online
                </a>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tip --}}
    <div class="tip-banner flex items-start gap-3 rounded-2xl px-5 py-4 mt-6"
         style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
        <i class="fas fa-lightbulb mt-0.5 shrink-0" style="color:#2E7D32;"></i>
        <p class="text-sm leading-relaxed" style="color:#14532d;">
            <strong>Tip:</strong> Share your report with your school counsellor or parents so they can guide you through your subject selection for SS1.
        </p>
    </div>

    @endif
</div>
@endsection
