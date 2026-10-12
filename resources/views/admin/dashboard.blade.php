@extends('layouts.dashboard')
@section('title', 'Admin Dashboard — PathFindr')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="p-4 sm:p-6 lg:p-8">

        {{-- Welcome Banner --}}
        <div class="bg-[#1A2A6C] rounded-2xl p-8 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#2D5F9E] opacity-20 rounded-full -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-[#43A047] opacity-10 rounded-full translate-y-1/2 -translate-x-1/4 pointer-events-none"></div>
            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <p class="text-white/70 text-xs font-medium mb-1">
                        <i class="fas fa-calendar-alt mr-2"></i>{{ now()->format('l, d F Y') }}
                    </p>
                    <h1 class="text-white text-3xl font-extrabold">
                        Admin Dashboard — <span class="text-[#F5A623]">PathFindr</span>
                    </h1>
                    <p class="text-white/85 text-sm mt-1.5">
                        Welcome back, <strong>{{ auth()->user()->name }}</strong>.
                        @if ($isSuperAdmin)
                            Viewing all schools — full platform overview.
                        @else
                            Viewing students from <strong class="text-[#F5A623]">{{ $schoolName }}</strong>.
                        @endif
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <img src="{{ auth()->user()->avatarUrl() }}"
                         alt="{{ auth()->user()->name }}"
                         class="w-12 h-12 rounded-full object-cover border-2 border-[#F5A623]/40">
                    <div>
                        <div class="text-white font-semibold text-sm">{{ auth()->user()->name }}</div>
                        @if ($isSuperAdmin)
                            <div class="inline-flex items-center gap-1.5 bg-purple-500/20 border border-purple-400/30 rounded-full px-3 py-0.5 mt-1">
                                <i class="fas fa-crown text-purple-300 text-xs"></i>
                                <span class="text-purple-300 text-xs font-semibold">Super Admin</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-1.5 bg-[#F5A623]/20 border border-[#F5A623]/30 rounded-full px-3 py-0.5 mt-1">
                                <i class="fas fa-user-shield text-[#F5A623] text-xs"></i>
                                <span class="text-[#F5A623] text-xs font-semibold">School Admin</span>
                            </div>
                            @if ($schoolName)
                        <div class="text-white/60 text-xs mt-0.5">
                                <i class="fas fa-school mr-1"></i>{{ $schoolName }}
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">

            {{-- Total Students --}}
            <div class="bg-white rounded-2xl p-6 border border-[#EBF4FF] shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-[#2D3748] text-sm font-medium">Total Students</div>
                        <div class="text-[#1A2A6C] text-4xl font-extrabold mt-1">{{ $totalStudents }}</div>
                    </div>
                    <div class="w-12 h-12 bg-[#1A2A6C] rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-[#F5A623] text-xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-[#43A047] text-xs font-semibold">
                    @if ($newThisWeek > 0)
                        <i class="fas fa-arrow-up"></i> +{{ $newThisWeek }} registered this week
                    @else
                        <i class="fas fa-minus text-gray-400"></i>
                        <span class="text-gray-400">No new registrations this week</span>
                    @endif
                </div>
            </div>

            {{-- Tests Completed --}}
            <div class="bg-white rounded-2xl p-6 border border-[#EBF4FF] shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-[#2D3748] text-sm font-medium">Tests Completed</div>
                        <div class="text-[#1A2A6C] text-4xl font-extrabold mt-1">{{ $testsCompleted }}</div>
                    </div>
                    <div class="w-12 h-12 bg-[#43A047]/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-[#43A047] text-xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-[#43A047] text-xs font-semibold">
                    @if ($testsToday > 0)
                        <i class="fas fa-arrow-up"></i> +{{ $testsToday }} completed today
                    @else
                        <i class="fas fa-minus text-gray-400"></i>
                        <span class="text-gray-400">No tests today yet</span>
                    @endif
                </div>
            </div>

            {{-- Average Score --}}
            <div class="bg-white rounded-2xl p-6 border border-[#EBF4FF] shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-[#2D3748] text-sm font-medium">Average Score</div>
                        <div class="text-[#1A2A6C] text-4xl font-extrabold mt-1">
                            @if ($avgScore !== null)
                                {{ $avgScore }}<span class="text-xl text-[#2D3748]/70">%</span>
                            @else
                                <span class="text-2xl text-gray-500">No data</span>
                            @endif
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-[#2D5F9E]/10 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-[#2D5F9E] text-xl"></i>
                    </div>
                </div>
                <div class="flex items-center gap-1 text-[#2D5F9E] text-xs font-semibold">
                    <i class="fas fa-percent text-[10px]"></i>
                    {{ $completionRate }}% completion rate
                    <span class="text-[#2D3748]/60 font-normal ml-1">({{ $testsCompleted }}/{{ $totalStudents }})</span>
                </div>
            </div>
        </div>

        {{-- Combination Breakdown --}}
        @if (!empty($combinations))
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            @php
                $comboColors = [
                    'Science'    => ['bg' => 'bg-[#43A047]/10', 'text' => 'text-[#43A047]', 'border' => 'border-[#43A047]/20', 'icon' => 'fa-flask'],
                    'Commercial' => ['bg' => 'bg-[#F5A623]/10', 'text' => 'text-[#92400e]', 'border' => 'border-[#F5A623]/30', 'icon' => 'fa-chart-line'],
                    'Arts'       => ['bg' => 'bg-[#2D5F9E]/10', 'text' => 'text-[#2D5F9E]', 'border' => 'border-[#2D5F9E]/20', 'icon' => 'fa-pen-nib'],
                ];
            @endphp
            @foreach (['Science', 'Commercial', 'Arts'] as $combo)
            @php
                $count = $combinations[$combo] ?? 0;
                $c = $comboColors[$combo];
                $pct = $testsCompleted > 0 ? round(($count / $testsCompleted) * 100) : 0;
            @endphp
            <div class="bg-white rounded-xl p-5 border {{ $c['border'] }} shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 {{ $c['bg'] }} rounded-lg flex items-center justify-center">
                        <i class="fas {{ $c['icon'] }} {{ $c['text'] }} text-sm"></i>
                    </div>
                    <div>
                        <div class="{{ $c['text'] }} font-bold text-sm">{{ $combo }}</div>
                        <div class="text-[#2D3748]/70 text-xs">{{ $count }} student{{ $count !== 1 ? 's' : '' }}</div>
                    </div>
                    <div class="{{ $c['text'] }} font-extrabold text-lg ml-auto">{{ $pct }}%</div>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700"
                         style="width: {{ $pct }}%; background: {{ $combo === 'Science' ? '#43A047' : ($combo === 'Commercial' ? '#F5A623' : '#2D5F9E') }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Bottom Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Recent Students Table --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-[#EBF4FF] shadow-md overflow-hidden">
                <div class="flex justify-between items-center p-6 border-b border-[#EBF4FF]">
                    <h2 class="text-[#1A2A6C] text-base font-bold flex items-center gap-2">
                        <i class="fas fa-users text-[#F5A623]"></i> Recent Students
                        <span class="text-xs text-[#2D3748]/60 font-normal ml-1">(latest {{ $recentStudents->count() }})</span>
                    </h2>
                </div>

                @if ($recentStudents->isEmpty())
                    <div class="px-6 py-12 text-center text-[#2D3748]/40 text-sm">
                        <i class="fas fa-users text-4xl mb-3 block opacity-20"></i>
                        No students registered yet.
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
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#EBF4FF]">
                            @foreach ($recentStudents as $student)
                            <tr class="hover:bg-[#EBF4FF]/50 transition-colors duration-150">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $student['avatar'] }}"
                                             alt="{{ $student['name'] }}"
                                             class="w-8 h-8 rounded-full object-cover border border-[#EBF4FF] shrink-0">
                                        <div>
                                            <div class="font-semibold text-[#1A2A6C] text-sm leading-tight">{{ $student['name'] }}</div>
                                            <div class="text-[#2D3748]/70 text-xs">{{ $student['email'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-[#2D3748]/75 text-xs">
                                    {{ $student['school'] }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($student['status'] === 'Completed')
                                        <span class="inline-flex items-center gap-1 bg-[#43A047]/10 text-[#43A047] border border-[#43A047]/20 text-xs px-2.5 py-1 rounded-full font-medium">
                                            <i class="fas fa-check-circle text-[9px]"></i> Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 bg-[#F5A623]/10 text-[#92400e] border border-[#F5A623]/30 text-xs px-2.5 py-1 rounded-full font-medium">
                                            <i class="fas fa-clock text-[9px]"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-[#1A2A6C] text-sm">{{ $student['score'] }}</div>
                                    @if ($student['combo'])
                                        <div class="text-[#2D3748]/65 text-xs">{{ $student['combo'] }}</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md p-6">
                    <h3 class="text-[#1A2A6C] font-bold text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-bolt text-[#F5A623]"></i> Quick Actions
                    </h3>
                    <div class="space-y-2">
                        <a href="{{ route('admin.students.create') }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-[#EBF4FF] hover:bg-[#1A2A6C] hover:text-white group transition-all duration-200">
                            <i class="fas fa-user-plus text-[#1A2A6C] group-hover:text-[#F5A623] text-sm w-4"></i>
                            <span class="text-sm font-medium text-[#2D3748] group-hover:text-white">Add New Student</span>
                        </a>
                        <a href="{{ route('admin.students.index') }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-[#EBF4FF] hover:bg-[#1A2A6C] hover:text-white group transition-all duration-200">
                            <i class="fas fa-users text-[#1A2A6C] group-hover:text-[#F5A623] text-sm w-4"></i>
                            <span class="text-sm font-medium text-[#2D3748] group-hover:text-white">View All Students</span>
                        </a>
                        <a href="{{ route('admin.questions.index') }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-[#EBF4FF] hover:bg-[#1A2A6C] hover:text-white group transition-all duration-200">
                            <i class="fas fa-question-circle text-[#1A2A6C] group-hover:text-[#F5A623] text-sm w-4"></i>
                            <span class="text-sm font-medium text-[#2D3748] group-hover:text-white">Manage Questions</span>
                        </a>
                        <a href="{{ route('admin.export.index') }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-[#EBF4FF] hover:bg-[#1A2A6C] hover:text-white group transition-all duration-200">
                            <i class="fas fa-file-export text-[#1A2A6C] group-hover:text-[#F5A623] text-sm w-4"></i>
                            <span class="text-sm font-medium text-[#2D3748] group-hover:text-white">Export Results</span>
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 p-3 rounded-xl bg-[#EBF4FF] hover:bg-[#1A2A6C] hover:text-white group transition-all duration-200">
                            <i class="fas fa-cog text-[#1A2A6C] group-hover:text-[#F5A623] text-sm w-4"></i>
                            <span class="text-sm font-medium text-[#2D3748] group-hover:text-white">Profile Settings</span>
                        </a>
                    </div>
                </div>

                {{-- Platform Summary --}}
                <div class="bg-[#1A2A6C] rounded-2xl p-5">
                    <h3 class="text-white font-bold text-sm mb-4 flex items-center gap-2">
                        <i class="fas fa-chart-pie text-[#43A047]"></i> Platform Summary
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-white/70 text-xs">Registered Students</span>
                            <span class="text-white font-bold text-sm">{{ $totalStudents }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70 text-xs">Tests Completed</span>
                            <span class="text-[#43A047] font-bold text-sm">{{ $testsCompleted }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70 text-xs">Yet to Test</span>
                            <span class="text-[#F5A623] font-bold text-sm">{{ $totalStudents - $testsCompleted }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/70 text-xs">Completion Rate</span>
                            <span class="text-white font-bold text-sm">{{ $completionRate }}%</span>
                        </div>
                        <div class="h-1.5 bg-white/10 rounded-full mt-1 overflow-hidden">
                            <div class="h-full bg-[#43A047] rounded-full transition-all duration-700"
                                 style="width: {{ $completionRate }}%"></div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-5">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium py-2.5 rounded-xl transition-all duration-200">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
</div>
@endsection
