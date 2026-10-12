@extends('layouts.super-admin')
@section('title', 'Schools — PathFindr Master')
@section('page-title', 'Manage Schools')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex gap-2 flex-1 max-w-sm">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search schools..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl text-white outline-none"
                   style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);">
        </div>
        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white"
                style="background:#2563EB;">Search</button>
    </form>
</div>

<div class="glass overflow-hidden">
    <table class="w-full sa-table">
        <thead>
            <tr>
                <th class="text-left">School Name</th>
                <th class="text-left">Students</th>
                <th class="text-left">First Registered</th>
                <th class="text-left">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schools as $s)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-bold shrink-0"
                             style="background:rgba(37,99,235,0.2); color:#60A5FA;">
                            {{ strtoupper(substr($s->school_name, 0, 1)) }}
                        </div>
                        <span class="font-semibold text-white">{{ $s->school_name }}</span>
                    </div>
                </td>
                <td>
                    <span class="font-bold text-white">{{ $s->student_count }}</span>
                    <span class="text-xs ml-1" style="color:#9CA3AF;">student{{ $s->student_count !== 1 ? 's' : '' }}</span>
                </td>
                <td class="text-xs" style="color:#9CA3AF;">
                    {{ \Carbon\Carbon::parse($s->first_seen)->format('d M Y') }}
                </td>
                <td>
                    <span class="badge" style="background:rgba(16,185,129,0.15); color:#34D399;">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span> Active
                    </span>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-12 text-white/30">No schools found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-4 border-t" style="border-color:rgba(255,255,255,0.07);">
        {{ $schools->links() }}
    </div>
</div>

@endsection
