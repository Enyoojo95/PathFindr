@extends('layouts.super-admin')
@section('title', 'Analytics — PathFindr Master')
@section('page-title', 'Analytics & Reports')

@section('content')

<div class="flex justify-end mb-6">
    <a href="{{ route('super-admin.analytics.download') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
       style="background:linear-gradient(135deg,#10B981,#2563EB);">
        <i class="fas fa-download text-xs"></i> Export CSV
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">

    {{-- Combo Distribution --}}
    <div class="glass p-6">
        <h3 class="font-bold text-white mb-5 flex items-center gap-2">
            <i class="fas fa-chart-pie text-blue-400 text-sm"></i> Subject Combination Distribution
        </h3>
        @php $total = $byCombo->sum('total') ?: 1; @endphp
        <div class="space-y-4">
            @foreach ($byCombo as $item)
            @php
                $pct = round(($item->total / $total) * 100);
                $color = match($item->recommended_combination) { 'Science' => '#10B981', 'Commercial' => '#F59E0B', default => '#2563EB' };
            @endphp
            <div>
                <div class="flex justify-between text-xs mb-1.5">
                    <span class="font-semibold text-white">{{ $item->recommended_combination }}</span>
                    <span style="color:#9CA3AF;">{{ $item->total }} students — {{ $pct }}%</span>
                </div>
                <div class="h-2.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                    <div class="h-full rounded-full" style="width:{{ $pct }}%; background:{{ $color }};"></div>
                </div>
            </div>
            @endforeach
            @if ($byCombo->isEmpty())
                <p class="text-white/30 text-sm">No data yet.</p>
            @endif
        </div>
    </div>

    {{-- Top Schools --}}
    <div class="glass p-6">
        <h3 class="font-bold text-white mb-5 flex items-center gap-2">
            <i class="fas fa-building text-purple-400 text-sm"></i> Top Schools by Students
        </h3>
        @php $maxS = $bySchool->max('students') ?: 1; @endphp
        <div class="space-y-3">
            @forelse ($bySchool as $s)
            @php $pct = round(($s->students / $maxS) * 100); @endphp
            <div class="flex items-center gap-3">
                <div class="w-28 text-xs truncate" style="color:#9CA3AF;" title="{{ $s->school_name }}">{{ $s->school_name }}</div>
                <div class="flex-1 h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                    <div class="h-full rounded-full" style="width:{{ $pct }}%; background:linear-gradient(90deg,#2563EB,#7C3AED);"></div>
                </div>
                <div class="text-xs font-bold text-white w-8 text-right">{{ $s->students }}</div>
            </div>
            @empty
            <p class="text-white/30 text-sm">No data yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Avg Score by School --}}
<div class="glass p-6">
    <h3 class="font-bold text-white mb-5 flex items-center gap-2">
        <i class="fas fa-medal text-amber-400 text-sm"></i> Average Score by School
    </h3>
    <div class="overflow-x-auto">
    <table class="w-full sa-table min-w-[400px]">
        <thead>
            <tr>
                <th class="text-left">School</th>
                <th class="text-left">Students Tested</th>
                <th class="text-left">Average Score</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($avgBySchool as $row)
            <tr>
                <td class="font-medium text-white">{{ $row->school_name }}</td>
                <td style="color:#9CA3AF;">{{ $row->count }}</td>
                <td>
                    <div class="flex items-center gap-3">
                        <span class="font-bold text-white">{{ $row->avg_score }}%</span>
                        <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.06);">
                            <div class="h-full rounded-full" style="width:{{ $row->avg_score }}%; background:#10B981;"></div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" class="text-center py-8 text-white/30">No test data yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@endsection
