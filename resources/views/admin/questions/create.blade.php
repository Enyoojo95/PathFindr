@extends('layouts.dashboard')
@section('title', 'Add Question — PathFindr Admin')
@section('page-title', 'Add Question')
@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('admin.questions.index') }}"
           class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#1A2A6C] hover:bg-[#1A2A6C] hover:text-white transition-all duration-200 shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-[#1A2A6C]">Add Question</h1>
            <p class="text-gray-500 text-sm">Add a new question to the psychometric test bank</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-2xl border text-sm"
         style="background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#dc2626;">
        <div class="font-semibold mb-1 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Fix the following:</div>
        <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    @include('admin.questions._form', ['question' => null, 'action' => route('admin.questions.store'), 'method' => 'POST'])
</div>
@endsection
