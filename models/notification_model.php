<?php
// ============================================================
//  notification_model.php
//  Requetes SQL liees aux notifications in-app
//  Chemin : models/notification_model.php
// ============================================================

// ------------------------------------------------------------
//  Creer une notification
// ------------------------------------------------------------
if (!function_exists('creer_notification')) {
    function creer_notification($utilisateur_id, $type, $titre, $contenu, $lien = null) {
        $pdo  = get_pdo();
        $stmt = $pdo->prepare("
            INSERT INTO notifications (utilisateur_id, type, titre, contenu, lien)
            VALUES (:uid, :type, :titre, :contenu, :lien)
        ");
        $stmt->execute([
            ':uid'     => $utilisateur_id,
            ':type'    => $type,
            ':titre'   => $titre,
            ':contenu' => $contenu,
            ':lien'    => $lien,
        ]);
    }
}


// ------------------------------------------------------------
//  Compter les notifications non lues
// ------------------------------------------------------------
function compter_notifications_non_lues($utilisateur_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM notifications
        WHERE utilisateur_id = :uid AND lu = 0
    ");
    $stmt->execute([':uid' => $utilisateur_id]);
    return (int) $stmt->fetchColumn();
}


// ------------------------------------------------------------
//  Recuperer les notifications d un utilisateur
// ------------------------------------------------------------
function get_notifications($utilisateur_id, $limite = 20) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * FROM notifications
        WHERE utilisateur_id = :uid
        ORDER BY created_at DESC
        LIMIT :limite
    ");
    $stmt->bindValue(':uid',    $utilisateur_id, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite,         PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Marquer une notification comme lue
// ------------------------------------------------------------
function marquer_notification_lue($id, $utilisateur_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE notifications SET lu = 1
        WHERE id = :id AND utilisateur_id = :uid
    ");
    $stmt->execute([':id' => $id, ':uid' => $utilisateur_id]);
}


// ------------------------------------------------------------
//  Marquer toutes les notifications comme lues
// ------------------------------------------------------------
function marquer_toutes_lues($utilisateur_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE notifications SET lu = 1 WHERE utilisateur_id = :uid
    ");
    $stmt->execute([':uid' => $utilisateur_id]);
}