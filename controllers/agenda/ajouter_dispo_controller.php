<?php
// controllers/agenda/ajouter_dispo_controller.php — F12

require_once BASE_PATH . '/models/disponibilite_model.php';

require_logged_in();
require_mentor();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    set_error('Requete invalide.');
    redirect_to('disponibilites');
}

$mentor_id  = $_SESSION['user_id'];
$matiere_id = (int)  ($_POST['matiere_id'] ?? 0);
$date       = trim($_POST['date_dispo']    ?? '');
$debut      = trim($_POST['heure_debut']   ?? '');
$fin        = trim($_POST['heure_fin']     ?? '');
$mode       = trim($_POST['mode_session']  ?? 'en_ligne');

$erreurs = [];
if (!$matiere_id)          $erreurs[] = 'Selectionne une matiere.';
if (empty($date))          $erreurs[] = 'La date est obligatoire.';
if ($date < date('Y-m-d')) $erreurs[] = 'La date ne peut pas etre dans le passe.';
if (empty($debut))         $erreurs[] = 'L heure de debut est obligatoire.';
if (empty($fin))           $erreurs[] = 'L heure de fin est obligatoire.';
if (!empty($debut) && !empty($fin) && $fin <= $debut)
                           $erreurs[] = 'L heure de fin doit etre apres l heure de debut.';

if (!empty($erreurs)) {
    set_error(implode(' ', $erreurs));
    redirect_to('disponibilites');
}

$res = creer_disponibilite($mentor_id, $matiere_id, $date, $debut, $fin, $mode);

isset($res['erreur']) ? set_error($res['erreur']) : set_success('Creneau ajoute !');
redirect_to('disponibilites');