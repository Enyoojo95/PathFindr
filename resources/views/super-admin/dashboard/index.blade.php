@extends('layouts.super-admin')
@section('title', 'Overview — PathFindr Master')
@section('page-title', 'Overview Dashboard')

@section('content')

{{-- Stat Cards --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; margin-bottom:20px;">
    @php
        $stats = [
            ['label'=>'Total Students',  'value'=>$totalStudents,  'icon'=>'fa-users',        'grad'=>'linear-gradient(135deg,#2563EB,#1D4ED8)', 'glow'=>'rgba(37,99,235,0.25)'],
            ['label'=>'Total Schools',   'value'=>$totalSchools,   'icon'=>'fa-building',     'grad'=>'linear-gradient(135deg,#7C3AED,#6D28D9)', 'glow'=>'rgba(124,58,237,0.25)'],
            ['label'=>'Admins',          'value'=>$totalAdmins,    'icon'=>'fa-user-shield',  'grad'=>'linear-gradient(135deg,#10B981,#059669)', 'glow'=>'rgba(16,185,129,0.25)'],
            ['label'=>'Tests Completed', 'value'=>$testsCompleted,'icon'=>'fa-check-circle',  'grad'=>'linear-gradient(135deg,#F59E0B,#D97706)', 'glow'=>'rgba(245,158,11,0.25)'],
        ];
    @endphp
    @foreach($stats as $s)
    <div class="glass-card" style="padding:22px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:14px;">
            <div>
                <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(255,255,255,0.65);margin-bottom:6px;">{{ $s['label'] }}</p>
                <p style="font-size:2.2rem;font-weight:900;color:#fff;line-height:1;">{{ number_format($s['value']) }}</p>
            </div>
            <div style="width:44px;height:44px;border-radius:13px;background:{{ $s['grad'] }};
                        display:flex;align-items:center;justify-content:center;
                        box-shadow:0 6px 20px {{ $s['glow'] }}, inset 0 1px 0 rgba(255,255,255,0.2);">
                <i class="fas {{ $s['icon'] }}" style="color:#fff;font-size:1.1rem;"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Row 2: Avg Score + Top Combo + Chart --}}
