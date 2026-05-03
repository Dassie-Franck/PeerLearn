<?php
// ============================================================
//  models/message_model.php
//  Toutes les requêtes pour la messagerie
// ============================================================

/**
 * Récupère toutes les conversations d'un utilisateur
 * Retourne : [interlocuteur_id, prenom, nom, photo, dernier_message, date_dernier_message, nb_non_lus, dernier_envoyeur_id]
 */
function get_conversations($user_id) {
    $pdo = get_pdo();
    
    $stmt = $pdo->prepare("
        SELECT 
            CASE 
                WHEN m.envoyeur_id = :uid THEN m.destinataire_id
                ELSE m.envoyeur_id
            END AS interlocuteur_id,
            u.id,
            u.prenom,
            u.nom,
            u.photo,
            m.contenu AS dernier_message,
            m.date_envoi AS date_dernier_message,
            m.envoyeur_id AS dernier_envoyeur_id,
            (SELECT COUNT(*) FROM messages 
             WHERE destinataire_id = :uid2 
               AND envoyeur_id = interlocuteur_id 
               AND lu = 0) AS nb_non_lus
        FROM messages m
        INNER JOIN utilisateurs u ON u.id = (
            CASE 
                WHEN m.envoyeur_id = :uid3 THEN m.destinataire_id
                ELSE m.envoyeur_id
            END
        )
        WHERE m.envoyeur_id = :uid4 OR m.destinataire_id = :uid5
        GROUP BY interlocuteur_id
        ORDER BY MAX(m.date_envoi) DESC
    ");
    
    $stmt->execute([
        ':uid' => $user_id,
        ':uid2' => $user_id,
        ':uid3' => $user_id,
        ':uid4' => $user_id,
        ':uid5' => $user_id
    ]);
    
    return $stmt->fetchAll();
}

/**
 * Compte les messages non lus pour un utilisateur
 */
function compter_messages_non_lus($user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM messages 
        WHERE destinataire_id = :uid AND lu = 0
    ");
    $stmt->execute([':uid' => $user_id]);
    return (int)$stmt->fetchColumn();
}

/**
 * Récupère la conversation entre deux utilisateurs
 */
function get_conversation($user_id, $interlocuteur_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT m.*, 
               u.prenom AS envoyeur_prenom, 
               u.nom AS envoyeur_nom
        FROM messages m
        INNER JOIN utilisateurs u ON u.id = m.envoyeur_id
        WHERE (m.envoyeur_id = :uid AND m.destinataire_id = :interlo)
           OR (m.envoyeur_id = :interlo AND m.destinataire_id = :uid2)
        ORDER BY m.date_envoi ASC
    ");
    $stmt->execute([
        ':uid' => $user_id,
        ':uid2' => $user_id,
        ':interlo' => $interlocuteur_id
    ]);
    return $stmt->fetchAll();
}

/**
 * Envoie un message
 */
function envoyer_message($envoyeur_id, $destinataire_id, $contenu, $fichier_joint = null) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        INSERT INTO messages (envoyeur_id, destinataire_id, contenu, fichier_joint, date_envoi)
        VALUES (:env, :dest, :contenu, :fichier, NOW())
    ");
    $stmt->execute([
        ':env' => $envoyeur_id,
        ':dest' => $destinataire_id,
        ':contenu' => $contenu,
        ':fichier' => $fichier_joint
    ]);
    return $pdo->lastInsertId();
}

/**
 * Marque les messages d'une conversation comme lus
 */
function marquer_messages_lus($user_id, $interlocuteur_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE messages 
        SET lu = 1, lu_le = NOW()
        WHERE destinataire_id = :uid 
          AND envoyeur_id = :interlo 
          AND lu = 0
    ");
    $stmt->execute([
        ':uid' => $user_id,
        ':interlo' => $interlocuteur_id
    ]);
}

/**
 * Récupère les nouveaux messages (pour polling AJAX)
 */
function get_nouveaux_messages($user_id, $interlocuteur_id, $dernier_id = 0) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT m.*, 
               u.prenom AS envoyeur_prenom, 
               u.nom AS envoyeur_nom
        FROM messages m
        INNER JOIN utilisateurs u ON u.id = m.envoyeur_id
        WHERE ((m.envoyeur_id = :uid AND m.destinataire_id = :interlo)
            OR (m.envoyeur_id = :interlo AND m.destinataire_id = :uid2))
          AND m.id > :dernier
        ORDER BY m.date_envoi ASC
    ");
    $stmt->execute([
        ':uid' => $user_id,
        ':uid2' => $user_id,
        ':interlo' => $interlocuteur_id,
        ':dernier' => $dernier_id
    ]);
    return $stmt->fetchAll();
}

/**
 * Signale un message (pour modération)
 */
function signaler_message($message_id, $user_id, $motif) {
    $pdo = get_pdo();
    
    $stmt = $pdo->prepare("
        UPDATE messages 
        SET signale = 1, motif_signalement = :motif
        WHERE id = :msg_id AND destinataire_id = :uid
    ");
    $stmt->execute([
        ':msg_id' => $message_id,
        ':uid' => $user_id,
        ':motif' => $motif
    ]);
}

/**
 * Supprime un message
 */
function supprimer_message($message_id, $user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        DELETE FROM messages 
        WHERE id = :msg_id 
          AND (envoyeur_id = :uid OR destinataire_id = :uid)
    ");
    $stmt->execute([
        ':msg_id' => $message_id,
        ':uid' => $user_id
    ]);
}

/**
 * Initialise une conversation système (message de bienvenue)
 */
function initier_conversation_systeme($expediteur_id, $destinataire_id, $titre, $message) {
    $pdo = get_pdo();
    
    $contenu = "🤖 **" . $titre . "**\n\n" . $message;
    
    $stmt = $pdo->prepare("
        INSERT INTO messages (envoyeur_id, destinataire_id, contenu, date_envoi, est_systeme)
        VALUES (:exp, :dest, :contenu, NOW(), 1)
    ");
    $stmt->execute([
        ':exp' => $expediteur_id,
        ':dest' => $destinataire_id,
        ':contenu' => $contenu
    ]);
    
    return $pdo->lastInsertId();
}