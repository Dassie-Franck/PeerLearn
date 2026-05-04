<?php
// ============================================================
//  controllers/message/inbox_controller.php
//  Liste toutes les conversations de l'utilisateur connecté
//  GET : ?url=messages
// ============================================================

require_once BASE_PATH . '/config/session.php';
requireConnecte();

$pdo     = get_pdo();
$user_id = getUserId();

// ── Liste des conversations (dernier message par interlocuteur)
// On regroupe par "paire" d'utilisateurs et on prend le dernier msg
$stmt = $pdo->prepare("
    SELECT
        -- Interlocuteur
        IF(m.envoyeur_id = :uid, m.destinataire_id, m.envoyeur_id) AS interlocuteur_id,
        u.nom,
        u.prenom,
        u.photo,
        CONCAT(u.prenom, ' ', u.nom)  AS nom_complet,
        u.est_mentor,
        u.mentor_valide,

        -- Dernier message
        m.contenu      AS dernier_message,
        m.date_envoi   AS derniere_date,
        m.envoyeur_id,

        -- Nb messages non lus envoyés PAR l'interlocuteur
        SUM(
            CASE WHEN m2.destinataire_id = :uid2
                      AND m2.envoyeur_id  = IF(m.envoyeur_id = :uid3, m.destinataire_id, m.envoyeur_id)
                      AND m2.lu = 0
                 THEN 1 ELSE 0 END
        ) AS non_lus

    FROM messages m
    -- Joint pour avoir les infos de l'interlocuteur
    JOIN utilisateurs u
        ON u.id = IF(m.envoyeur_id = :uid4, m.destinataire_id, m.envoyeur_id)
    -- Joint pour compter les non lus
    LEFT JOIN messages m2
        ON (m2.envoyeur_id = IF(m.envoyeur_id = :uid5, m.destinataire_id, m.envoyeur_id)
            AND m2.destinataire_id = :uid6)

    WHERE (m.envoyeur_id = :uid7 OR m.destinataire_id = :uid8)

    -- Garder seulement le dernier message par paire
    AND m.id = (
        SELECT MAX(m3.id)
        FROM messages m3
        WHERE (m3.envoyeur_id     = :uid9  AND m3.destinataire_id = u.id)
           OR (m3.destinataire_id = :uid10 AND m3.envoyeur_id     = u.id)
    )

    GROUP BY interlocuteur_id, u.nom, u.prenom, u.photo, u.est_mentor,
             u.mentor_valide, m.contenu, m.date_envoi, m.envoyeur_id
    ORDER BY m.date_envoi DESC
");

$stmt->execute([
    ':uid'  => $user_id, ':uid2' => $user_id, ':uid3'  => $user_id,
    ':uid4' => $user_id, ':uid5' => $user_id, ':uid6'  => $user_id,
    ':uid7' => $user_id, ':uid8' => $user_id, ':uid9'  => $user_id,
    ':uid10'=> $user_id,
]);
$conversations = $stmt->fetchAll();

// ── Total non lus (pour badge navbar) ───────────────────────
$stmt2 = $pdo->prepare("
    SELECT COUNT(*) FROM messages
    WHERE destinataire_id = :uid AND lu = 0
");
$stmt2->execute([':uid' => $user_id]);
$total_non_lus = (int) $stmt2->fetchColumn();

$page_active = 'messages';
require_once BASE_PATH . '/views/messages/inbox.php';
