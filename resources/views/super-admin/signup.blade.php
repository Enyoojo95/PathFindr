<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PathFindr — Create Master Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif; min-height: 100vh;
            background: #0B1124;
            background-image:
                radial-gradient(ellipse 70% 60% at 80% 10%, rgba(124,58,237,0.22) 0%, transparent 60%),
                radial-gradient(ellipse 55% 50% at 20% 85%, rgba(37,99,235,0.18) 0%, transparent 55%);
            display: flex; align-items: center; justify-content: center;
            padding: 20px; color: #fff;
        }
        body::before {
            content:'';position:fixed;inset:0;pointer-events:none;
            background-image:linear-gradient(rgba(255,255,255,0.025) 1px,transparent 1px),
                             linear-gradient(90deg,rgba(255,255,255,0.025) 1px,transparent 1px);
            background-size:40px 40px;
        }
        .glass-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(32px); -webkit-backdrop-filter: blur(32px);
            border: 1px solid rgba(255,255,255,0.13);
            box-shadow: 0 24px 64px rgba(0,0,0,0.6), inset 0 1px 0 rgba(255,255,255,0.09);
            border-radius: 24px; padding: 40px;
            width: 100%; max-width: 420px; position: relative; z-index: 1;
        }
        .glass-input {
            width: 100%;
            background: rgba(255,255,255,0.07); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.12); border-radius: 12px;
            padding: 11px 16px 11px 40px;
            color: #fff; font-size: 0.875rem; font-family: 'Inter', sans-serif;
            outline: none; transition: all 0.25s ease;
        }
        .glass-input::placeholder { color: rgba(255,255,255,0.28); }
        .glass-input:focus { border-color: rgba(124,58,237,0.65); background: rgba(255,255,255,0.10); box-shadow: 0 0 0 3px rgba(124,58,237,0.15); }
        .glass-btn {
            width: 100%;
            background: rgba(124,58,237,0.28); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(124,58,237,0.45); border-radius: 12px;
            padding: 12px; color: #fff; font-size: 0.9rem; font-weight: 700;
            font-family: 'Inter', sans-serif; cursor: pointer; transition: all 0.25s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .glass-btn:hover { background: rgba(124,58,237,0.45); border-color: rgba(124,58,237,0.7); box-shadow: 0 4px 24px rgba(0,0,0,0.4), 0 0 24px rgba(124,58,237,0.3); transform: scale(1.02); }
        .icon-wrap { position: relative; }
        .icon-wrap i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.3); font-size: 0.78rem; pointer-events: none; }
        .field-label { display: block; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.4); margin-bottom: 6px; }
        .error-box { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: 12px; padding: 12px 16px; font-size: 0.82rem; color: #FCA5A5; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="glass-card">
    <div style="text-align:center; margin-bottom:28px;">
        <div style="width:52px;height:52px;border-radius:16px;background:linear-gradient(135deg,#7C3AED,#2563EB);
                    display:flex;align-items:center;justify-content:center;margin:0 auto 14px;
                    box-shadow:0 8px 24px rgba(124,58,237,0.4);">
            <i class="fas fa-user-shield" style="color:#fff;font-size:1.3rem;"></i>
        </div>
        <div style="font-size:1.4rem;font-weight:900;color:#fff;letter-spacing:-0.02em;">Create Master Account</div>
        <div style="font-size:0.8rem;color:rgba(255,255,255,0.3);margin-top:4px;">Authorised personnel only</div>
    </div>

    @if($errors->any())
    <div class="error-box">
        <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>
        @foreach($errors->all() as $e){{ $e }}<br>@endforeach
    </div>
    @endif

    <form method="POST" action="{{ route('super-admin.signup.post') }}" style="display:flex;flex-direction:column;gap:14px;">
        @csrf
        @foreach([
            ['name'=>'name',                 'label'=>'Full Name',       'type'=>'text',     'icon'=>'fa-user',     'ph'=>'Austin-Great'],
            ['name'=>'email',                'label'=>'Email',           'type'=>'email',    'icon'=>'fa-envelope', 'ph'=>'you@pathfindr.ng'],
            ['name'=>'secret_code',          'label'=>'Master Code',     'type'=>'password', 'icon'=>'fa-key',      'ph'=>'Secret master code'],
            ['name'=>'password',             'label'=>'Password',        'type'=>'password', 'icon'=>'fa-lock',     'ph'=>'Min. 8 characters'],
            ['name'=>'password_confirmation','label'=>'Confirm Password','type'=>'password', 'icon'=>'fa-lock',     'ph'=>'Repeat password'],
        ] as $f)
        <div>
            <label class="field-label">{{ $f['label'] }}</label>
            <div class="icon-wrap">
                <i class="fas {{ $f['icon'] }}"></i>
                <input type="{{ $f['type'] }}" name="{{ $f['name'] }}"
                       value="{{ $f['type'] !== 'password' ? old($f['name']) : '' }}"
                       required placeholder="{{ $f['ph'] }}" class="glass-input">
            </div>
        </div>
        @endforeach

        <button type="submit" class="glass-btn" style="margin-top:6px;">
            <i class="fas fa-user-plus"></i> Create Account
        </button>
    </form>

    <p style="text-align:center;font-size:0.78rem;color:rgba(255,255,255,0.3);margin-top:20px;">
        Already registered?
        <a href="{{ route('super-admin.login') }}" style="color:rgba(99,143,255,0.9);font-weight:600;text-decoration:none;margin-left:4px;">Sign in</a>
    </p>
</div>

</body>
</html>
