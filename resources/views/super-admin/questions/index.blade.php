@extends('layouts.super-admin')
@section('title', 'Questions — PathFindr Master')
@section('page-title', 'Manage Questions')

@section('content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <form method="GET" class="flex gap-2">
        <select name="category" class="px-3 py-2.5 rounded-xl text-sm text-white outline-none"
                style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.12);"
                onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach (['numerical','verbal','spatial','interest'] as $cat)
            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
            @endforeach
        </select>
    </form>
    <button onclick="document.getElementById('add-q-modal').classList.remove('hidden')"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white whitespace-nowrap"
            style="background:linear-gradient(135deg,#2563EB,#7C3AED);">
        <i class="fas fa-plus text-xs"></i> Add Question
    </button>
</div>

<div class="glass overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full sa-table min-w-[650px]">
        <thead>
            <tr>
                <th class="text-left w-10">#</th>
                <th class="text-left">Question</th>
                <th class="text-left">Category</th>
                <th class="text-left">Answer</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($questions as $q)
            @php
                $catColor = match($q->category) {
                    'numerical' => 'rgba(37,99,235,0.15); color:#93C5FD',
                    'verbal'    => 'rgba(16,185,129,0.15); color:#6EE7B7',
                    'spatial'   => 'rgba(124,58,237,0.15); color:#C4B5FD',
                    default     => 'rgba(245,158,11,0.15); color:#FCD34D',
                };
            @endphp
            <tr>
                <td class="text-white/30 text-xs">{{ $q->id }}</td>
                <td class="max-w-xs">
                    <p class="font-medium text-white text-sm leading-snug line-clamp-2">{{ $q->question_text }}</p>
                    <p class="text-xs mt-0.5" style="color:#9CA3AF;">A: {{ $q->option_a }} · B: {{ $q->option_b }}</p>
                </td>
                <td><span class="badge" style="background:{{ $catColor }};">{{ ucfirst($q->category) }}</span></td>
                <td class="font-bold text-emerald-400 text-sm">{{ $q->correct_answer ?? '—' }}</td>
                <td>
                    <div class="flex items-center justify-end gap-3">
                        <button onclick="openEditModal({{ $q->id }}, '{{ addslashes($q->category) }}', {{ json_encode($q->question_text) }}, '{{ addslashes($q->option_a) }}', '{{ addslashes($q->option_b) }}', '{{ addslashes($q->option_c) }}', '{{ addslashes($q->option_d) }}', '{{ $q->correct_answer }}')"
                                class="text-xs font-medium transition-colors hover:text-white" style="color:#60A5FA;">
                            <i class="fas fa-edit text-[10px]"></i> Edit
                        </button>
                        <form method="POST" action="{{ route('super-admin.questions.destroy', $q) }}"
                              onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-xs font-medium transition-colors hover:text-red-300" style="color:rgba(239,68,68,0.6);">
                                <i class="fas fa-trash text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-12 text-white/30">No questions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    <div class="px-5 py-4 border-t" style="border-color:rgba(255,255,255,0.07);">
        {{ $questions->links() }}
    </div>
</div>

{{-- Add Modal --}}
<div id="add-q-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 overflow-y-auto"
     style="background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);">
    <div class="glass-dark w-full max-w-lg p-7 my-8" style="border-radius:20px;">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-bold">Add Question</h3>
            <button onclick="document.getElementById('add-q-modal').classList.add('hidden')" class="text-white/40 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('super-admin.questions.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Category</label>
                <select name="category" class="sa-input">
                    @foreach (['numerical','verbal','spatial','interest'] as $c)
                    <option value="{{ $c }}">{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Question</label>
                <textarea name="question_text" rows="3" class="sa-input resize-none" placeholder="Enter question text..."></textarea>
            </div>
            @foreach (['a','b','c','d'] as $opt)
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Option {{ strtoupper($opt) }}</label>
                <input type="text" name="option_{{ $opt }}" class="sa-input" placeholder="Option {{ strtoupper($opt) }}">
            </div>
            @endforeach
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Correct Answer (optional)</label>
                <select name="correct_answer" class="sa-input">
                    <option value="">None</option>
                    @foreach (['A','B','C','D'] as $o)<option value="{{ $o }}">{{ $o }}</option>@endforeach
                </select>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm text-white"
                    style="background:linear-gradient(135deg,#2563EB,#7C3AED);">
                <i class="fas fa-save mr-2"></i> Save Question
            </button>
        </form>
    </div>
</div>

{{-- Edit Modal --}}
<div id="edit-q-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center px-4 overflow-y-auto"
     style="background:rgba(0,0,0,0.6); backdrop-filter:blur(4px);">
    <div class="glass-dark w-full max-w-lg p-7 my-8" style="border-radius:20px;">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-bold">Edit Question</h3>
            <button onclick="document.getElementById('edit-q-modal').classList.add('hidden')" class="text-white/40 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form id="edit-q-form" method="POST" action="" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Category</label>
                <select id="eq-category" name="category" class="sa-input">
                    @foreach (['numerical','verbal','spatial','interest'] as $c)
                    <option value="{{ $c }}">{{ ucfirst($c) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Question</label>
                <textarea id="eq-text" name="question_text" rows="3" class="sa-input resize-none"></textarea>
            </div>
            @foreach (['a','b','c','d'] as $opt)
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Option {{ strtoupper($opt) }}</label>
                <input type="text" id="eq-opt-{{ $opt }}" name="option_{{ $opt }}" class="sa-input">
            </div>
            @endforeach
            <div>
                <label class="block text-xs font-semibold text-white/50 uppercase tracking-widest mb-1.5">Correct Answer</label>
                <select id="eq-answer" name="correct_answer" class="sa-input">
                    <option value="">None</option>
                    @foreach (['A','B','C','D'] as $o)<option value="{{ $o }}">{{ $o }}</option>@endforeach
                </select>
            </div>
            <button type="submit" class="w-full py-3 rounded-xl font-bold text-sm text-white"
                    style="background:linear-gradient(135deg,#10B981,#2563EB);">
                <i class="fas fa-save mr-2"></i> Update Question
            </button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openEditModal(id, cat, text, a, b, c, d, ans) {
    document.getElementById('edit-q-form').action = '/super-admin/questions/' + id;
    document.getElementById('eq-category').value = cat;
    document.getElementById('eq-text').value = text;
    document.getElementById('eq-opt-a').value = a;
    document.getElementById('eq-opt-b').value = b;
    document.getElementById('eq-opt-c').value = c;
    document.getElementById('eq-opt-d').value = d;
    document.getElementById('eq-answer').value = ans || '';
    document.getElementById('edit-q-modal').classList.remove('hidden');
}
</script>
@endsection
