@extends('layouts.super-admin')
@section('title', 'Activity Logs — PathFindr Master')
@section('page-title', 'Activity Logs')

@section('content')

<div class="glass overflow-hidden">
    <table class="w-full sa-table">
        <thead>
            <tr>
                <th class="text-left">User</th>
                <th class="text-left">Role</th>
                <th class="text-left">Action</th>
                <th class="text-left">Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($logs as $log)
            <tr>
                <td class="font-medium text-white">{{ $log['user'] }}</td>
                <td>
                    <span class="badge" style="background:rgba(37,99,235,0.15); color:#93C5FD;">{{ ucfirst($log['role']) }}</span>
                </td>
                <td style="color:#9CA3AF;">{{ $log['action'] }}</td>
                <td class="text-xs" style="color:#9CA3AF;">{{ $log['time'] }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="text-center py-12 text-white/30">No activity yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
