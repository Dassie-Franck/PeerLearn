<?php
// ============================================================
//  includes/auth_check.php
//  A inclure en haut de chaque controller protege
// ============================================================

if (!is_logged_in()) {
    set_error("Vous devez etre connecte pour acceder a cette page.");
    redirect_to('login');
}
