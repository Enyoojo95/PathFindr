{{--
    Usage (Alpine.js required):

    <x-glass.modal id="add-school-modal" title="Add New School">
        {{-- form content --}}
    </x-glass.modal>

    Open trigger: @click="$dispatch('open-modal', 'add-school-modal')"
    Or plain JS:  openGlassModal('add-school-modal')
--}}
@props([
    'id'      => 'glass-modal',
    'title'   => '',
    'maxWidth'=> '520px',
])

<div
    id="{{ $id }}"
    class="glass-modal-backdrop"
    style="display:none;"
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $id }}-title"
    onclick="if(event.target===this) closeGlassModal('{{ $id }}')"
    onkeydown="if(event.key==='Escape') closeGlassModal('{{ $id }}')"
>
    <div class="glass-modal-panel" style="max-width:{{ $maxWidth }};">

        {{-- Header --}}
        <div style="display:flex;align-items:center;justify-content:space-between;
                    padding:20px 24px 16px;border-bottom:1px solid rgba(255,255,255,0.07);">
            <h3 id="{{ $id }}-title"
                style="color:#fff;font-size:1rem;font-weight:700;margin:0;">
                {{ $title }}
            </h3>
            <button
                type="button"
                onclick="closeGlassModal('{{ $id }}')"
                style="background:none;border:none;cursor:pointer;
                       color:rgba(255,255,255,0.35);font-size:1rem;
                       transition:color 0.15s;padding:4px;"
                onmouseover="this.style.color='#fff';"
                onmouseout="this.style.color='rgba(255,255,255,0.35)';"
                aria-label="Close"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:24px;">
            {{ $slot }}
        </div>
    </div>
</div>

{{-- Modal JS helper (injected once per page by the first modal) --}}
@once
<script>
function openGlassModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('closing');
    el.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    // Return focus to modal panel
    el.querySelector('.glass-modal-panel').focus?.();
}
function closeGlassModal(id) {
    var el = document.getElementById(id);
    if (!el) return;
    el.classList.add('closing');
    setTimeout(function() {
        el.style.display = 'none';
        el.classList.remove('closing');
        document.body.style.overflow = '';
    }, 200);
}
// Global Escape key handler
document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    document.querySelectorAll('.glass-modal-backdrop[style*="flex"]').forEach(function(el) {
        closeGlassModal(el.id);
    });
});
</script>
@endonce
