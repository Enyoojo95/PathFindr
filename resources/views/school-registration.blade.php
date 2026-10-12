
@extends('layouts.app')
@section('title', 'Register Your School — PathFindr')

@section('styles')
<style>
/* ── Page background ── */
.reg-page {
    min-height: 100vh;
    background: linear-gradient(160deg, #020c1b 0%, #0a1628 40%, #0d2137 70%, #071a2e 100%);
    position: relative;
    overflow-x: hidden;
    font-family: 'Inter', sans-serif;
}

/* Animated dot grid */
.reg-page::before {
    content: '';
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
    background-image: radial-gradient(circle, rgba(99,211,180,0.08) 1px, transparent 1px);
    background-size: 32px 32px;
    animation: gridShift 20s linear infinite;
}
@keyframes gridShift {
    0%   { background-position: 0 0; }
    100% { background-position: 32px 32px; }
}

/* Ambient colour blobs */
.blob {
    position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
    filter: blur(80px); opacity: 0.35;
    animation: blobFloat 8s ease-in-out infinite;
}
@keyframes blobFloat {
    0%,100% { transform: translateY(0) scale(1); }
    50%     { transform: translateY(-24px) scale(1.06); }
}

/* Glass card */
.glass {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(24px);
    -webkit-backdrop-filter: blur(24px);
    border: 1px solid rgba(99,211,180,0.18);
    box-shadow: 0 8px 40px rgba(0,0,0,0.55), inset 0 1px 0 rgba(255,255,255,0.07);
    border-radius: 24px;
}

/* Input */
.reg-input {
    width: 100%;
    background: rgba(255,255,255,0.06);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(99,211,180,0.2);
    border-radius: 12px;
    padding: 13px 16px 13px 44px;
    color: #fff;
    font-size: 0.875rem;
    font-family: 'Inter', sans-serif;
    outline: none;
    transition: all 0.25s ease;
}
.reg-input::placeholder { color: rgba(255,255,255,0.5); }
.reg-input:focus {
    border-color: rgba(99,211,180,0.8);
    background: rgba(255,255,255,0.10);
    box-shadow: 0 0 0 4px rgba(99,211,180,0.2);
}
select.reg-input option { background: #0a1628; color: #fff; }
textarea.reg-input { padding-top: 13px; resize: vertical; }

/* Label */
.reg-label {
    display: block;
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em;
    color: rgba(153,230,210,0.9);
    margin-bottom: 6px;
}

/* Submit button */
.reg-btn {
    width: 100%;
    background: linear-gradient(135deg, #0ea5e9, #10b981);
    border: none; border-radius: 14px;
    padding: 15px;
    color: #fff; font-size: 1rem; font-weight: 700;
    font-family: 'Inter', sans-serif;
    cursor: pointer; letter-spacing: 0.02em;
    transition: all 0.3s ease;
    box-shadow: 0 6px 28px rgba(14,165,233,0.4);
    position: relative; overflow: hidden;
}
.reg-btn::after {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
    opacity: 0; transition: opacity 0.3s;
}
.reg-btn:hover {
    transform: translateY(-2px) scale(1.01);
    box-shadow: 0 10px 40px rgba(14,165,233,0.55);
}
.reg-btn:hover::after { opacity: 1; }
.reg-btn:active { transform: scale(0.99); }

/* Field icon */
.field-wrap { position: relative; }
.field-icon {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: rgba(99,211,180,0.5); font-size: 0.8rem; pointer-events: none;
}
.field-icon.top { top: 14px; transform: none; }

/* Step indicator */
.step-dot {
    width: 10px; height: 10px; border-radius: 50%;
    background: rgba(99,211,180,0.25);
    border: 1px solid rgba(99,211,180,0.4);
    transition: all 0.3s ease;
}
.step-dot.active {
    background: #10b981;
    box-shadow: 0 0 12px rgba(16,185,129,0.6);
    border-color: #10b981;
}

/* Floating particle */
.particle {
    position: fixed; border-radius: 50%; pointer-events: none; z-index: 0;
    background: rgba(99,211,180,0.6);
}

/* Success state */
.success-card {
    animation: successPop 0.5s cubic-bezier(0.34,1.56,0.64,1) forwards;
}
@keyframes successPop {
    from { opacity: 0; transform: scale(0.85) translateY(20px); }
    to   { opacity: 1; transform: scale(1)    translateY(0); }
}

/* Staggered field entrance */
.field-enter {
    opacity: 0; transform: translateY(18px);
    animation: fieldIn 0.45s ease forwards;
}
@keyframes fieldIn {
    to { opacity: 1; transform: translateY(0); }
}

/* Error box */
.err-box {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.3);
    border-radius: 12px;
    padding: 12px 16px;
    color: #FCA5A5;
    font-size: 0.82rem;
}
</style>
@endsection

@section('content')
<div class="reg-page">

    {{-- Animated blobs --}}
    <div class="blob" style="width:500px;height:500px;top:-100px;left:-150px;background:radial-gradient(circle,#0ea5e9,transparent);animation-delay:0s;"></div>
    <div class="blob" style="width:400px;height:400px;bottom:-80px;right:-100px;background:radial-gradient(circle,#10b981,transparent);animation-delay:3s;"></div>
    <div class="blob" style="width:300px;height:300px;top:40%;left:55%;background:radial-gradient(circle,#06b6d4,transparent);animation-delay:1.5s;opacity:0.2;"></div>

    <div style="position:relative;z-index:1;max-width:860px;margin:0 auto;padding:48px 20px 80px;">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:48px;" class="field-enter">
            <div style="display:inline-flex;align-items:center;gap:8px;padding:6px 18px;
                        background:rgba(99,211,180,0.1);border:1px solid rgba(99,211,180,0.25);
                        border-radius:99px;font-size:0.75rem;font-weight:700;
                        color:rgba(99,211,180,0.9);letter-spacing:0.08em;margin-bottom:20px;">
                <span style="width:6px;height:6px;border-radius:50%;background:#10b981;display:inline-block;animation:pulse 2s infinite;"></span>
                SCHOOL PARTNERSHIP PROGRAMME
            </div>
            <h1 style="font-size:clamp(2rem,5vw,3.2rem);font-weight:900;color:#fff;
                       letter-spacing:-0.03em;line-height:1.1;margin-bottom:16px;">
                Empower Your Students with<br>
                <span style="background:linear-gradient(135deg,#0ea5e9,#10b981);
                             -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                             background-clip:text;">
                    AI Career Guidance
                </span>
            </h1>
            <p style="font-size:1.05rem;color:rgba(255,255,255,0.5);max-width:520px;margin:0 auto;line-height:1.6;">
                Join PathFindr and give every JSS3 student in your school a personalised road map to their future — powered by psychometric science and AI.
            </p>
        </div>

        {{-- Trust badges --}}
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:12px;margin-bottom:44px;" class="field-enter" style="animation-delay:0.1s;">
            @foreach(['✦ Free to Join','✦ AI-Powered Reports','✦ Dedicated Admin Portal','✦ Nigerian Curriculum'] as $b)
            <span style="padding:6px 16px;background:rgba(255,255,255,0.05);border:1px solid rgba(99,211,180,0.2);
                         border-radius:99px;font-size:0.75rem;font-weight:600;color:rgba(99,211,180,0.8);">
                {{ $b }}
            </span>
            @endforeach
        </div>

        {{-- ── SUCCESS STATE ── --}}
        @if(session('submitted'))
        <div class="glass success-card" style="padding:56px 40px;text-align:center;">
            <div style="width:72px;height:72px;border-radius:50%;
                        background:linear-gradient(135deg,#0ea5e9,#10b981);
                        display:flex;align-items:center;justify-content:center;
                        margin:0 auto 24px;
                        box-shadow:0 8px 32px rgba(16,185,129,0.4);">
                <i class="fas fa-check" style="color:#fff;font-size:1.8rem;"></i>
            </div>
            <h2 style="color:#fff;font-size:1.8rem;font-weight:900;margin-bottom:10px;">Application Received!</h2>
            <p style="color:rgba(255,255,255,0.55);max-width:440px;margin:0 auto 28px;line-height:1.6;">
                Thank you for registering your school. Our team will review your application and reach out within <strong style="color:rgba(99,211,180,0.9);">2–3 business days</strong> with your admin credentials.
            </p>
            <a href="{{ url('/') }}"
               style="display:inline-flex;align-items:center;gap:8px;
                      background:linear-gradient(135deg,#0ea5e9,#10b981);
                      padding:12px 28px;border-radius:12px;color:#fff;
                      font-weight:700;text-decoration:none;font-size:0.9rem;
                      box-shadow:0 6px 24px rgba(14,165,233,0.35);
                      transition:all 0.3s;">
                <i class="fas fa-home"></i> Back to Home
            </a>
        </div>

        {{-- ── FORM ── --}}
        @else
        <form method="POST" action="{{ route('school-registration.store') }}">
            @csrf

            {{-- Validation errors --}}
            @if($errors->any())
            <div class="err-box" style="margin-bottom:24px;">
                <div style="font-weight:700;margin-bottom:6px;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-exclamation-circle"></i> Please fix the following:
                </div>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:3px;">
                    @foreach($errors->all() as $e)
                    <li>— {{ $e }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="glass" style="padding:40px 36px;display:flex;flex-direction:column;gap:28px;">

                {{-- Section: School Identity --}}
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:32px;height:32px;border-radius:10px;
                                    background:linear-gradient(135deg,#0ea5e9,#10b981);
                                    display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 4px 12px rgba(14,165,233,0.3);">
                            <i class="fas fa-school" style="color:#fff;font-size:0.8rem;"></i>
                        </div>
                        <h3 style="color:#fff;font-weight:700;font-size:1rem;margin:0;">School Identity</h3>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;">
                        <div class="field-enter" style="animation-delay:0.05s;">
                            <label class="reg-label">School Name *</label>
                            <div class="field-wrap">
                                <i class="fas fa-building field-icon"></i>
                                <input type="text" name="school_name" value="{{ old('school_name') }}" required
                                       placeholder="e.g. Crystalfield Academy"
                                       class="reg-input @error('school_name') !border-red-500/60 @enderror">
                            </div>
                        </div>
                        <div class="field-enter" style="animation-delay:0.1s;">
                            <label class="reg-label">School Website</label>
                            <div class="field-wrap">
                                <i class="fas fa-globe field-icon"></i>
                                <input type="url" name="website" value="{{ old('website') }}"
                                       placeholder="https://yourschool.ng"
                                       class="reg-input @error('website') !border-red-500/60 @enderror">
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid rgba(99,211,180,0.1);">

                {{-- Section: Location --}}
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:32px;height:32px;border-radius:10px;
                                    background:linear-gradient(135deg,#06b6d4,#0ea5e9);
                                    display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 4px 12px rgba(6,182,212,0.3);">
                            <i class="fas fa-map-marker-alt" style="color:#fff;font-size:0.8rem;"></i>
                        </div>
                        <h3 style="color:#fff;font-weight:700;font-size:1rem;margin:0;">Location</h3>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;">
                        <div class="field-enter" style="animation-delay:0.12s;">
                            <label class="reg-label">Full Address *</label>
                            <div class="field-wrap">
                                <i class="fas fa-location-dot field-icon"></i>
                                <input type="text" name="address" value="{{ old('address') }}" required
                                       placeholder="Street, Area, City"
                                       class="reg-input @error('address') !border-red-500/60 @enderror">
                            </div>
                        </div>
                        <div class="field-enter" style="animation-delay:0.16s;">
                            <label class="reg-label">State / Province *</label>
                            <div class="field-wrap">
                                <i class="fas fa-map field-icon"></i>
                                <input type="text" name="state" value="{{ old('state') }}" required
                                       placeholder="e.g. FCT Abuja"
                                       class="reg-input @error('state') !border-red-500/60 @enderror">
                            </div>
                        </div>
                        <div class="field-enter" style="animation-delay:0.2s;">
                            <label class="reg-label">Country *</label>
                            <div class="field-wrap">
                                <i class="fas fa-flag field-icon"></i>
                                <input type="text" name="country" value="{{ old('country', 'Nigeria') }}" required
                                       placeholder="Nigeria"
                                       class="reg-input @error('country') !border-red-500/60 @enderror">
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid rgba(99,211,180,0.1);">

                {{-- Section: Contact --}}
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:32px;height:32px;border-radius:10px;
                                    background:linear-gradient(135deg,#10b981,#06b6d4);
                                    display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 4px 12px rgba(16,185,129,0.3);">
                            <i class="fas fa-address-card" style="color:#fff;font-size:0.8rem;"></i>
                        </div>
                        <h3 style="color:#fff;font-weight:700;font-size:1rem;margin:0;">Contact Details</h3>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
                        <div class="field-enter" style="animation-delay:0.22s;">
                            <label class="reg-label">Official Email *</label>
                            <div class="field-wrap">
                                <i class="fas fa-envelope field-icon"></i>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="principal@school.ng"
                                       class="reg-input @error('email') !border-red-500/60 @enderror">
                            </div>
                        </div>
                        <div class="field-enter" style="animation-delay:0.26s;">
                            <label class="reg-label">Phone Number *</label>
                            <div class="field-wrap">
                                <i class="fas fa-phone field-icon"></i>
                                <input type="tel" name="phone" value="{{ old('phone') }}" required
                                       placeholder="+234 800 000 0000"
                                       class="reg-input @error('phone') !border-red-500/60 @enderror">
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid rgba(99,211,180,0.1);">

                {{-- Section: Academic Profile --}}
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:32px;height:32px;border-radius:10px;
                                    background:linear-gradient(135deg,#7c3aed,#0ea5e9);
                                    display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 4px 12px rgba(124,58,237,0.3);">
                            <i class="fas fa-graduation-cap" style="color:#fff;font-size:0.8rem;"></i>
                        </div>
                        <h3 style="color:#fff;font-weight:700;font-size:1rem;margin:0;">Academic Profile</h3>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
                        <div class="field-enter" style="animation-delay:0.28s;">
                            <label class="reg-label">Number of Classes *</label>
                            <div class="field-wrap">
                                <i class="fas fa-chalkboard field-icon"></i>
                                <input type="number" name="number_of_classes" value="{{ old('number_of_classes') }}"
                                       required min="1" max="500" placeholder="e.g. 12"
                                       class="reg-input @error('number_of_classes') !border-red-500/60 @enderror">
                            </div>
                        </div>
                        <div class="field-enter" style="animation-delay:0.32s;">
                            <label class="reg-label">Approximate Student Count *</label>
                            <div class="field-wrap">
                                <i class="fas fa-users field-icon"></i>
                                <select name="student_count_range" required
                                        class="reg-input @error('student_count_range') !border-red-500/60 @enderror">
                                    <option value="" disabled {{ old('student_count_range') ? '' : 'selected' }}>Select range</option>
                                    @foreach(['Under 50','50 – 100','100 – 200','200 – 500','500 – 1,000','1,000 – 2,000','Over 2,000'] as $r)
                                    <option value="{{ $r }}" {{ old('student_count_range') === $r ? 'selected' : '' }}>{{ $r }} students</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <hr style="border:none;border-top:1px solid rgba(99,211,180,0.1);">

                {{-- Section: Why PathFindr --}}
                <div class="field-enter" style="animation-delay:0.36s;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                        <div style="width:32px;height:32px;border-radius:10px;
                                    background:linear-gradient(135deg,#f59e0b,#10b981);
                                    display:flex;align-items:center;justify-content:center;
                                    box-shadow:0 4px 12px rgba(245,158,11,0.3);">
                            <i class="fas fa-lightbulb" style="color:#fff;font-size:0.8rem;"></i>
                        </div>
                        <h3 style="color:#fff;font-weight:700;font-size:1rem;margin:0;">Why PathFindr?</h3>
                    </div>
                    <label class="reg-label">Tell us why your school wants to join *</label>
                    <div class="field-wrap">
                        <i class="fas fa-pen-to-square field-icon top" style="top:14px;"></i>
                        <textarea name="reason" rows="5" required
                                  placeholder="Share your motivation — e.g. helping JSS3 students make informed SS1 subject choices, reducing career confusion, partnering with AI-driven tools..."
                                  class="reg-input @error('reason') !border-red-500/60 @enderror"
                                  style="padding-left:44px;">{{ old('reason') }}</textarea>
                    </div>
                    <p style="font-size:0.72rem;color:rgba(255,255,255,0.3);margin-top:6px;">Minimum 30 characters</p>
                </div>

                {{-- Submit --}}
                <div class="field-enter" style="animation-delay:0.4s;">
                    <button type="submit" class="reg-btn">
                        <i class="fas fa-rocket" style="margin-right:8px;"></i>
                        Submit Registration Request
                        <i class="fas fa-arrow-right" style="margin-left:8px;font-size:0.8em;"></i>
                    </button>
                    <p style="text-align:center;font-size:0.75rem;color:rgba(255,255,255,0.3);margin-top:12px;">
                        <i class="fas fa-shield-halved" style="margin-right:4px;color:rgba(99,211,180,0.5);"></i>
                        Your information is secure. We'll respond within 2–3 business days.
                    </p>
                </div>
            </div>
        </form>
        @endif

        {{-- Bottom: why section --}}
        <div style="margin-top:56px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            @foreach([
                ['fa-bolt','Fast Setup','Your admin account and portal are activated within 72 hours of approval.'],
                ['fa-brain','Psychometric Engine','Proven 30-min test for numerical, verbal, spatial & interest strengths.'],
                ['fa-file-pdf','Instant Reports','Students download AI-generated PDF career reports immediately after testing.'],
                ['fa-headset','Ongoing Support','Dedicated onboarding support from the PathFindr team.'],
            ] as [$icon,$title,$desc])
            <div class="glass" style="padding:22px;transition:all 0.3s;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.borderColor='rgba(99,211,180,0.35)';"
                 onmouseout="this.style.transform='';this.style.borderColor='rgba(99,211,180,0.18)';">
                <i class="fas {{ $icon }}" style="font-size:1.3rem;margin-bottom:12px;display:block;
                               background:linear-gradient(135deg,#0ea5e9,#10b981);
                               -webkit-background-clip:text;-webkit-text-fill-color:transparent;
                               background-clip:text;"></i>
                <div style="color:#fff;font-weight:700;font-size:0.9rem;margin-bottom:6px;">{{ $title }}</div>
                <div style="color:rgba(255,255,255,0.4);font-size:0.78rem;line-height:1.5;">{{ $desc }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
@keyframes pulse { 0%,100%{box-shadow:0 0 0 0 rgba(16,185,129,0.5)} 50%{box-shadow:0 0 0 6px rgba(16,185,129,0)} }
</style>
@endsection
