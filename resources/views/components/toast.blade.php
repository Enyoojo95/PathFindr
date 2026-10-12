{{--
    iOS 26-style glassmorphic toast notification.
    Auto-renders from session flash and auto-dismisses.
    Usage: just include in layout — reads session automatically.
    Can also be triggered by JS: pfToast('Message', 'success'|'error'|'info')
--}}

@php
    $toastMsg  = session('success') ?? session('error') ?? session('status') ?? null;
    $toastType = session('success') ? 'success' : (session('error') ? 'error' : 'info');
    if ($toastMsg === 'profile-updated') { $toastMsg = 'Profile updated successfully.'; $toastType = 'success'; }
    if ($toastMsg === 'avatar-updated')  { $toastMsg = 'Profile picture updated successfully.'; $toastType = 'success'; }
    if ($toastMsg === 'password-updated') { $toastMsg = 'Password changed successfully.'; $toastType = 'success'; }
@endphp

<style>
/* ── Toast container ── */
#pf-toast-wrap {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 99999;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    pointer-events: none;
    width: min(420px, calc(100vw - 32px));
}

/* ── Individual toast ── */
.pf-toast {
    width: 100%;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 20px;
    pointer-events: all;
    cursor: pointer;

    /* iOS 26 glass */
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(28px) saturate(180%);
    -webkit-backdrop-filter: blur(28px) saturate(180%);
    border: 1px solid rgba(255, 255, 255, 0.35);
    box-shadow:
        0 8px 32px rgba(0, 0, 0, 0.18),
        0 2px 8px rgba(0, 0, 0, 0.08),
        inset 0 1px 0 rgba(255, 255, 255, 0.5);

    /* Animate in */
    animation: toastIn 0.45s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    transition: opacity 0.3s ease, transform 0.3s ease;
}
html.dark .pf-toast {
    background: rgba(22, 27, 34, 0.72);
    border-color: rgba(255, 255, 255, 0.12);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.08);
}
.pf-toast.hiding {
    animation: toastOut 0.35s cubic-bezier(0.4, 0, 1, 1) forwards;
}

@keyframes toastIn {
    from { opacity: 0; transform: translateY(-24px) scale(0.92); }
    to   { opacity: 1; transform: translateY(0)     scale(1); }
}
@keyframes toastOut {
    from { opacity: 1; transform: translateY(0)     scale(1); }
    to   { opacity: 0; transform: translateY(-16px) scale(0.94); }
}

/* ── Icon pill ── */
.pf-toast-icon {
    width: 34px; height: 34px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.9rem; flex-shrink: 0;
}
.pf-toast.success .pf-toast-icon { background: rgba(67,160,71,0.18); color: #43A047; }
.pf-toast.error   .pf-toast-icon { background: rgba(239,68,68,0.15);  color: #EF4444; }
.pf-toast.info    .pf-toast-icon { background: rgba(26,42,108,0.15);  color: #1A2A6C; }
html.dark .pf-toast.success .pf-toast-icon { background: rgba(86,211,100,0.15); color: #56d364; }
html.dark .pf-toast.error   .pf-toast-icon { background: rgba(239,68,68,0.2);   color: #f85149; }
html.dark .pf-toast.info    .pf-toast-icon { background: rgba(121,192,255,0.15); color: #79c0ff; }

/* ── Text ── */
.pf-toast-body { flex: 1; min-width: 0; }
.pf-toast-title {
    font-size: 0.82rem; font-weight: 700; line-height: 1.3;
    color: #111827;
}
html.dark .pf-toast-title { color: #e6edf3; }
.pf-toast-msg {
    font-size: 0.78rem; color: #4B5563; margin-top: 2px; line-height: 1.4;
}
html.dark .pf-toast-msg { color: #8b949e; }

/* ── Close btn ── */
.pf-toast-close {
    width: 22px; height: 22px; border-radius: 50%;
    background: rgba(0,0,0,0.08); border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.65rem; color: #6B7280; flex-shrink: 0;
    margin-top: 2px; transition: background 0.15s;
}
.pf-toast-close:hover { background: rgba(0,0,0,0.15); color: #111; }
html.dark .pf-toast-close { background: rgba(255,255,255,0.08); color: #8b949e; }
html.dark .pf-toast-close:hover { background: rgba(255,255,255,0.15); color: #fff; }

/* ── Progress bar ── */
.pf-toast-progress {
    position: absolute; bottom: 0; left: 0; right: 0; height: 2px;
    border-radius: 0 0 20px 20px; overflow: hidden;
}
.pf-toast-progress-fill {
    height: 100%; width: 100%;
    transform-origin: left;
    transition: transform linear;
}
.pf-toast.success .pf-toast-progress-fill { background: #43A047; }
.pf-toast.error   .pf-toast-progress-fill { background: #EF4444; }
.pf-toast.info    .pf-toast-progress-fill { background: #1A2A6C; }
</style>

<div id="pf-toast-wrap"></div>

<script>
window.pfToast = function(message, type, title) {
    type  = type  || 'info';
    title = title || (type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Notice');

    const icons = {
        success: 'fa-check-circle',
        error:   'fa-exclamation-circle',
        info:    'fa-info-circle',
    };

    const wrap = document.getElementById('pf-toast-wrap');
    const toast = document.createElement('div');
    toast.className = 'pf-toast ' + type;
    toast.style.position = 'relative';
    toast.innerHTML = `
        <div class="pf-toast-icon"><i class="fas ${icons[type]}"></i></div>
        <div class="pf-toast-body">
            <div class="pf-toast-title">${title}</div>
            <div class="pf-toast-msg">${message}</div>
        </div>
        <button class="pf-toast-close" onclick="pfDismissToast(this.closest('.pf-toast'))">
            <i class="fas fa-times"></i>
        </button>
        <div class="pf-toast-progress">
            <div class="pf-toast-progress-fill" id="pftp-${Date.now()}"></div>
        </div>
    `;
    wrap.appendChild(toast);

    // Animate progress bar
    const duration = 5000;
    const fill = toast.querySelector('.pf-toast-progress-fill');
    fill.style.transform = 'scaleX(1)';
    requestAnimationFrame(() => {
        fill.style.transition = `transform ${duration}ms linear`;
        fill.style.transform  = 'scaleX(0)';
    });

    // Auto dismiss
    const timer = setTimeout(() => pfDismissToast(toast), duration);
    toast.dataset.timer = timer;

    // Pause on hover
    toast.addEventListener('mouseenter', () => {
        clearTimeout(parseInt(toast.dataset.timer));
        fill.style.transition = 'none';
    });
    toast.addEventListener('mouseleave', () => {
        const remaining = parseFloat(fill.style.transform.replace('scaleX(','')) * duration;
        const newTimer = setTimeout(() => pfDismissToast(toast), remaining || 1500);
        toast.dataset.timer = newTimer;
        fill.style.transition = `transform ${remaining || 1500}ms linear`;
        fill.style.transform  = 'scaleX(0)';
    });
};

window.pfDismissToast = function(toast) {
    if (!toast || toast.classList.contains('hiding')) return;
    clearTimeout(parseInt(toast.dataset.timer));
    toast.classList.add('hiding');
    toast.addEventListener('animationend', () => toast.remove(), { once: true });
};

// Auto-fire server-side flash messages on page load
@if($toastMsg)
document.addEventListener('DOMContentLoaded', function() {
    pfToast(
        @json($toastMsg),
        @json($toastType)
    );
});
@endif
</script>
