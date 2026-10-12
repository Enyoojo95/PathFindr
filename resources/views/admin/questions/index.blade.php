@extends('layouts.dashboard')
@section('title', 'Manage Questions — PathFindr Admin')
@section('page-title', 'Manage Questions')
@section('content')
<div class="p-4 sm:p-6 lg:p-8">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-[#2D5F9E] text-sm hover:text-[#1A2A6C] flex items-center gap-1 mb-2">
                <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
            </a>
            <h1 class="text-2xl font-extrabold text-[#1A2A6C]">Manage Questions</h1>
            <p class="text-gray-500 text-sm mt-0.5">{{ $questions->total() }} question{{ $questions->total() !== 1 ? 's' : '' }} in the bank</p>
        </div>
        <a href="{{ route('admin.questions.create') }}"
           class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#43A047] text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-md">
            <i class="fas fa-plus text-xs"></i> Add Question
        </a>
    </div>

    @if (session('success')){{-- handled by toast --}}@endif

    @php
        $catColors = [
            'numerical' => 'bg-[#1A2A6C]/10 text-[#1A2A6C]',
            'verbal'    => 'bg-[#43A047]/10 text-[#43A047]',
            'spatial'   => 'bg-[#2D5F9E]/10 text-[#2D5F9E]',
            'interest'  => 'bg-[#F5A623]/10 text-[#b07d0a]',
        ];
    @endphp

    <div class="bg-white rounded-2xl border border-[#EBF4FF] shadow-md overflow-hidden">
        @if ($questions->isEmpty())
            <div class="py-16 text-center text-gray-400 text-sm">
                <i class="fas fa-question-circle text-4xl mb-3 block opacity-20"></i>
                No questions yet.
                <a href="{{ route('admin.questions.create') }}" class="text-[#1A2A6C] font-semibold ml-1 hover:underline">Add the first one</a>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-[#EBF4FF]">
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider w-12">#</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Question</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Category</th>
                        <th class="text-left px-5 py-3 text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Answer</th>
                        <th class="px-5 py-3 text-right text-[#2D3748]/50 font-semibold text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EBF4FF]">
                    @foreach ($questions as $q)
                    <tr class="hover:bg-[#EBF4FF]/40 transition-colors duration-150">
                        <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $q->id }}</td>
                        <td class="px-5 py-3.5 max-w-sm">
                            <p class="text-[#1A2A6C] font-medium text-sm leading-snug line-clamp-2">{{ $q->question_text }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">A: {{ $q->option_a }} · B: {{ $q->option_b }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold capitalize {{ $catColors[$q->category] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ $q->category }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            @if ($q->correct_answer)
                                <span class="font-bold text-[#43A047] text-sm">{{ $q->correct_answer }}</span>
                            @else
                                <span class="text-gray-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.questions.edit', $q) }}"
                                   class="inline-flex items-center gap-1 text-[#2D5F9E] hover:text-[#1A2A6C] text-xs font-medium transition-colors">
                                    <i class="fas fa-edit text-[10px]"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.questions.destroy', $q) }}"
                                      id="qdel-{{ $q->id }}">
                                    @csrf @method('DELETE')
                                </form>
                                <button type="button"
                                        onclick="pfConfirm('Delete this question?', function(){ document.getElementById('qdel-{{ $q->id }}').submit(); }, 'error')"
                                        class="inline-flex items-center gap-1 text-red-400 hover:text-red-600 text-xs font-medium transition-colors">
                                    <i class="fas fa-trash text-[10px]"></i> Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-[#EBF4FF]">{{ $questions->links() }}</div>
        @endif
    </div>
</div>
@endsection
