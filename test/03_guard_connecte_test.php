<?php
require_once 'bootstrap_test.php';

echo "<h2>Guard Connecté</h2>";

// Déconnecter volontairement
unset($_SESSION['user_id']);

requireConnecte();

echo "Si tu vois ceci → utilisateur connecté";