<div style="display:grid; grid-template-columns:1fr 1fr 2fr; gap:16px; margin-bottom:20px;">

    {{-- Avg Score --}}
    <div class="glass-card" style="padding:22px;">
        <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(255,255,255,0.65);margin-bottom:12px;">Avg Score</p>
        @if($avgScore)
        <p style="font-size:3rem;font-weight:900;color:#fff;line-height:1;margin-bottom:10px;">
            {{ $avgScore }}<span style="font-size:1.4rem;color:rgba(255,255,255,0.65);">%</span>
        </p>
        <div style="height:5px;border-radius:99px;background:rgba(255,255,255,0.12);overflow:hidden;">
            <div style="height:100%;width:{{ $avgScore }}%;background:linear-gradient(90deg,#2563EB,#7C3AED);border-radius:99px;transition:width 1s ease;"></div>
        </div>
        <p style="font-size:0.75rem;color:rgba(255,255,255,0.6);margin-top:8px;">across all tests</p>
        @else
        <p style="color:rgba(255,255,255,0.6);font-size:0.9rem;">No test data yet</p>
        @endif
    </div>

    {{-- Top Combination --}}
    <div class="glass-card" style="padding:22px;">
        <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(255,255,255,0.65);margin-bottom:12px;">Top Path</p>
        @if($topCombo)
        <p style="font-size:1.5rem;font-weight:900;color:#fff;margin-bottom:6px;">{{ $topCombo->recommended_combination }}</p>
        <div style="display:inline-flex;align-items:center;gap:6px;padding:4px 12px;border-radius:99px;
                    background:rgba(245,158,11,0.18);border:1px solid rgba(245,158,11,0.35);
                    font-size:0.72rem;font-weight:700;color:#FDE68A;">
            <i class="fas fa-trophy" style="font-size:0.65rem;"></i> {{ $topCombo->cnt }} students
        </div>
        @else
        <p style="color:rgba(255,255,255,0.6);font-size:0.9rem;">No data yet</p>
        @endif
    </div>

    {{-- Trend Chart --}}
    <div class="glass-card" style="padding:22px;">
        <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(255,255,255,0.65);margin-bottom:16px;">Test Completions — Last 6 Months</p>
        @if($trends->isNotEmpty())
        @php $maxC = $trends->max('count') ?: 1; @endphp
        <div style="display:flex;align-items:flex-end;gap:8px;height:80px;">
            @foreach($trends as $t)
            @php $h = max(round(($t->count / $maxC) * 100), 5); @endphp
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;">
                <span style="font-size:0.65rem;color:rgba(255,255,255,0.7);font-weight:600;">{{ $t->count }}</span>
                <div style="width:100%;height:{{ $h }}%;border-radius:6px 6px 0 0;
                            background:linear-gradient(180deg,rgba(37,99,235,0.9),rgba(124,58,237,0.7));
                            min-height:4px; transition:height 1s ease;">
                </div>
                <span style="font-size:0.65rem;color:rgba(255,255,255,0.65);">{{ $t->month }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:rgba(255,255,255,0.6);font-size:0.85rem;">Not enough data yet.</p>
        @endif
    </div>
</div>

{{-- Row 3: Quick Actions + Combination Breakdown --}}
<div style="display:grid; grid-template-columns:1fr 1.5fr; gap:16px; margin-bottom:20px;">

    {{-- Quick Actions --}}
    <div class="glass-card" style="padding:22px;">
        <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(255,255,255,0.65);margin-bottom:14px;">Quick Actions</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach([
                ['label'=>'View All Students', 'icon'=>'fa-users',     'route'=>'super-admin.students.index', 'color'=>'#2563EB'],
                ['label'=>'Add Question',      'icon'=>'fa-plus',      'route'=>'super-admin.questions.index','color'=>'#7C3AED'],
                ['label'=>'Analytics Report',  'icon'=>'fa-chart-line','route'=>'super-admin.analytics.index','color'=>'#10B981'],
                ['label'=>'System Settings',   'icon'=>'fa-sliders',   'route'=>'super-admin.settings.index', 'color'=>'#F59E0B'],
            ] as $a)
            <a href="{{ route($a['route']) }}"
               style="display:flex;align-items:center;gap:10px;padding:9px 13px;border-radius:12px;
                      text-decoration:none;color:rgba(255,255,255,0.85);font-size:0.82rem;font-weight:500;
                      background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);
                      transition:all 0.2s ease;"
               onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.borderColor='rgba(255,255,255,0.2)';this.style.color='#fff';this.style.transform='translateX(3px)';"
               onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.borderColor='rgba(255,255,255,0.1)';this.style.color='rgba(255,255,255,0.85)';this.style.transform='none';">
                <span style="width:28px;height:28px;border-radius:8px;background:{{ $a['color'] }}28;
                             display:flex;align-items:center;justify-content:center;color:{{ $a['color'] }};font-size:0.72rem;">
                    <i class="fas {{ $a['icon'] }}"></i>
                </span>
                {{ $a['label'] }}
                <i class="fas fa-chevron-right" style="margin-left:auto;font-size:0.6rem;color:rgba(255,255,255,0.45);"></i>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Combination Breakdown --}}
    <div class="glass-card" style="padding:22px;">
        <p style="font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.07em;color:rgba(255,255,255,0.65);margin-bottom:14px;">Subject Distribution</p>
        @php
            $allCombos = \App\Models\TestResult::whereNotNull('recommended_combination')
                ->selectRaw('recommended_combination, COUNT(*) as total')
                ->groupBy('recommended_combination')->get();
            $total = $allCombos->sum('total') ?: 1;
            $comboColors = ['Science'=>'#10B981','Commercial'=>'#F59E0B','Arts'=>'#2563EB'];
        @endphp
        @if($allCombos->isNotEmpty())
        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($allCombos as $c)
            @php $pct = round(($c->total / $total) * 100); $col = $comboColors[$c->recommended_combination] ?? '#7C3AED'; @endphp
            <div>
                <div style="display:flex;justify-content:space-between;font-size:0.78rem;margin-bottom:6px;">
                    <span style="color:rgba(255,255,255,0.9);font-weight:600;">{{ $c->recommended_combination }}</span>
                    <span style="color:rgba(255,255,255,0.65);">{{ $c->total }} · {{ $pct }}%</span>
                </div>
                <div style="height:5px;border-radius:99px;background:rgba(255,255,255,0.1);">
                    <div style="height:100%;width:{{ $pct }}%;background:{{ $col }};border-radius:99px;transition:width 1s ease;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p style="color:rgba(255,255,255,0.6);font-size:0.85rem;">No recommendations yet.</p>
        @endif
    </div>

