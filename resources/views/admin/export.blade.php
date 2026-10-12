@extends('layouts.dashboard')
@section('title', 'Export Results — PathFindr Admin')
@section('page-title', 'Export Results')

@section('styles')
<style>
.share-btn {
    display:inline-flex; align-items:center; gap:6px;
    font-size:.72rem; font-weight:700;
    padding:5px 11px; border-radius:8px; border:none;
    cursor:pointer; transition:all .18s ease;
    text-decoration:none; white-space:nowrap;
}
.share-btn:hover { transform:translateY(-1px); }
.share-wa   { background:#25D366; color:#fff; box-shadow:0 2px 6px rgba(37,211,102,.3); }
.share-wa:hover   { background:#1aad55; box-shadow:0 4px 12px rgba(37,211,102,.35); }
.share-mail { background:#0284c7; color:#fff; box-shadow:0 2px 6px rgba(2,132,199,.25); }
.share-mail:hover { background:#0369a1; box-shadow:0 4px 12px rgba(2,132,199,.3); }
.share-pdf  { background:#1A2A6C; color:#fff; box-shadow:0 2px 6px rgba(26,42,108,.25); }
.share-pdf:hover  { background:#0f1f5c; box-shadow:0 4px 12px rgba(26,42,108,.3); }
html.dark table tbody tr:hover td { background:rgba(255,255,255,.03) !important; }
</style>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1A2A6C]">Export Results</h1>
            <p class="text-gray-500 text-sm">
                @if($admin->isSuperAdmin()) All schools — @else {{ $admin->school_name }} — @endif
                {{ $totalStudents }} student{{ $totalStudents !== 1 ? 's' : '' }},
                {{ $testsCompleted }} completed
            </p>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 border border-[#EBF4FF] shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-[#1A2A6C] rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-users text-[#F5A623]"></i>
            </div>
            <div>
                <div class="text-[#1A2A6C] text-2xl font-extrabold">{{ $totalStudents }}</div>
                <div class="text-gray-400 text-xs">Total Students</div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-[#EBF4FF] shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-[#43A047]/10 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-check-circle text-[#43A047]"></i>
            </div>
            <div>
                <div class="text-[#1A2A6C] text-2xl font-extrabold">{{ $testsCompleted }}</div>
                <div class="text-gray-400 text-xs">Tests Completed</div>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-[#EBF4FF] shadow-sm flex items-center gap-4">
            <div class="w-10 h-10 bg-[#F5A623]/10 rounded-xl flex items-center justify-center shrink-0">
                <i class="fas fa-clock text-[#b07d0a]"></i>
            </div>
            <div>
                <div class="text-[#1A2A6C] text-2xl font-extrabold">{{ $totalStudents - $testsCompleted }}</div>
                <div class="text-gray-400 text-xs">Yet to Test</div>
            </div>
        </div>
    </div>

    {{-- Download Card --}}
    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md p-7 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-[#43A047]/10 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-csv text-[#43A047] text-xl"></i>
                </div>
                <div>
                    <div class="text-[#1A2A6C] font-bold text-base">Download CSV Report</div>
                    <div class="text-gray-400 text-xs">pathfindr-results-{{ now()->format('Y-m-d') }}.csv</div>
                </div>
            </div>
            <p class="text-gray-500 text-sm">
                Includes: Name, Email, School, Test Status, Score, Recommended Combination, Registration Date.
            </p>
        </div>
        <a href="{{ route('admin.export.download') }}"
           class="inline-flex items-center gap-2 bg-[#43A047] hover:bg-emerald-600 text-white font-bold px-7 py-3.5 rounded-xl text-sm transition-all duration-200 shadow-md whitespace-nowrap">
            <i class="fas fa-download text-xs"></i> Download CSV
        </a>
    </div>

    {{-- Preview Table --}}
    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EBF4FF] flex items-center justify-between flex-wrap gap-3">
            <h3 class="text-[#1A2A6C] font-bold text-sm flex items-center gap-2">
                <i class="fas fa-table text-[#F5A623]"></i>
                Data Preview ({{ $totalStudents }} student{{ $totalStudents !== 1 ? 's' : '' }})
            </h3>
            @if($testsCompleted > 0)
            <span class="text-xs text-gray-400 flex items-center gap-1.5">
                <i class="fas fa-share-alt text-[#2D5F9E]"></i>
                Share buttons appear for completed results from your school
            </span>
            @endif
        </div>

        @if($students->isEmpty())
        <div class="py-12 text-center text-gray-400 text-sm">
            <i class="fas fa-database text-4xl mb-3 block opacity-20"></i> No data to export yet.
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#EBF4FF]">
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Student</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">School</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Status</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Score</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Combination</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Joined</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Share</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBF4FF]">
                    @foreach($students as $user)
                    @php
                        $result = $user->testResults->first();

                        // Admin can share only if: result exists AND student is from same school
                        $sameSchool = $admin->isSuperAdmin()
                            || (strtolower($admin->school_name ?? '') === strtolower($user->school_name ?? ''));

                        $canShare = $result && $sameSchool;

                        if ($canShare) {
                            $pdfUrl      = route('exam.result.pdf', $result->id);
                            $waText      = urlencode(
                                "Hello {$user->name},\n\nYour PathFindr Psychometric Assessment Report is ready.\n\n"
                                . "Score: " . round($result->total_score, 1) . "%\n"
                                . "Recommended: " . ($result->recommended_combination ?? 'N/A') . "\n\n"
                                . "Download your report: {$pdfUrl}\n\n"
                                . "— {$admin->school_name}"
                            );
                            $mailSubject = urlencode("Your PathFindr Assessment Report — {$user->name}");
                            $mailBody    = urlencode(
                                "Dear {$user->name},\n\n"
                                . "Your PathFindr Psychometric Assessment Report is now available.\n\n"
                                . "Score: " . round($result->total_score, 1) . "%\n"
                                . "Recommended Subject Combination: " . ($result->recommended_combination ?? 'N/A') . "\n\n"
                                . "Download your PDF report here:\n{$pdfUrl}\n\n"
                                . "Best regards,\n{$admin->school_name}"
                            );
                        }
                    @endphp
                    <tr class="hover:bg-[#EBF4FF]/40 transition-colors duration-150">

                        {{-- Student --}}
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                                     class="w-8 h-8 rounded-full object-cover border-2 border-[#EBF4FF] shrink-0">
                                <div>
                                    <div class="font-semibold text-[#1A2A6C] text-sm">{{ $user->name }}</div>
                                    <div class="text-gray-400 text-xs">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- School --}}
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $user->school_name ?? '—' }}</td>

                        {{-- Status --}}
                        <td class="px-5 py-3">
                            @if($result)
                            <span class="inline-flex items-center gap-1 text-[#2E7D32] text-xs font-semibold bg-[#e8f5e9] border border-[#a5d6a7] px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-check text-[9px]"></i> Done
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[#92400e] text-xs font-semibold bg-[#fff3e0] border border-[#fcd34d] px-2.5 py-0.5 rounded-full">
                                <i class="fas fa-clock text-[9px]"></i> Pending
                            </span>
                            @endif
                        </td>

                        {{-- Score --}}
                        <td class="px-5 py-3 font-extrabold text-[#1A2A6C] text-sm">
                            {{ $result ? round($result->total_score, 1).'%' : '—' }}
                        </td>

                        {{-- Combination --}}
                        <td class="px-5 py-3">
                            @if($result?->recommended_combination)
                            @php
                                $comboStyle = match($result->recommended_combination) {
                                    'Science'    => 'color:#166534;background:#dcfce7;border-color:#86efac;',
                                    'Commercial' => 'color:#7c2d12;background:#fff3e0;border-color:#fcd34d;',
                                    'Arts'       => 'color:#4c1d95;background:#ede9fe;border-color:#c4b5fd;',
                                    default      => 'color:#1A2A6C;background:#eff6ff;border-color:#bfdbfe;',
                                };
                            @endphp
                            <span class="inline-flex items-center text-xs font-bold px-2.5 py-0.5 rounded-full border"
                                  style="{{ $comboStyle }}">
                                {{ $result->recommended_combination }}
                            </span>
                            @else
                            <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Joined --}}
                        <td class="px-5 py-3 text-gray-400 text-xs whitespace-nowrap">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- Share --}}
                        <td class="px-5 py-3">
                            @if($canShare)
                            <div class="flex items-center gap-1.5 flex-wrap">
                                <a href="https://wa.me/?text={{ $waText }}"
                                   target="_blank" rel="noopener"
                                   class="share-btn share-wa"
                                   title="Share via WhatsApp">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </a>
                                <a href="mailto:{{ $user->email }}?subject={{ $mailSubject }}&body={{ $mailBody }}"
                                   class="share-btn share-mail"
                                   title="Send via Email">
                                    <i class="fas fa-envelope"></i> Email
                                </a>
                                <a href="{{ route('exam.result.pdf', $result->id) }}"
                                   class="share-btn share-pdf"
                                   title="Download PDF">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a>
                            </div>
                            @elseif(!$result)
                            <span class="text-gray-300 text-xs italic">Not completed</span>
                            @else
                            <span class="text-gray-300 text-xs italic">Different school</span>
                            @endif
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
