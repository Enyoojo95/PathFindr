@extends('layouts.dashboard')
@section('title', 'Add Student — PathFindr Admin')
@section('page-title', 'Add New Student')
@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('admin.students.index') }}"
           class="w-9 h-9 rounded-full bg-white border border-gray-200 flex items-center justify-center text-[#1A2A6C] hover:bg-[#1A2A6C] hover:text-white transition-all duration-200 shadow-sm">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-extrabold text-[#1A2A6C]">Add New Student</h1>
            <p class="text-gray-500 text-sm">Register a student directly from the admin panel</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 p-4 rounded-2xl border text-sm"
         style="background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#dc2626;">
        <div class="font-semibold mb-1 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Fix the following:</div>
        <ul class="list-disc list-inside space-y-0.5">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Email Address <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#2D3748] mb-1.5">
                    School Name <span class="text-red-400">*</span>
                    @if (!auth()->user()->isSuperAdmin())
                        <span class="text-gray-400 font-normal">(auto-filled from your account)</span>
                    @endif
                </label>
                <div class="relative">
                    <i class="fas fa-school absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="school_name"
                           value="{{ auth()->user()->isSuperAdmin() ? old('school_name') : auth()->user()->school_name }}"
                           {{ !auth()->user()->isSuperAdmin() ? 'readonly' : '' }}
                           required
                           class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all {{ !auth()->user()->isSuperAdmin() ? 'opacity-60 cursor-not-allowed' : '' }}">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Class</label>
                    <select name="class" class="w-full px-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                        <option value="JSS3" {{ old('class','JSS3')==='JSS3'?'selected':'' }}>JSS3</option>
                        <option value="SS1"  {{ old('class')==='SS1' ?'selected':'' }}>SS1</option>
                        <option value="SS2"  {{ old('class')==='SS2' ?'selected':'' }}>SS2</option>
                        <option value="SS3"  {{ old('class')==='SS3' ?'selected':'' }}>SS3</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Age</label>
                    <input type="number" name="age" value="{{ old('age') }}" min="8" max="20"
                           class="w-full px-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Gender</label>
                    <select name="gender" class="w-full px-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                        <option value="">— Select —</option>
                        <option value="male"   {{ old('gender')==='male'  ?'selected':'' }}>Male</option>
                        <option value="female" {{ old('gender')==='female'?'selected':'' }}>Female</option>
                        <option value="other"  {{ old('gender')==='other' ?'selected':'' }}>Other</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Phone (optional)</label>
                <div class="relative">
                    <i class="fas fa-phone absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                </div>
            </div>

            <div class="border-t border-gray-100 pt-5">
                <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Temporary Password <span class="text-red-400">*</span></label>
                <div class="relative">
                    <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="password" name="password" required id="pw-field"
                           placeholder="Min. 8 characters"
                           class="w-full pl-10 pr-10 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                    <button type="button" onclick="togglePw()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                        <i class="fas fa-eye" id="pw-icon"></i>
                    </button>
                </div>
                <p class="text-gray-400 text-xs mt-1.5"><i class="fas fa-info-circle mr-1"></i>Student will use this to log in. They can change it from their profile.</p>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="flex-1 flex items-center justify-center gap-2 bg-[#43A047] hover:bg-emerald-600 text-white font-semibold py-3 rounded-xl text-sm transition-all duration-200 shadow-md">
                    <i class="fas fa-user-plus text-xs"></i> Add Student
                </button>
                <a href="{{ route('admin.students.index') }}"
                   class="flex-1 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 font-semibold py-3 rounded-xl text-sm transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function togglePw() {
    const f = document.getElementById('pw-field');
    const i = document.getElementById('pw-icon');
    f.type = f.type === 'password' ? 'text' : 'password';
    i.className = f.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
@endsection
