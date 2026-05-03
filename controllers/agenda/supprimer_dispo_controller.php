<?php
// controllers/agenda/supprimer_dispo_controller.php — F13

require_once BASE_PATH . '/models/disponibilite_model.php';

require_logged_in();
require_mentor();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    set_error('Requete invalide.');
    redirect_to('disponibilites');
}

$id        = (int) ($_POST['dispo_id'] ?? 0);
$mentor_id = $_SESSION['user_id'];

if (!$id) {
    set_error('Creneau introuvable.');
    redirect_to('disponibilites');
}

$ok = supprimer_disponibilite($id, $mentor_id);
$ok ? set_success('Creneau supprime.') : set_error('Impossible de supprimer ce creneau (deja reserve).');
redirect_to('disponibilites');