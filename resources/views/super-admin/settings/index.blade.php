@extends('layouts.super-admin')
@section('title', 'Settings — PathFindr Master')
@section('page-title', 'System Settings')

@section('content')

<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;">

    {{-- General --}}
    <div class="glass-card" style="padding:24px;">
        <h3 style="color:#fff;font-weight:700;font-size:0.9rem;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-sliders" style="color:#2563EB;font-size:0.8rem;"></i> General Settings
        </h3>
        <form method="POST" action="{{ route('super-admin.settings.update') }}" style="display:flex;flex-direction:column;gap:14px;">
            @csrf
            <div>
                <label style="display:block;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);margin-bottom:6px;">Site Name</label>
                <input type="text" name="site_name" value="{{ $settings['site_name'] }}" class="glass-input">
            </div>
            <div>
                <label style="display:block;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);margin-bottom:6px;">Axel AI API Key</label>
                <div style="position:relative;">
                    <input type="password" name="openai_key" value="{{ $settings['openai_key'] }}" id="oai-key" class="glass-input" style="padding-right:40px;">
                    <button type="button" onclick="var f=document.getElementById('oai-key');var i=this.querySelector('i');f.type=f.type==='password'?'text':'password';i.className=f.type==='password'?'fas fa-eye':'fas fa-eye-slash';"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.3);">
                        <i class="fas fa-eye" style="font-size:0.78rem;"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="glass-btn" style="background:rgba(37,99,235,0.25);border-color:rgba(37,99,235,0.4);">
                <i class="fas fa-save" style="font-size:0.78rem;"></i> Save
            </button>
        </form>
    </div>

    {{-- Email --}}
    <div class="glass-card" style="padding:24px;">
        <h3 style="color:#fff;font-weight:700;font-size:0.9rem;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-envelope" style="color:#7C3AED;font-size:0.8rem;"></i> Email (SMTP)
        </h3>
        <form method="POST" action="{{ route('super-admin.settings.update') }}" style="display:flex;flex-direction:column;gap:14px;">
            @csrf
            @foreach([
                ['n'=>'mail_host','l'=>'SMTP Host','v'=>$settings['mail_host'],'p'=>'smtp.mailtrap.io'],
                ['n'=>'mail_port','l'=>'SMTP Port','v'=>$settings['mail_port'],'p'=>'587'],
                ['n'=>'mail_username','l'=>'Username','v'=>$settings['mail_username'],'p'=>'username'],
            ] as $f)
            <div>
                <label style="display:block;font-size:0.68rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:rgba(255,255,255,0.35);margin-bottom:6px;">{{ $f['l'] }}</label>
                <input type="text" name="{{ $f['n'] }}" value="{{ $f['v'] }}" placeholder="{{ $f['p'] }}" class="glass-input">
            </div>
            @endforeach
            <button type="submit" class="glass-btn" style="background:rgba(124,58,237,0.25);border-color:rgba(124,58,237,0.4);">
                <i class="fas fa-save" style="font-size:0.78rem;"></i> Save
            </button>
        </form>
    </div>

    {{-- Feature Toggles --}}
    <div class="glass-card" style="padding:24px;">
        <h3 style="color:#fff;font-weight:700;font-size:0.9rem;margin-bottom:18px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-toggle-on" style="color:#10B981;font-size:0.8rem;"></i> Feature Toggles
        </h3>
        <form method="POST" action="{{ route('super-admin.settings.update') }}" style="display:flex;flex-direction:column;gap:4px;">
            @csrf
            @foreach([
                ['n'=>'whatsapp_sharing',    'l'=>'WhatsApp Sharing',    'v'=>$settings['whatsapp_sharing']],
                ['n'=>'student_registration','l'=>'Student Registration','v'=>$settings['student_registration']],
                ['n'=>'pdf_reports',         'l'=>'PDF Reports',         'v'=>$settings['pdf_reports']],
            ] as $f)
            <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid rgba(255,255,255,0.05);">
                <span style="color:rgba(255,255,255,0.7);font-size:0.82rem;">{{ $f['l'] }}</span>
                <label style="position:relative;display:inline-flex;align-items:center;cursor:pointer;">
                    <input type="checkbox" name="{{ $f['n'] }}" value="1" {{ $f['v'] ? 'checked' : '' }}
                           style="position:absolute;opacity:0;width:0;height:0;" id="toggle-{{ $f['n'] }}"
                           onchange="var t=document.getElementById('track-{{ $f['n'] }}');var d=document.getElementById('dot-{{ $f['n'] }}');if(this.checked){t.style.background='#10B981';d.style.transform='translateX(18px)';}else{t.style.background='rgba(255,255,255,0.12)';d.style.transform='translateX(2px)';}">
                    <div id="track-{{ $f['n'] }}" style="width:38px;height:20px;border-radius:99px;transition:background 0.2s;background:{{ $f['v'] ? '#10B981' : 'rgba(255,255,255,0.12)' }};border:1px solid rgba(255,255,255,0.1);"></div>
                    <div id="dot-{{ $f['n'] }}" style="position:absolute;top:2px;width:16px;height:16px;border-radius:50%;background:#fff;box-shadow:0 1px 4px rgba(0,0,0,0.3);transition:transform 0.2s;transform:translateX({{ $f['v'] ? '20px' : '2px' }});"></div>
                </label>
            </div>
            @endforeach
            <button type="submit" class="glass-btn" style="margin-top:12px;background:rgba(16,185,129,0.2);border-color:rgba(16,185,129,0.35);">
                <i class="fas fa-save" style="font-size:0.78rem;"></i> Save Toggles
            </button>
        </form>
    </div>

    {{-- Maintenance --}}
    <div class="glass-card" id="maintenance" style="padding:24px;">
        <h3 style="color:#fff;font-weight:700;font-size:0.9rem;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-shield-halved" style="color:#EF4444;font-size:0.8rem;"></i> Maintenance Mode
        </h3>
        <p style="font-size:0.78rem;color:rgba(255,255,255,0.35);margin-bottom:16px;">Enabling maintenance mode takes the site offline for regular users.</p>
        <form method="POST" action="{{ route('super-admin.maintenance') }}" style="display:flex;flex-direction:column;gap:12px;">
            @csrf
            <textarea name="message" rows="3"
                      style="width:100%;background:rgba(255,255,255,0.06);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
                             border:1px solid rgba(255,255,255,0.10);border-radius:12px;padding:10px 14px;
                             color:#fff;font-size:0.82rem;font-family:'Inter',sans-serif;outline:none;resize:none;"
                      placeholder="We'll be back shortly..."></textarea>
            <div style="display:flex;gap:8px;">
                <button type="submit" name="enable" value="1" class="glass-btn" style="flex:1;background:rgba(239,68,68,0.2);border-color:rgba(239,68,68,0.35);">
                    <i class="fas fa-power-off" style="font-size:0.75rem;"></i> Enable
                </button>
                <button type="submit" name="enable" value="0" class="glass-btn" style="flex:1;background:rgba(16,185,129,0.2);border-color:rgba(16,185,129,0.35);">
                    <i class="fas fa-check" style="font-size:0.75rem;"></i> Go Live
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
