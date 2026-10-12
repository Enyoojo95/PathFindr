@extends('layouts.dashboard')
@section('title', 'Students — PathFindr Admin')
@section('page-title', 'All Students')
@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-[#2D5F9E] text-sm hover:text-[#1A2A6C] flex items-center gap-1 mb-2">
                <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
            </a>
            <h1 class="text-2xl font-extrabold text-[#1A2A6C]">All Students</h1>
            <p class="text-gray-500 text-sm mt-0.5">
                @if(auth()->user()->isSuperAdmin()) All schools @else {{ auth()->user()->school_name }} @endif
            </p>
        </div>
        <a href="{{ route('admin.students.create') }}"
           class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#43A047] text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-md">
            <i class="fas fa-user-plus text-xs"></i> Add New Student
        </a>
    </div>

    @if (session('success')){{-- handled by toast --}}@endif

    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-[#EBF4FF] flex items-center justify-between">
            <h2 class="text-[#1A2A6C] font-bold text-sm">
                <i class="fas fa-users text-[#F5A623] mr-2"></i>
                {{ $students->total() }} student{{ $students->total() !== 1 ? 's' : '' }} found
            </h2>
        </div>

        @if ($students->isEmpty())
            <div class="py-16 text-center text-gray-400 text-sm">
                <i class="fas fa-user-slash text-4xl mb-3 block opacity-20"></i>
                No students yet.
                <a href="{{ route('admin.students.create') }}" class="text-[#1A2A6C] font-semibold ml-1 hover:underline">Add one now</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#EBF4FF]">
                            <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Student</th>
                            <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">School</th>
                            <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Test Status</th>
                            <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Score</th>
                            <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Joined</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EBF4FF]">
                        @foreach ($students as $user)
                        @php $result = $user->testResults->first(); @endphp
                        <tr class="hover:bg-[#EBF4FF]/40 transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                                         class="w-8 h-8 rounded-full object-cover border border-[#EBF4FF] shrink-0">
                                    <div>
                                        <div class="font-semibold text-[#1A2A6C] text-sm">{{ $user->name }}</div>
                                        <div class="text-[#2D3748]/70 text-xs">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-[#2D3748]/60 text-xs">{{ $user->school_name ?? '—' }}</td>
                            <td class="px-5 py-3.5">
                                @if ($result)
                                    <span class="inline-flex items-center gap-1 bg-[#43A047]/10 text-[#43A047] border border-[#43A047]/20 text-xs px-2.5 py-1 rounded-full font-medium">
                                        <i class="fas fa-check-circle text-[9px]"></i> Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-[#F5A623]/10 text-[#b07d0a] border border-[#F5A623]/20 text-xs px-2.5 py-1 rounded-full font-medium">
                                        <i class="fas fa-clock text-[9px]"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="font-bold text-[#1A2A6C] text-sm">{{ $result ? round($result->total_score, 1).'%' : '—' }}</div>
                                @if ($result?->recommended_combination)
                                    <div class="text-[#2D3748]/40 text-xs">{{ $result->recommended_combination }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-[#2D3748]/50 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    @if ($result)
                                    <button type="button"
                                            onclick="pfConfirm('Reset {{ addslashes($user->name) }}\'s exam? This deletes their result and allows a retake.', function(){ document.getElementById('reset-form-{{ $user->id }}').submit(); })"
                                            class="text-[#2D5F9E] hover:text-[#1A2A6C] text-xs font-medium transition-colors duration-150">
                                        <i class="fas fa-redo-alt"></i> Reset
                                    </button>
                                    <form id="reset-form-{{ $user->id }}" method="POST" action="{{ route('admin.students.reset-exam', $user) }}" class="hidden">
                                        @csrf
                                    </form>
                                    @endif
                                    <button type="button"
                                            onclick="pfConfirm('Remove {{ addslashes($user->name) }}? This cannot be undone.', function(){ document.getElementById('del-form-{{ $user->id }}').submit(); }, 'error')"
                                            class="text-red-400 hover:text-red-600 text-xs font-medium transition-colors duration-150">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <form id="del-form-{{ $user->id }}" method="POST" action="{{ route('admin.students.destroy', $user) }}" class="hidden">
                                        @csrf @method('DELETE')
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-[#EBF4FF]">
                {{ $students->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
