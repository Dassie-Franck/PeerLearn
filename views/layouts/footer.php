<?php // views/layouts/footer.php — styles CSS globaux ?>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
<style>
body       { font-family: 'DM Sans', sans-serif; }
h1,h2,h3,.font-syne { font-family: 'Syne', sans-serif; }

/* Couleurs */
:root { --violet:#5B4FE8; --teal:#0FC4A7; --ink:#0D0D14; }

/* Navigation sidebar */
.nav-link {
    display:flex; align-items:center; gap:12px;
    padding:10px 12px; border-radius:10px;
    font-size:14px; color:#9CA3AF; text-decoration:none;
    transition:background .15s, color .15s;
    border-left:3px solid transparent;
}
.nav-link:hover  { background:rgba(91,79,232,.1); color:var(--violet); }
.nav-link.active { background:rgba(91,79,232,.15); color:var(--violet);
                   border-left-color:var(--violet); padding-left:9px; }

/* Boutons */
.btn-primary {
    display:inline-block; background:var(--violet); color:#fff;
    padding:11px 20px; border-radius:12px; font-size:14px;
    font-weight:500; border:none; cursor:pointer;
    transition:opacity .2s; text-decoration:none; text-align:center;
}
.btn-primary:hover { opacity:.88; color:#fff; }

.btn-secondary {
    display:inline-block; background:#F3F4F6; color:#374151;
    padding:11px 20px; border-radius:12px; font-size:14px;
    font-weight:500; border:none; cursor:pointer;
    transition:background .2s; text-decoration:none; text-align:center;
}
.btn-secondary:hover { background:#E5E7EB; }

.btn-danger {
    display:inline-block; background:#FEF2F2; color:#991B1B;
    padding:11px 20px; border-radius:12px; font-size:14px;
    font-weight:500; border:none; cursor:pointer;
    transition:background .2s; text-decoration:none; text-align:center;
}
.btn-danger:hover { background:#FEE2E2; }

/* Inputs */
.input-field {
    width:100%; padding:12px 16px; border:1px solid #E5E7EB;
    border-radius:12px; font-size:14px; font-family:'DM Sans',sans-serif;
    color:#111827; background:#fff; outline:none;
    transition:border-color .2s, box-shadow .2s; box-sizing:border-box;
}
.input-field:focus {
    border-color:var(--violet);
    box-shadow:0 0 0 3px rgba(91,79,232,.12);
}

/* Cartes */
.card {
    background:#fff; border:1px solid #F3F4F6;
    border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,.05); padding:24px;
}

/* Badges statut */
.badge-success { background:#DCFCE7; color:#166534; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; display:inline-block; }
.badge-warning { background:#FEF9C3; color:#854D0E; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; display:inline-block; }
.badge-error   { background:#FEE2E2; color:#991B1B; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; display:inline-block; }
.badge-info    { background:#EFF6FF; color:#1E40AF; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; display:inline-block; }
.badge-gray    { background:#F3F4F6; color:#374151; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:500; display:inline-block; }
</style>