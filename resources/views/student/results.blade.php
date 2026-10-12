@extends('layouts.dashboard')
@section('title', 'My Results — PathFindr')
@section('page-title', 'My Results')

@section('styles')
<style>
.score-ring { transform: rotate(-90deg); transform-origin: 50% 50%; }
.card-in { animation: cardIn 0.4s ease forwards; opacity: 0; transform: translateY(16px); }
@keyframes cardIn { to { opacity:1; transform:translateY(0); } }
.combo-science    { background:linear-gradient(135deg,#e8f5e9,#f0fdf4); border-color:#2E7D32; }
.combo-commercial { background:linear-gradient(135deg,#fff3e0,#fff7ed); border-color:#ea580c; }
.combo-arts       { background:linear-gradient(135deg,#ede9fe,#f5f3ff); border-color:#7C3AED; }
.bar-fill { transition: width 1.2s cubic-bezier(0.4,0,0.2,1); }
</style>
@endsection

@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-extrabold text-[#1A2A6C]">My Assessment Results</h1>
            <p class="text-[#2D3748]/60 text-sm mt-0.5">Your complete performance history</p>
        </div>
        <a href="{{ route('student.reports') }}"
           class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#2D5F9E] text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm">
            <i class="fas fa-file-pdf"></i> PDF Reports
        </a>
    </div>

    @if($results->isEmpty())
    {{-- Empty state --}}
    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md p-14 text-center card-in">
        <div class="w-20 h-20 bg-[#EBF4FF] rounded-full flex items-center justify-center mx-auto mb-5">
            <i class="fas fa-chart-bar text-[#1A2A6C] text-3xl"></i>
        </div>
        <h2 class="text-[#1A2A6C] font-extrabold text-xl mb-2">No Results Yet</h2>
        <p class="text-[#2D3748]/60 text-sm max-w-sm mx-auto mb-6">
            You haven't completed an assessment yet. Take the 30-minute test to discover your strengths and get your personalised path.
        </p>
        <a href="{{ route('exam.start') }}"
           class="inline-flex items-center gap-2 bg-[#43A047] hover:bg-emerald-600 text-white font-bold px-7 py-3 rounded-full text-sm shadow-md transition-all duration-200">
            <i class="fas fa-rocket"></i> Start Assessment
        </a>
    </div>
    @else

    @foreach($results as $idx => $result)
    @php
        $session  = $sessions->get($result->id);
        $timeTaken = null;
        if ($session && $session->started_at) {
            $end = $session->updated_at ?? $result->created_at;
            $diff = $session->started_at->diff($end);
            $timeTaken = ($diff->h > 0 ? $diff->h.'h ' : '') . $diff->i.'m ' . $diff->s.'s';
        }
        $qAnswered = $session ? count(array_filter($session->question_ids ?? [], fn($id) => true)) : '—';
        $comboClass = match($result->recommended_combination) {
            'Science'    => 'combo-science',
            'Commercial' => 'combo-commercial',
            'Arts'       => 'combo-arts',
            default      => '',
        };
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
            default      => 'fa-question',
        };
        $bars = [
            ['Numerical', $result->numerical_score, '#1A2A6C'],
            ['Verbal',    $result->verbal_score,    '#2D5F9E'],
            ['Spatial',   $result->spatial_score,   '#7C3AED'],
            ['Interest',  $result->interest_score,  '#43A047'],
        ];
        $total = round($result->total_score ?? 0, 1);
        $circumference = 2 * M_PI * 36; // r=36
        $dash = ($total / 100) * $circumference;
    @endphp

    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md mb-6 overflow-hidden card-in"
         style="animation-delay:{{ $idx * 0.1 }}s;">

        {{-- Top bar --}}
        <div class="bg-[#1A2A6C] px-6 py-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white/15 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-poll text-white text-base"></i>
                </div>
                <div>
                    <div class="text-white font-bold text-sm">Assessment #{{ $results->count() - $idx }}</div>
                    <div class="text-white/60 text-xs">{{ $result->created_at->format('l, d F Y · h:i A') }}</div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @if($result->recommended_combination)
                <span class="inline-flex items-center gap-1.5 bg-[#F5A623] text-[#0f172a] font-bold text-xs px-3 py-1.5 rounded-full">
                    <i class="fas {{ $comboIcon }} text-[10px]"></i>
                    {{ $result->recommended_combination }}
                </span>
                @endif
                <a href="{{ route('exam.result.pdf', $result->id) }}"
                   class="inline-flex items-center gap-1.5 bg-white/15 hover:bg-white/25 text-white font-semibold text-xs px-3 py-1.5 rounded-full transition-all duration-200">
                    <i class="fas fa-download text-[10px]"></i> PDF
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Score ring --}}
                <div class="flex flex-col items-center justify-center bg-[#f8faff] rounded-2xl p-6 border border-[#EBF4FF]">
                    <svg width="100" height="100" viewBox="0 0 100 100" class="mb-3">
                        <circle cx="50" cy="50" r="36" fill="none" stroke="#EBF4FF" stroke-width="9"/>
                        <circle cx="50" cy="50" r="36" fill="none" stroke="#1A2A6C" stroke-width="9"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $dash }} {{ $circumference }}"
                                class="score-ring"/>
                    </svg>
                    <div class="text-[#1A2A6C] text-3xl font-extrabold leading-none -mt-2">{{ $total }}<span class="text-lg font-bold text-[#2D3748]/40">%</span></div>
                    <div class="text-[#2D3748]/50 text-xs font-semibold uppercase tracking-wider mt-1">Overall Score</div>
                </div>

                {{-- Score bars --}}
                <div class="lg:col-span-2 space-y-3">
                    <div class="text-[#1A2A6C] font-bold text-sm mb-4">Score Breakdown</div>
                    @foreach($bars as [$label, $score, $color])
                    @php $s = round($score ?? 0, 1); @endphp
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-[#2D3748]">{{ $label }}</span>
                            <span style="color:{{ $color }}">{{ $s }}%</span>
                        </div>
                        <div class="h-2.5 bg-[#EBF4FF] rounded-full overflow-hidden">
                            <div class="h-full rounded-full bar-fill" style="width:{{ $s }}%;background:{{ $color }};"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Meta info strip --}}
            <div class="mt-5 pt-5 border-t border-[#EBF4FF] grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="w-8 h-8 bg-[#EBF4FF] rounded-xl flex items-center justify-center mx-auto mb-1.5">
                        <i class="fas fa-calendar-alt text-[#1A2A6C] text-xs"></i>
                    </div>
                    <div class="text-[#1A2A6C] font-bold text-sm">{{ $result->created_at->format('d M Y') }}</div>
                    <div class="text-[#2D3748]/50 text-xs">Date Taken</div>
                </div>
                <div class="text-center">
                    <div class="w-8 h-8 bg-[#EBF4FF] rounded-xl flex items-center justify-center mx-auto mb-1.5">
                        <i class="fas fa-clock text-[#2D5F9E] text-xs"></i>
                    </div>
                    <div class="text-[#1A2A6C] font-bold text-sm">{{ $result->created_at->format('h:i A') }}</div>
                    <div class="text-[#2D3748]/50 text-xs">Time Submitted</div>
                </div>
                <div class="text-center">
                    <div class="w-8 h-8 bg-[#EBF4FF] rounded-xl flex items-center justify-center mx-auto mb-1.5">
                        <i class="fas fa-stopwatch text-[#43A047] text-xs"></i>
                    </div>
                    <div class="text-[#1A2A6C] font-bold text-sm">{{ $timeTaken ?? '~30 min' }}</div>
                    <div class="text-[#2D3748]/50 text-xs">Time Taken</div>
                </div>
                <div class="text-center">
                    <div class="w-8 h-8 bg-[#EBF4FF] rounded-xl flex items-center justify-center mx-auto mb-1.5">
                        <i class="fas fa-list-check text-[#F5A623] text-xs"></i>
                    </div>
                    <div class="text-[#1A2A6C] font-bold text-sm">{{ is_array($session?->question_ids ?? null) ? count($session->question_ids) : '30' }}</div>
                    <div class="text-[#2D3748]/50 text-xs">Questions</div>
                </div>
            </div>

            {{-- Recommendation --}}
            @if($result->recommended_combination)
            <div class="mt-5 rounded-xl border-l-4 p-4 {{ $comboClass }}" style="border-left-color:{{ $comboColor }};">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:{{ $comboColor }};">
                        <i class="fas {{ $comboIcon }} text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-sm" style="color:{{ $comboColor }};">
                            Recommended: {{ $result->recommended_combination }}
                        </div>
                        @php
                            $subs = match($result->recommended_combination) {
                                'Science'    => 'Physics · Chemistry · Biology · Mathematics',
                                'Commercial' => 'Economics · Accounting · Commerce · Business Studies',
                                'Arts'       => 'Literature · Government · History · CRK/IRS',
                                default      => '',
                            };
                        @endphp
                        <div class="text-xs text-[#2D3748]/70 mt-0.5">{{ $subs }}</div>
                        @if(!empty($result->careers))
                        <div class="flex flex-wrap gap-1.5 mt-2">
                            @foreach(array_slice($result->careers, 0, 4) as $career)
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium"
                                  style="background:{{ $comboColor }}18;color:{{ $comboColor }};">
                                {{ is_array($career) ? ($career['title'] ?? $career['name'] ?? '') : $career }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Motivational message --}}
            @if(!empty($result->motivational_message))
            <div class="mt-4 bg-[#f0fff4] border-l-4 border-[#43A047] rounded-r-xl px-4 py-3">
                <p class="text-[#14532d] text-sm italic leading-relaxed">"{{ $result->motivational_message }}"</p>
            </div>
            @endif
        </div>
    </div>
    @endforeach

    {{-- Progress visual (if more than 1 result) --}}
    @if($results->count() > 1)
    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md p-6 card-in" style="animation-delay:0.3s;">
        <div class="flex items-center gap-2 mb-5">
            <div class="w-8 h-8 bg-[#1A2A6C] rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-white text-xs"></i>
            </div>
            <h3 class="text-[#1A2A6C] font-bold">Score Progress Over Time</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#EBF4FF]">
                        <th class="text-left text-[#2D3748]/50 font-semibold text-xs pb-2 pr-4">Assessment</th>
                        <th class="text-center text-[#1A2A6C] font-semibold text-xs pb-2 px-2">Numerical</th>
                        <th class="text-center text-[#2D5F9E] font-semibold text-xs pb-2 px-2">Verbal</th>
                        <th class="text-center text-[#7C3AED] font-semibold text-xs pb-2 px-2">Spatial</th>
                        <th class="text-center text-[#43A047] font-semibold text-xs pb-2 px-2">Interest</th>
                        <th class="text-center text-[#F5A623] font-semibold text-xs pb-2 pl-2">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBF4FF]">
                    @foreach($results->reverse() as $r)
                    <tr class="hover:bg-[#f8faff] transition-colors">
                        <td class="py-2.5 pr-4">
                            <div class="text-[#1A2A6C] font-semibold text-xs">{{ $r->created_at->format('d M Y') }}</div>
                            <div class="text-[#2D3748]/40 text-[10px]">{{ $r->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="text-center py-2.5 px-2 font-bold text-xs text-[#1A2A6C]">{{ round($r->numerical_score,1) }}%</td>
                        <td class="text-center py-2.5 px-2 font-bold text-xs text-[#2D5F9E]">{{ round($r->verbal_score,1) }}%</td>
                        <td class="text-center py-2.5 px-2 font-bold text-xs text-[#7C3AED]">{{ round($r->spatial_score,1) }}%</td>
                        <td class="text-center py-2.5 px-2 font-bold text-xs text-[#43A047]">{{ round($r->interest_score,1) }}%</td>
                        <td class="text-center py-2.5 pl-2">
                            <span class="inline-block bg-[#1A2A6C] text-white font-extrabold text-xs px-2.5 py-1 rounded-full">
                                {{ round($r->total_score,1) }}%
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @endif
</div>
@endsection
