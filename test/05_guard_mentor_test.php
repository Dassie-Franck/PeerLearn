<?php
require_once 'bootstrap_test.php';

echo "<h2>Guard Mentor</h2>";

$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'user';
$_SESSION['user_est_mentor'] = 1;
$_SESSION['user_mentor_valide'] = 0; // pas validé

requireMentor();

echo "Si tu vois ceci → mentor validé OK";