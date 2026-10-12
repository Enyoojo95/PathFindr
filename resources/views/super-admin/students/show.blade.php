@extends('layouts.super-admin')
@section('title', '{{ $user->name }} — PathFindr Master')
@section('page-title', 'Student Results')

@section('content')

<a href="{{ route('super-admin.students.index') }}"
   class="inline-flex items-center gap-2 text-sm mb-6 transition-colors hover:text-white" style="color:#60A5FA;">
    <i class="fas fa-arrow-left text-xs"></i> Back to Students
</a>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Profile Card --}}
    <div class="glass p-6 text-center">
        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
             class="w-20 h-20 rounded-full object-cover mx-auto mb-4"
             style="border:3px solid rgba(37,99,235,0.4);">
        <h2 class="text-white font-bold text-lg">{{ $user->name }}</h2>
        <p class="text-sm mt-0.5" style="color:#9CA3AF;">{{ $user->email }}</p>
        <div class="mt-4 space-y-2 text-left">
            @foreach ([
                ['icon'=>'fa-school',      'label'=>'School',  'value'=> $user->school_name ?? optional($student)->school ?? '—'],
                ['icon'=>'fa-book',        'label'=>'Class',   'value'=> optional($student)->class ?? 'JSS3'],
                ['icon'=>'fa-birthday-cake','label'=>'Age',    'value'=> optional($student)->age ? optional($student)->age . ' yrs' : '—'],
                ['icon'=>'fa-venus-mars',  'label'=>'Gender',  'value'=> optional($student)->gender ? ucfirst(optional($student)->gender) : '—'],
                ['icon'=>'fa-calendar-alt','label'=>'Joined',  'value'=> $user->created_at->format('d M Y')],
            ] as $f)
            <div class="flex items-center gap-2.5 px-3 py-2 rounded-xl" style="background:rgba(255,255,255,0.04);">
                <i class="fas {{ $f['icon'] }} w-4 text-center text-xs" style="color:#60A5FA;"></i>
                <span class="text-xs" style="color:#9CA3AF;">{{ $f['label'] }}</span>
                <span class="text-xs font-semibold text-white ml-auto">{{ $f['value'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Scores + Results --}}
    <div class="lg:col-span-2 space-y-5">

        @if ($result)
        {{-- Score bars --}}
        <div class="glass p-6">
            <h3 class="font-bold text-white mb-5">Psychometric Scores</h3>
            @php
                $scores = [
                    ['label'=>'Numerical', 'value'=>$result->numerical_score, 'color'=>'#2563EB'],
                    ['label'=>'Verbal',    'value'=>$result->verbal_score,    'color'=>'#7C3AED'],
                    ['label'=>'Spatial',   'value'=>$result->spatial_score,   'color'=>'#10B981'],
                    ['label'=>'Interest',  'value'=>$result->interest_score,  'color'=>'#F59E0B'],
                ];
            @endphp
            <div class="space-y-4">
                @foreach ($scores as $s)
                <div>
                    <div class="flex justify-between text-xs mb-1.5">
                        <span style="color:#9CA3AF;">{{ $s['label'] }}</span>
                        <span class="font-bold text-white">{{ $s['value'] ?? '—' }}%</span>
                    </div>
                    <div class="h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.08);">
                        <div class="h-full rounded-full transition-all duration-700"
                             style="width:{{ $s['value'] ?? 0 }}%; background:{{ $s['color'] }};"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-5 pt-4 flex items-center justify-between" style="border-top:1px solid rgba(255,255,255,0.07);">
                <span class="text-sm" style="color:#9CA3AF;">Total Score</span>
                <span class="text-2xl font-extrabold text-white">{{ round($result->total_score, 1) }}<span class="text-base text-white/40">%</span></span>
            </div>
        </div>

        {{-- Recommendation --}}
        <div class="glass p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-white">Recommended Path</h3>
                <span class="badge text-sm px-3 py-1" style="background:rgba(37,99,235,0.2); color:#60A5FA;">
                    {{ $result->recommended_combination ?? '—' }}
                </span>
            </div>

            @if ($result->careers)
            <div class="mb-4">
                <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#9CA3AF;">Career Matches</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (is_array($result->careers) ? $result->careers : json_decode($result->careers, true) ?? [] as $career)
                    <span class="badge px-3 py-1" style="background:rgba(16,185,129,0.12); color:#6EE7B7;">
                        <i class="fas fa-briefcase text-[10px]"></i> {{ $career }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($result->universities)
            <div class="mb-4">
                <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#9CA3AF;">University Matches</p>
                <div class="flex flex-wrap gap-2">
                    @foreach (is_array($result->universities) ? $result->universities : json_decode($result->universities, true) ?? [] as $uni)
                    <span class="badge px-3 py-1" style="background:rgba(124,58,237,0.12); color:#C4B5FD;">
                        <i class="fas fa-university text-[10px]"></i> {{ $uni }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            @if ($result->motivational_message)
            <div class="mt-3 p-4 rounded-xl text-sm italic" style="background:rgba(255,255,255,0.04); color:rgba(255,255,255,0.6); border-left:3px solid #2563EB;">
                "{{ $result->motivational_message }}"
            </div>
            @endif
        </div>

        @else
        <div class="glass p-10 text-center">
            <i class="fas fa-clock text-4xl mb-4 block" style="color:rgba(255,255,255,0.15);"></i>
            <p class="text-white/40">This student hasn't taken the test yet.</p>
        </div>
        @endif
    </div>
</div>

@endsection
