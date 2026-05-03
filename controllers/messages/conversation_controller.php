<?php
// ============================================================
//  controllers/message/conversation_controller.php
//  Affiche la conversation entre user connecté et interlocuteur
//  GET : ?url=conversation&avec=X
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireConnecte();

$pdo             = get_pdo();
$user_id         = getUserId();
$interlocuteur_id = (int) ($_GET['avec'] ?? 0);

if (!$interlocuteur_id || $interlocuteur_id === $user_id) {
    redirect_to('messages');
}

// ── Infos de l'interlocuteur ────────────────────────────────
$stmt = $pdo->prepare("
    SELECT
        u.id, u.nom, u.prenom, u.photo,
        CONCAT(u.prenom, ' ', u.nom) AS nom_complet,
        u.est_mentor, u.mentor_valide,
        mp.statut_dispo, mp.note_moyenne, mp.nb_evaluations
    FROM utilisateurs u
    LEFT JOIN mentors_profils mp ON mp.utilisateur_id = u.id
    WHERE u.id = :id AND u.statut = 'actif'
    LIMIT 1
");
$stmt->execute([':id' => $interlocuteur_id]);
$interlocuteur = $stmt->fetch();

if (!$interlocuteur) {
    setToast('Utilisateur introuvable.', 'error');
    redirect_to('messages');
}

// ── Charger les messages de la conversation ─────────────────
$stmt = $pdo->prepare("
    SELECT
        m.id,
        m.envoyeur_id,
        m.contenu,
        m.fichier_joint,
        m.lu,
        m.signale,
        m.date_envoi
    FROM messages m
    WHERE (m.envoyeur_id      = :uid  AND m.destinataire_id = :iid)
       OR (m.destinataire_id  = :uid2 AND m.envoyeur_id     = :iid2)
    ORDER BY m.date_envoi ASC
");
$stmt->execute([
    ':uid'  => $user_id,         ':iid'  => $interlocuteur_id,
    ':uid2' => $user_id,         ':iid2' => $interlocuteur_id,
]);
$messages = $stmt->fetchAll();

// ── Marquer comme lus les messages reçus ────────────────────
$pdo->prepare("
    UPDATE messages
    SET lu = 1
    WHERE destinataire_id = :uid
      AND envoyeur_id     = :iid
      AND lu = 0
")->execute([':uid' => $user_id, ':iid' => $interlocuteur_id]);

// ── Dernier id pour le polling JS ───────────────────────────
$last_id = !empty($messages) ? (int) end($messages)['id'] : 0;

$page_active = 'messages';
require_once BASE_PATH . '/views/messages/conversation.php';