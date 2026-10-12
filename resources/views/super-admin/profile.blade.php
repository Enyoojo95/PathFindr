@extends('layouts.super-admin')
@section('title', 'Profile — PathFindr Master')
@section('page-title', 'My Profile')

@section('content')

<div style="max-width:560px;">

    @if(session('success'))
    <div class="glass-sm" style="padding:12px 16px;font-size:0.82rem;color:#6EE7B7;border-color:rgba(16,185,129,0.3);margin-bottom:16px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('super-admin.profile.update') }}" enctype="multipart/form-data">
        @csrf

        {{-- Avatar --}}
        <div class="glass-card" style="padding:24px;margin-bottom:16px;text-align:center;">
            <div style="position:relative;display:inline-block;margin-bottom:16px;">
                <img id="avatar-preview" src="{{ $admin->avatarUrl() }}" alt="{{ $admin->name }}"
                     style="width:90px;height:90px;border-radius:50%;object-fit:cover;
                            border:3px solid rgba(37,99,235,0.4);
                            box-shadow:0 8px 24px rgba(0,0,0,0.5);">
                <label for="pp-input"
                       style="position:absolute;bottom:-2px;right:-2px;width:28px;height:28px;
                              border-radius:50%;background:linear-gradient(135deg,#2563EB,#7C3AED);
                              display:flex;align-items:center;justify-content:center;cursor:pointer;
                              box-shadow:0 4px 12px rgba(37,99,235,0.4);transition:transform 0.2s;"
                       onmouseover="this.style.transform='scale(1.1)';" onmouseout="this.style.transform='scale(1)';">
                    <i class="fas fa-camera" style="color:#fff;font-size:0.65rem;"></i>
                </label>
            </div>
            <div style="color:#fff;font-weight:700;font-size:1rem;">{{ $admin->name }}</div>
            <div style="color:rgba(255,255,255,0.35);font-size:0.78rem;margin-top:2px;">Super Administrator</div>
            <input type="file" id="pp-input" name="profile_picture" accept="image/*" style="display:none;">
            <p style="font-size:0.7rem;color:rgba(255,255,255,0.25);margin-top:10px;">JPG, PNG or WebP · max 2MB</p>
        </div>

        {{-- Fields --}}
        <div class="glass-card" style="padding:24px;display:flex;flex-direction:column;gap:16px;">

            @if($errors->any())
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:12px;padding:12px 16px;font-size:0.82rem;color:#FCA5A5;">
                @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
            </div>
            @endif

            @foreach([
                ['name'=>'name', 'label'=>'Full Name','type'=>'text',  'icon'=>'fa-user',    'val'=>$admin->name],
                ['name'=>'email','label'=>'Email',    'type'=>'email', 'icon'=>'fa-envelope','val'=>$admin->email],
                ['name'=>'phone','label'=>'Phone',    'type'=>'text',  'icon'=>'fa-phone',   'val'=>$admin->phone ?? ''],
            ] as $f)
            <div>
                <label style="display:block;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);margin-bottom:6px;">{{ $f['label'] }}</label>
                <div style="position:relative;">
                    <i class="fas {{ $f['icon'] }}" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.25);font-size:0.75rem;pointer-events:none;"></i>
                    <input type="{{ $f['type'] }}" name="{{ $f['name'] }}" value="{{ $f['val'] }}"
                           class="glass-input" style="padding-left:38px;">
                </div>
            </div>
            @endforeach

            <div>
                <label style="display:block;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);margin-bottom:6px;">Bio</label>
                <textarea name="bio" rows="3"
                          style="width:100%;background:rgba(255,255,255,0.07);backdrop-filter:blur(12px);
                                 -webkit-backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,0.12);
                                 border-radius:12px;padding:11px 16px;color:#fff;font-size:0.875rem;
                                 font-family:'Inter',sans-serif;outline:none;resize:none;transition:all 0.25s;"
                          onfocus="this.style.borderColor='rgba(37,99,235,0.65)';this.style.boxShadow='0 0 0 3px rgba(37,99,235,0.15)';"
                          onblur="this.style.borderColor='rgba(255,255,255,0.12)';this.style.boxShadow='none';"
                          placeholder="Short bio...">{{ $admin->bio }}</textarea>
            </div>

            <button type="submit" class="glass-btn" style="background:rgba(37,99,235,0.28);border-color:rgba(37,99,235,0.45);"
                    onmouseover="this.style.background='rgba(37,99,235,0.45)';this.style.boxShadow='0 4px 24px rgba(0,0,0,0.4),0 0 24px rgba(37,99,235,0.3)';"
                    onmouseout="this.style.background='rgba(37,99,235,0.28)';this.style.boxShadow='';">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
document.getElementById('pp-input').addEventListener('change', function(){
    var file = this.files[0];
    if(!file) return;
    var reader = new FileReader();
    reader.onload = function(e){ document.getElementById('avatar-preview').src = e.target.result; };
    reader.readAsDataURL(file);
});
</script>
@endsection
