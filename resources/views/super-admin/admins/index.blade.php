@extends('layouts.super-admin')
@section('title', 'Admins — PathFindr Master')
@section('page-title', 'Manage Admins')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex gap-2 flex-1 max-w-sm">
        <div class="relative flex-1">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search admins..."
                   class="w-full pl-9 pr-4 py-2.5 text-sm rounded-xl text-white outline-none"
                   style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);">
        </div>
        <button type="submit" class="px-4 py-2.5 rounded-xl text-sm font-semibold text-white" style="background:#2563EB;">Go</button>
    </form>
    <button onclick="document.getElementById('add-admin-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white whitespace-nowrap"
            style="background:linear-gradient(135deg,#2563EB,#7C3AED);">
        <i class="fas fa-user-plus text-xs"></i> Add Admin
    </button>
</div>

<div class="glass overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full sa-table min-w-[600px]">
        <thead>
            <tr>
                <th class="text-left">Admin</th>
                <th class="text-left">School</th>
                <th class="text-left">Role</th>
                <th class="text-left">Status</th>
                <th class="text-left">Joined</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($admins as $admin)
            <tr>
                <td>
                    <div class="flex items-center gap-3">
                        <img src="{{ $admin->avatarUrl() }}" alt="{{ $admin->name }}"
                             class="w-8 h-8 rounded-full object-cover shrink-0"
                             style="border:1px solid rgba(255,255,255,0.1);">
                        <div>
                            <div class="font-semibold text-white text-sm">{{ $admin->name }}</div>
                            <div class="text-xs" style="color:#9CA3AF;">{{ $admin->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="text-xs" style="color:#9CA3AF;">{{ $admin->school_name ?? '—' }}</td>
                <td>
                    @if ($admin->isSuperAdmin())
                        <span class="badge" style="background:rgba(124,58,237,0.15); color:#C4B5FD;">Super Admin</span>
                    @else
                        <span class="badge" style="background:rgba(37,99,235,0.15); color:#93C5FD;">School Admin</span>
                    @endif
                </td>
                <td>
                    <span class="badge" style="background:{{ $admin->is_suspended ? 'rgba(239,68,68,0.15)' : 'rgba(16,185,129,0.15)' }}; color:{{ $admin->is_suspended ? '#FCA5A5' : '#6EE7B7' }};">
                        {{ $admin->is_suspended ? 'Suspended' : 'Active' }}
                    </span>
                </td>
                <td class="text-xs" style="color:#9CA3AF;">{{ $admin->created_at->format('d M Y') }}</td>
                <td>
                    <div class="flex items-center justify-end gap-2">
                        @if ($admin->isAdmin())
                        <form method="POST" action="{{ route('super-admin.admins.suspend', $admin) }}">
                            @csrf
                            <button type="submit" class="text-xs font-medium" style="color:{{ $admin->is_suspended ? '#6EE7B7' : '#FBBF24' }};">
                                <i class="fas {{ $admin->is_suspended ? 'fa-user-check' : 'fa-user-slash' }} text-[10px]"></i>
                                {{ $admin->is_suspended ? 'Restore' : 'Suspend' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('super-admin.admins.destroy', $admin) }}"
                              onsubmit="return confirm('Delete {{ addslashes($admin->name) }}?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-medium transition-colors hover:text-red-300" style="color:rgba(239,68,68,0.6);">
                                <i class="fas fa-trash text-[10px]"></i> Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-white/30">No admins found.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-4 border-t" style="border-color:rgba(255,255,255,0.07);">
        {{ $admins->links() }}
    </div>
</div>

{{-- Add Admin Modal --}}
<div id="add-admin-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4"
     style="background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);">
    <div class="glass-dark w-full max-w-md p-7" style="border-radius:20px;">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-bold text-lg">Add New Admin</h3>
            <button onclick="document.getElementById('add-admin-modal').classList.add('hidden')"
                    class="text-white/40 hover:text-white text-lg"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('super-admin.admins.store') }}" class="space-y-4">
            @csrf
            @foreach ([
                ['name'=>'name',       'label'=>'Full Name',    'type'=>'text',     'icon'=>'fa-user',     'ph'=>'Admin full name'],
                ['name'=>'email',      'label'=>'Email',        'type'=>'email',    'icon'=>'fa-envelope', 'ph'=>'admin@school.ng'],
                ['name'=>'school_name','label'=>'School Name',  'type'=>'text',     'icon'=>'fa-building', 'ph'=>'Assigned school'],
                ['name'=>'password',   'label'=>'Password',     'type'=>'password', 'icon'=>'fa-lock',     'ph'=>'Min. 8 characters'],
            ] as $f)
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">{{ $f['label'] }}</label>
                <div class="relative">
                    <i class="fas {{ $f['icon'] }} absolute left-3 top-1/2 -translate-y-1/2 text-white/30 text-xs"></i>
                    <input type="{{ $f['type'] }}" name="{{ $f['name'] }}" placeholder="{{ $f['ph'] }}"
                           class="sa-input" style="padding-left:38px;">
                </div>
            </div>
            @endforeach
            <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-sm text-white mt-1"
                    style="background:linear-gradient(135deg,#2563EB,#7C3AED);">
                <i class="fas fa-user-plus mr-2"></i> Create Admin
            </button>
        </form>
    </div>
</div>

@endsection
