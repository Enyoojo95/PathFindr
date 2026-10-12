<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
    <form method="POST" action="{{ $action }}" class="space-y-5">
        @csrf
        @if ($method !== 'POST') @method($method) @endif

        {{-- Category --}}
        <div>
            <label class="block text-sm font-medium text-[#2D3748] mb-2">Category <span class="text-red-400">*</span></label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach (['numerical' => 'fa-calculator', 'verbal' => 'fa-font', 'spatial' => 'fa-shapes', 'interest' => 'fa-heart'] as $cat => $icon)
                <label class="cursor-pointer">
                    <input type="radio" name="category" value="{{ $cat }}" class="sr-only peer"
                           {{ old('category', $question?->category) === $cat ? 'checked' : '' }}>
                    <div class="flex items-center justify-center gap-1.5 border-2 border-gray-200 rounded-xl p-3 text-xs font-semibold text-gray-500 capitalize
                                peer-checked:border-[#1A2A6C] peer-checked:bg-[#1A2A6C] peer-checked:text-white transition-all duration-150">
                        <i class="fas {{ $icon }} text-xs"></i> {{ $cat }}
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Question Text --}}
        <div>
            <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Question Text <span class="text-red-400">*</span></label>
            <textarea name="question_text" rows="3" required
                      class="w-full px-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all resize-none">{{ old('question_text', $question?->question_text) }}</textarea>
        </div>

        {{-- Options --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach (['A', 'B', 'C', 'D'] as $opt)
            <div>
                <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Option {{ $opt }} <span class="text-red-400">*</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 rounded bg-[#1A2A6C] text-white text-xs font-bold flex items-center justify-center">{{ $opt }}</span>
                    <input type="text" name="option_{{ strtolower($opt) }}"
                           value="{{ old('option_'.strtolower($opt), $question?->{'option_'.strtolower($opt)}) }}" required
                           class="w-full pl-11 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                </div>
            </div>
            @endforeach
        </div>

        {{-- Correct Answer --}}
        <div>
            <label class="block text-sm font-medium text-[#2D3748] mb-2">
                Correct Answer
                <span class="text-gray-400 font-normal">(leave blank for interest questions)</span>
            </label>
            <div class="flex gap-3">
                @foreach (['A','B','C','D'] as $opt)
                <label class="cursor-pointer">
                    <input type="radio" name="correct_answer" value="{{ $opt }}" class="sr-only peer"
                           {{ old('correct_answer', $question?->correct_answer) === $opt ? 'checked' : '' }}>
                    <div class="w-10 h-10 rounded-xl border-2 border-gray-200 flex items-center justify-center text-sm font-bold text-gray-400
                                peer-checked:border-[#43A047] peer-checked:bg-[#43A047] peer-checked:text-white transition-all duration-150">
                        {{ $opt }}
                    </div>
                </label>
                @endforeach
                <label class="cursor-pointer">
                    <input type="radio" name="correct_answer" value="" class="sr-only peer"
                           {{ old('correct_answer', $question?->correct_answer) === null ? 'checked' : '' }}>
                    <div class="px-3 h-10 rounded-xl border-2 border-gray-200 flex items-center justify-center text-xs font-semibold text-gray-400
                                peer-checked:border-gray-400 peer-checked:bg-gray-100 transition-all duration-150">
                        None
                    </div>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-2 border-t border-gray-100">
            <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 bg-[#43A047] hover:bg-emerald-600 text-white font-semibold py-3 rounded-xl text-sm transition-all duration-200 shadow-md">
                <i class="fas fa-save text-xs"></i>
                {{ $method === 'POST' ? 'Add Question' : 'Save Changes' }}
            </button>
            <a href="{{ route('admin.questions.index') }}"
               class="flex-1 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-3 rounded-xl text-sm transition-all duration-200">
                Cancel
            </a>
        </div>
    </form>
</div>
