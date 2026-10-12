@extends('layouts.dashboard')
@section('title', 'Edit Profile — PathFindr')
@section('page-title', 'Edit Profile')
@section('content')
<div class="p-4 sm:p-6 lg:p-8 max-w-3xl mx-auto">

    @if (session('status') === 'profile-updated'){{-- handled by toast --}}@endif

    {{-- Avatar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7 mb-6">
        <h2 class="text-[#1A2A6C] font-bold text-base mb-5 flex items-center gap-2">
            <i class="fas fa-camera text-[#43A047]"></i> Profile Picture
        </h2>
        <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <div class="relative shrink-0">
                    <img id="avatar-preview" src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-full object-cover border-4 border-[#EBF4FF] shadow-md">
                    <label for="avatar-input"
                           class="absolute -bottom-1 -right-1 w-8 h-8 bg-[#1A2A6C] hover:bg-[#43A047] rounded-full flex items-center justify-center cursor-pointer transition-colors duration-200 shadow-md">
                        <i class="fas fa-camera text-white text-xs"></i>
                    </label>
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <p class="text-[#1A2A6C] font-semibold">{{ $user->name }}</p>
                    <p class="text-gray-400 text-sm mb-3">
                        {{ $user->isSuperAdmin() ? 'Super Admin' : ($user->isAdmin() ? 'Administrator' : 'Student') }}
                    </p>
                    <label for="avatar-input"
                           class="inline-flex items-center gap-2 bg-[#EBF4FF] hover:bg-[#1A2A6C] hover:text-white text-[#1A2A6C] border border-[#1A2A6C]/20 text-sm font-medium px-4 py-2 rounded-lg cursor-pointer transition-all duration-200">
                        <i class="fas fa-upload text-xs"></i> Choose Photo
                    </label>
                    <p class="text-gray-400 text-xs mt-2">JPG, PNG or WebP. Max 2MB.</p>
                </div>
            </div>
            <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden">
            <div id="avatar-actions" class="hidden mt-5 pt-5 border-t border-gray-100 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#43A047] hover:bg-emerald-600 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition-all duration-200 shadow-sm">
                    <i class="fas fa-save"></i> Save Photo
                </button>
                <button type="button" onclick="cancelAvatarPreview()"
                        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-5 py-2.5 rounded-xl text-sm transition-all duration-200">
                    Cancel
                </button>
                <span id="avatar-filename" class="text-gray-400 text-xs truncate max-w-[160px]"></span>
            </div>
            @error('avatar')
                <p class="mt-3 text-red-500 text-sm flex items-center gap-1.5">
                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                </p>
            @enderror
        </form>
    </div>

    {{-- Account Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7 mb-6">
        <h2 class="text-[#1A2A6C] font-bold text-base mb-5 flex items-center gap-2">
            <i class="fas fa-user-edit text-[#43A047]"></i> Account Information
        </h2>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf @method('patch')
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-2xl border text-sm"
                     style="background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#dc2626;">
                    <div class="font-semibold mb-1 flex items-center gap-2"><i class="fas fa-exclamation-circle"></i> Fix:</div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Full Name <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-user absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Email <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <i class="fas fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Account Role</label>
                    <div class="flex items-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                        <i class="fas {{ $user->isAdmin() || $user->isSuperAdmin() ? 'fa-user-shield text-[#1A2A6C]' : 'fa-user-graduate text-[#43A047]' }} text-sm"></i>
                        <span class="text-sm font-medium text-[#2D3748]">
                            {{ $user->isSuperAdmin() ? 'Super Admin' : ($user->isAdmin() ? 'Administrator' : 'Student') }}
                        </span>
                        <span class="ml-auto text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">Cannot be changed</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-5 border-t border-gray-100 flex items-center gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#2D5F9E] text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all duration-200 shadow-sm">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium px-6 py-3 rounded-xl text-sm transition-all duration-200">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7 mb-6">
        <h2 class="text-[#1A2A6C] font-bold text-base mb-5 flex items-center gap-2">
            <i class="fas fa-lock text-[#43A047]"></i> Change Password
        </h2>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf @method('put')
            @if ($errors->updatePassword->any())
                <div class="mb-5 p-4 rounded-2xl border text-sm"
                     style="background:rgba(239,68,68,0.08);border-color:rgba(239,68,68,0.25);color:#dc2626;">
                    <ul class="list-disc list-inside">@foreach ($errors->updatePassword->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @if (session('status') === 'password-updated'){{-- handled by toast --}}@endif
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Current Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" id="pw_current" name="current_password"
                               class="w-full pl-10 pr-10 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                        <button type="button" onclick="togglePw('pw_current',this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">New Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" id="pw_new" name="password"
                               class="w-full pl-10 pr-10 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                        <button type="button" onclick="togglePw('pw_new',this)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-sm">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#2D3748] mb-1.5">Confirm New Password</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" name="password_confirmation"
                               class="w-full pl-10 pr-4 py-3 bg-[#EBF4FF]/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#1A2A6C] transition-all">
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#1A2A6C] hover:bg-[#2D5F9E] text-white font-semibold px-6 py-3 rounded-xl text-sm transition-all duration-200 shadow-sm">
                    <i class="fas fa-key"></i> Update Password
                </button>
            </div>
        </form>
    </div>

    {{-- Danger Zone --}}
    <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-7">
        <h2 class="text-red-600 font-bold text-base mb-2 flex items-center gap-2">
            <i class="fas fa-exclamation-triangle"></i> Danger Zone
        </h2>
        <p class="text-gray-500 text-sm mb-5">Once deleted, all your data is permanently removed. This cannot be undone.</p>
        <button onclick="document.getElementById('delete-modal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-600 hover:text-white text-red-600 border border-red-200 font-medium px-5 py-2.5 rounded-xl text-sm transition-all duration-200">
            <i class="fas fa-trash-alt"></i> Delete Account
        </button>
    </div>

</div>

{{-- Delete Modal --}}
<div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-7">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-500"></i>
            </div>
            <h3 class="text-[#1A2A6C] font-bold text-lg">Delete Account</h3>
        </div>
        <p class="text-gray-500 text-sm mb-5">Enter your password to confirm. This action is irreversible.</p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf @method('delete')
            @if ($errors->userDeletion->any())
                <p class="text-red-500 text-sm mb-3">{{ $errors->userDeletion->first('password') }}</p>
            @endif
            <div class="relative mb-5">
                <i class="fas fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="password" name="password" placeholder="Enter your password"
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-400 transition-all">
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-xl text-sm transition-all">
                    <i class="fas fa-trash-alt mr-1.5"></i> Yes, Delete
                </button>
                <button type="button" onclick="document.getElementById('delete-modal').classList.add('hidden')"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium py-3 rounded-xl text-sm transition-all">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const avatarInput    = document.getElementById('avatar-input');
    const avatarPreview  = document.getElementById('avatar-preview');
    const avatarActions  = document.getElementById('avatar-actions');
    const avatarFilename = document.getElementById('avatar-filename');
    const originalSrc    = avatarPreview.src;

    avatarInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = e => {
            avatarPreview.src = e.target.result;
            avatarActions.classList.remove('hidden');
            avatarFilename.textContent = file.name;
        };
        reader.readAsDataURL(file);
    });

    function cancelAvatarPreview() {
        avatarPreview.src = originalSrc;
        avatarInput.value = '';
        avatarActions.classList.add('hidden');
        avatarFilename.textContent = '';
    }

    function togglePw(id, btn) {
        const field = document.getElementById(id);
        const icon  = btn.querySelector('i');
        field.type  = field.type === 'password' ? 'text' : 'password';
        icon.className = field.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
    }
</script>
@endsection
