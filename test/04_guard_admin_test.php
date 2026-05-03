<?php
require_once 'bootstrap_test.php';

echo "<h2>Guard Admin</h2>";

// Simuler utilisateur NON admin
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'user';

requireAdmin();

echo "Si tu vois ceci → admin OK";