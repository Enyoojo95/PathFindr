@extends('layouts.super-admin')
@section('title', 'Students — PathFindr Master')
@section('page-title', 'All Students')

@section('content')

<div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-bottom:20px;">
    <form method="GET" style="display:flex;gap:8px;flex:1;max-width:360px;">
        <div style="position:relative;flex:1;">
            <i class="fas fa-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.25);font-size:0.75rem;pointer-events:none;"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Name, email, or school..."
                   class="glass-input" style="padding-left:36px;">
        </div>
        <button type="submit" class="glass-btn glass-btn-primary" style="padding:10px 16px;white-space:nowrap;">
            <i class="fas fa-search" style="font-size:0.75rem;"></i>
        </button>
    </form>
    <div style="font-size:0.75rem;color:rgba(255,255,255,0.3);">{{ $students->total() }} students</div>
</div>

<div class="glass-card" style="overflow:hidden;padding:0;">
    <div style="overflow-x:auto;">
    <table class="glass-table" style="width:100%;min-width:680px;border-collapse:collapse;">
        <thead>
            <tr>
                <th style="text-align:left;">Student</th>
                <th style="text-align:left;">School</th>
                <th style="text-align:left;">Status</th>
                <th style="text-align:left;">Score</th>
                <th style="text-align:left;">Combination</th>
                <th style="text-align:left;">Joined</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $user)
            @php $result = $user->testResults->first(); @endphp
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                             style="width:32px;height:32px;border-radius:50%;object-fit:cover;border:1px solid rgba(255,255,255,0.1);">
                        <div>
                            <div style="color:#fff;font-weight:600;font-size:0.82rem;">{{ $user->name }}</div>
                            <div style="color:rgba(255,255,255,0.3);font-size:0.72rem;">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td style="font-size:0.78rem;color:rgba(255,255,255,0.4);">{{ $user->school_name ?? '—' }}</td>
                <td>
                    @if($result)
                    <span class="glass-badge" style="background:rgba(16,185,129,0.12);border-color:rgba(16,185,129,0.25);color:#6EE7B7;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#10B981;display:inline-block;"></span> Done
                    </span>
                    @else
                    <span class="glass-badge" style="background:rgba(245,158,11,0.12);border-color:rgba(245,158,11,0.25);color:#FCD34D;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#F59E0B;display:inline-block;"></span> Pending
                    </span>
                    @endif
                </td>
                <td style="font-weight:700;color:#fff;font-size:0.82rem;">{{ $result ? round($result->total_score,1).'%' : '—' }}</td>
                <td style="font-size:0.78rem;color:rgba(255,255,255,0.4);">{{ $result?->recommended_combination ?? '—' }}</td>
                <td style="font-size:0.72rem;color:rgba(255,255,255,0.3);">{{ $user->created_at->format('d M Y') }}</td>
                <td>
                    <a href="{{ route('super-admin.students.show', $user) }}"
                       style="font-size:0.75rem;font-weight:600;color:rgba(99,143,255,0.8);text-decoration:none;transition:color 0.15s;"
                       onmouseover="this.style.color='#fff';" onmouseout="this.style.color='rgba(99,143,255,0.8)';">
                        View <i class="fas fa-chevron-right" style="font-size:0.6rem;"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;padding:48px;color:rgba(255,255,255,0.2);">No students found.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div style="padding:14px 20px;border-top:1px solid rgba(255,255,255,0.05);">
        {{ $students->links() }}
    </div>
</div>

@endsection
