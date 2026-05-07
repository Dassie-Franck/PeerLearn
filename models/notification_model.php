<?php
// ============================================================
//  models/notification_model.php
//  Gestion des notifications
// ============================================================

/**
 * Crée une notification pour un utilisateur
 */
function creer_notifications($user_id, $type, $titre, $contenu, $lien = null) {
    // Vérifier que le type est valide
    $types_valides = [
        'nouvelle_reservation',
        'reservation_confirmee',
        'reservation_annulee',
        'nouveau_message',
        'nouvelle_evaluation',
        'profil_mentor_valide',
        'profil_mentor_rejete',
        'profil_suspendu',
        'rappel_session'
    ];
    
    if (!in_array($type, $types_valides)) {
        error_log("Type de notification invalide: " . $type);
        return false;
    }
    
    try {
        $pdo = get_pdo();
        $stmt = $pdo->prepare("
            INSERT INTO notifications (utilisateur_id, type, titre, contenu, lien, created_at)
            VALUES (:user_id, :type, :titre, :contenu, :lien, NOW())
        ");
        $stmt->execute([
            ':user_id' => $user_id,
            ':type' => $type,
            ':titre' => $titre,
            ':contenu' => $contenu,
            ':lien' => $lien
        ]);
        return $pdo->lastInsertId();
    } catch (Exception $e) {
        error_log("Erreur création notification: " . $e->getMessage());
        return false;
    }
}

/**
 * Récupère les notifications d'un utilisateur
 */
function get_notifications($user_id, $limit = 15) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * FROM notifications
        WHERE utilisateur_id = :user_id
        ORDER BY created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

/**
 * Compte les notifications non lues
 */
function compter_notifications_non_lues($user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM notifications
        WHERE utilisateur_id = :user_id AND lu = 0
    ");
    $stmt->execute([':user_id' => $user_id]);
    return (int)$stmt->fetchColumn();
}

/**
 * Marque une notification comme lue
 */
function marquer_notification_lue($notification_id, $user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE notifications
        SET lu = 1
        WHERE id = :id AND utilisateur_id = :user_id
    ");
    return $stmt->execute([':id' => $notification_id, ':user_id' => $user_id]);
}

/**
 * Marque toutes les notifications comme lues
 */
function marquer_toutes_notifications_lues($user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE notifications
        SET lu = 1
        WHERE utilisateur_id = :user_id AND lu = 0
    ");
    return $stmt->execute([':user_id' => $user_id]);
}

/**
 * Supprime une notification
 */
function supprimer_notification($notification_id, $user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        DELETE FROM notifications
        WHERE id = :id AND utilisateur_id = :user_id
    ");
    return $stmt->execute([':id' => $notification_id, ':user_id' => $user_id]);
}

/**
 * Récupère les dernières notifications non lues (pour polling)
 */
function get_dernieres_notifications_non_lues($user_id, $limit = 5) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * FROM notifications
        WHERE utilisateur_id = :user_id AND lu = 0
        ORDER BY created_at DESC
        LIMIT :limit
    ");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}