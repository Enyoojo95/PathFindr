<button onclick="window.pfToggleDark()"
        id="pf-dark-btn"
        title="Toggle dark mode"
        style="display:inline-flex;align-items:center;justify-content:center;
               width:36px;height:36px;border-radius:10px;border:1px solid var(--pf-border);
               background:var(--pf-surface);color:var(--pf-text);cursor:pointer;
               transition:all 0.2s;font-size:0.85rem;">
    <i id="pf-dark-icon" class="{{ session('pfDark') ? 'fas fa-sun' : 'fas fa-moon' }}"></i>
</button>

<script>
window.pfToggleDark = function() {
    const html = document.documentElement;
    const isDark = html.classList.toggle('dark');
    localStorage.setItem('pfDark', isDark ? '1' : '0');
    const icon = document.getElementById('pf-dark-icon');
    if (icon) icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
};
// Sync icon on load
(function(){
    const icon = document.getElementById('pf-dark-icon');
    if (icon && localStorage.getItem('pfDark')==='1') icon.className = 'fas fa-sun';
})();
</script>
