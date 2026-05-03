<?php
// ============================================================
//  views/layouts/toast.php
//  Systeme de toast — aligne avec helpers.php
//  types : success | error | warning | info
//  A inclure UNE FOIS dans chaque vue, juste apres <body>
// ============================================================
?>

<!-- Conteneur des toasts -->
<div id="toast-container"
     style="position:fixed;top:20px;right:20px;z-index:9999;
            display:flex;flex-direction:column;gap:10px;pointer-events:none;">
</div>

<?php if (!empty($_SESSION['toast'])): ?>
<script>
    // Declenche le toast PHP au chargement de la page
    document.addEventListener('DOMContentLoaded', function () {
        showToast(
            <?= json_encode($_SESSION['toast']['message']) ?>,
            <?= json_encode($_SESSION['toast']['type']) ?>
        );
    });
</script>
<?php unset($_SESSION['toast']); ?>
<?php endif; ?>

<!-- ======== STYLES ======== -->
<style>
.toast {
    display          : flex;
    align-items      : flex-start;
    gap              : 12px;
    min-width        : 300px;
    max-width        : 420px;
    padding          : 14px 16px;
    border-radius    : 12px;
    box-shadow       : 0 8px 32px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.10);
    font-family      : 'DM Sans', sans-serif;
    font-size        : 14px;
    font-weight      : 500;
    line-height      : 1.4;
    pointer-events   : all;
    cursor           : pointer;
    border-left      : 4px solid transparent;
    animation        : toastSlideIn 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards;
    position         : relative;
    overflow         : hidden;
}
.toast::before {
    content          : '';
    position         : absolute;
    bottom           : 0;
    left             : 0;
    height           : 3px;
    width            : 100%;
    transform-origin : left;
    animation        : toastProgress 4s linear forwards;
}
.toast-success { background:rgba(240,253,244,0.97); color:#166534; border-left-color:#22c55e; }
.toast-success::before { background:#22c55e; }
.toast-error   { background:rgba(254,242,242,0.97); color:#991b1b; border-left-color:#ef4444; }
.toast-error::before   { background:#ef4444; }
.toast-warning { background:rgba(255,251,235,0.97); color:#92400e; border-left-color:#f59e0b; }
.toast-warning::before { background:#f59e0b; }
.toast-info    { background:rgba(239,246,255,0.97); color:#1e40af; border-left-color:#5B4FE8; }
.toast-info::before    { background:#5B4FE8; }

.toast-icon  { flex-shrink:0; width:20px; height:20px; margin-top:1px; }
.toast-close {
    flex-shrink:0; margin-left:auto; opacity:0.5; transition:opacity 0.2s;
    background:none; border:none; cursor:pointer; padding:0; color:inherit; line-height:1;
}
.toast-close:hover { opacity:1; }
.toast.hiding { animation: toastSlideOut 0.25s ease-in forwards; }

@keyframes toastSlideIn {
    from { transform:translateX(110%); opacity:0; }
    to   { transform:translateX(0);    opacity:1; }
}
@keyframes toastSlideOut {
    from { transform:translateX(0);    opacity:1; }
    to   { transform:translateX(110%); opacity:0; }
}
@keyframes toastProgress {
    from { transform:scaleX(1); }
    to   { transform:scaleX(0); }
}
</style>

<!-- ======== JAVASCRIPT ======== -->
<script>
const TOAST_ICONS = {
    success: `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
    error:   `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>`,
    warning: `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>`,
    info:    `<svg class="toast-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>`,
};

function showToast(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        ${TOAST_ICONS[type] || TOAST_ICONS.info}
        <span class="flex-1">${message}</span>
        <button class="toast-close" onclick="hideToast(this.parentElement)" aria-label="Fermer">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                 stroke="currentColor" stroke-width="2.5">
                <path d="M1 1l12 12M13 1L1 13"/>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    const timer = setTimeout(() => hideToast(toast), duration);
    toast.addEventListener('mouseenter', () => clearTimeout(timer));
    toast.addEventListener('mouseleave', () => setTimeout(() => hideToast(toast), 1500));
    toast.addEventListener('click', (e) => {
        if (!e.target.closest('.toast-close')) hideToast(toast);
    });
}

function hideToast(toast) {
    if (!toast || toast.classList.contains('hiding')) return;
    toast.classList.add('hiding');
    toast.addEventListener('animationend', () => toast.remove(), { once: true });
}

// API globale
window.Toast = {
    success : (msg, d) => showToast(msg, 'success', d),
    error   : (msg, d) => showToast(msg, 'error',   d),
    warning : (msg, d) => showToast(msg, 'warning',  d),
    info    : (msg, d) => showToast(msg, 'info',     d),
};
</script>