<?php
require_once 'bootstrap_test.php';

echo "<h2>Test Auth Functions</h2>";

// Simuler utilisateur connecté
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';
$_SESSION['user_nom'] = 'Doe';
$_SESSION['user_prenom'] = 'John';
$_SESSION['user_email'] = 'john@test.com';
$_SESSION['user_est_mentor'] = 1;
$_SESSION['user_mentor_valide'] = 1;
$_SESSION['user_photo'] = 'photo.png';

echo "<pre>";

var_dump(estConnecte());
var_dump(getUserId());
var_dump(getUserRole());
var_dump(estAdmin());
var_dump(estMentorValide());
print_r(getUser());

echo "</pre>";
