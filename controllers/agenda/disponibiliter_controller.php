<?php
// controllers/agenda/disponibilites_controller.php — F14

require_once BASE_PATH . '/models/disponibilite_model.php';
require_once BASE_PATH . '/models/matiere_model.php';

require_logged_in();
require_mentor();

$user_id         = $_SESSION['user_id'];
$disponibilites  = get_disponibilites_mentor($user_id);
$matieres_mentor = get_matieres_mentor($user_id);
$page_active     = 'disponibilites';

require_once BASE_PATH . '/views/agenda/disponibilites.php';