</div>

{{-- Row 4: Pending Testimonials --}}
@if($pendingTestimonialsCount > 0)
<div class="glass-card" style="padding:22px;margin-top:0;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="width:32px;height:32px;border-radius:10px;background:rgba(245,158,11,0.2);
                        border:1px solid rgba(245,158,11,0.35);display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-comment-dots" style="color:#FDE68A;font-size:0.85rem;"></i>
            </div>
            <div>
                <p style="font-size:0.78rem;font-weight:700;color:#fff;margin:0;">Pending Testimonials</p>
                <p style="font-size:0.68rem;color:rgba(255,255,255,0.45);margin:0;">{{ $pendingTestimonialsCount }} awaiting approval</p>
            </div>
        </div>
        <a href="{{ route('super-admin.testimonials.index') }}?filter=pending"
           style="display:inline-flex;align-items:center;gap:6px;padding:6px 14px;border-radius:8px;
                  background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);
                  color:#FDE68A;font-size:0.72rem;font-weight:700;text-decoration:none;transition:all .18s;"
           onmouseover="this.style.background='rgba(245,158,11,0.28)'"
           onmouseout="this.style.background='rgba(245,158,11,0.15)'">
            View All <i class="fas fa-arrow-right" style="font-size:0.6rem;"></i>
        </a>
    </div>
    <div style="display:flex;flex-direction:column;gap:10px;">
        @foreach($pendingTestimonials as $t)
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;
                    padding:12px 14px;border-radius:12px;
                    background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
            <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">
                <img src="{{ $t->user->avatarUrl() }}" alt="{{ $t->user->name }}"
                     style="width:32px;height:32px;border-radius:50%;object-fit:cover;
                            border:1.5px solid rgba(255,255,255,0.1);flex-shrink:0;">
                <div style="min-width:0;">
                    <div style="font-weight:700;color:#fff;font-size:0.78rem;">{{ $t->user->name }}</div>
                    <div style="font-size:0.7rem;color:rgba(255,255,255,0.5);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:260px;">
                        {{ Str::limit($t->body, 80) }}
                    </div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                {{-- Star rating --}}
                <div style="display:flex;gap:2px;">
                    @for($s=1;$s<=5;$s++)
                    <i class="fa-star {{ $s<=$t->rating?'fas':'far' }}"
                       style="font-size:0.65rem;color:{{ $s<=$t->rating?'#F59E0B':'rgba(255,255,255,0.15)' }};"></i>
                    @endfor
                </div>
                {{-- Quick approve --}}
                <form method="POST" action="{{ route('testimonials.approve', $t) }}" style="margin:0;">
                    @csrf
                    <button type="submit"
                            style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;
                                   border-radius:8px;border:none;cursor:pointer;font-size:0.7rem;font-weight:700;
                                   background:rgba(16,185,129,0.2);border:1px solid rgba(16,185,129,0.35);color:#6EE7B7;
                                   transition:all .15s;"
                            onmouseover="this.style.background='rgba(16,185,129,0.35)'"
                            onmouseout="this.style.background='rgba(16,185,129,0.2)'">
                        <i class="fas fa-check" style="font-size:0.6rem;"></i> Approve
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

@endsection
