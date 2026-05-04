<?php
// ============================================================
//  models/session_model.php
//  Toutes les requetes SQL liees aux sessions de tutorat
// ============================================================


// ------------------------------------------------------------
//  Sessions a venir pour un utilisateur (mentor ou apprenant)
// ------------------------------------------------------------
function get_sessions_a_venir($user_id, $limite = 5) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT vsd.*
        FROM vue_sessions_details vsd
        WHERE (vsd.mentor_id = :uid OR vsd.apprenant_id = :uid2)
          AND vsd.statut IN ('en_attente', 'confirmee')
          AND vsd.date_session >= CURDATE()
        ORDER BY vsd.date_session ASC, vsd.heure_debut ASC
        LIMIT :limite
    ");
    $stmt->bindValue(':uid',    $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':uid2',   $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite,  PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Sessions en attente de confirmation pour un etudiant
// ------------------------------------------------------------
function get_sessions_en_attente_etudiant($apprenant_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT vsd.*
        FROM vue_sessions_details vsd
        WHERE vsd.apprenant_id = :uid
          AND vsd.statut = 'en_attente'
        ORDER BY vsd.date_session ASC
    ");
    $stmt->execute([':uid' => $apprenant_id]);
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Sessions en attente de confirmation pour un mentor
// ------------------------------------------------------------
function get_demandes_en_attente_mentor($mentor_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT vsd.*
        FROM vue_sessions_details vsd
        WHERE vsd.mentor_id = :uid
          AND vsd.statut    = 'en_attente'
        ORDER BY vsd.date_session ASC
    ");
    $stmt->execute([':uid' => $mentor_id]);
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Toutes les sessions d un utilisateur avec filtres
// ------------------------------------------------------------
function get_sessions_utilisateur($user_id, $statut = null) {
    $pdo    = get_pdo();
    $params = [':uid' => $user_id, ':uid2' => $user_id];
    $filtre = '';

    if ($statut) {
        $filtre = " AND vsd.statut = :statut";
        $params[':statut'] = $statut;
    }

    $stmt = $pdo->prepare("
        SELECT vsd.*
        FROM vue_sessions_details vsd
        WHERE (vsd.mentor_id = :uid OR vsd.apprenant_id = :uid2)
        $filtre
        ORDER BY vsd.date_session DESC, vsd.heure_debut DESC
    ");
    $stmt->execute($params);
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Recuperer une session par son ID
// ------------------------------------------------------------
function get_session_par_id($id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT * FROM vue_sessions_details WHERE id = :id LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}


// ------------------------------------------------------------
//  Creer une nouvelle session (reservation)
// ------------------------------------------------------------
function creer_session($mentor_id, $apprenant_id, $disponibilite_id, $matiere_id,
                        $date_session, $heure_debut, $heure_fin, $mode_session) {
    $pdo = get_pdo();

    // Verifie le chevauchement horaire pour l apprenant
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM sessions
        WHERE apprenant_id = :aid
          AND date_session  = :date
          AND statut NOT IN ('annulee')
          AND (heure_debut < :hfin AND heure_fin > :hdebut)
    ");
    $stmt->execute([
        ':aid'    => $apprenant_id,
        ':date'   => $date_session,
        ':hdebut' => $heure_debut,
        ':hfin'   => $heure_fin,
    ]);
    if ($stmt->fetchColumn() > 0) {
        return ['erreur' => "Tu as deja une session sur ce creneau horaire."];
    }

    // Verifie le chevauchement pour le mentor
    $stmt2 = $pdo->prepare("
        SELECT COUNT(*) FROM sessions
        WHERE mentor_id   = :mid
          AND date_session = :date
          AND statut NOT IN ('annulee')
          AND (heure_debut < :hfin AND heure_fin > :hdebut)
    ");
    $stmt2->execute([
        ':mid'    => $mentor_id,
        ':date'   => $date_session,
        ':hdebut' => $heure_debut,
        ':hfin'   => $heure_fin,
    ]);
    if ($stmt2->fetchColumn() > 0) {
        return ['erreur' => "Le mentor n est plus disponible sur ce creneau."];
    }

    // Insere la session
    $stmt3 = $pdo->prepare("
        INSERT INTO sessions
            (mentor_id, apprenant_id, disponibilite_id, matiere_id,
             date_session, heure_debut, heure_fin, mode_session, statut)
        VALUES
            (:mid, :aid, :did, :mat, :date, :hdebut, :hfin, :mode, 'en_attente')
    ");
    $stmt3->execute([
        ':mid'    => $mentor_id,
        ':aid'    => $apprenant_id,
        ':did'    => $disponibilite_id,
        ':mat'    => $matiere_id,
        ':date'   => $date_session,
        ':hdebut' => $heure_debut,
        ':hfin'   => $heure_fin,
        ':mode'   => $mode_session,
    ]);

    // Marque le creneau comme reserve
    if ($disponibilite_id) {
        $pdo->prepare("UPDATE disponibilites SET est_reservee = 1 WHERE id = :id")
            ->execute([':id' => $disponibilite_id]);
    }

    return ['id' => $pdo->lastInsertId()];
}


// ------------------------------------------------------------
//  Initier une conversation automatique apres confirmation
// ------------------------------------------------------------
function initier_conversation_apres_confirmation($session_id, $mentor_id, $apprenant_id) {
    $pdo = get_pdo();
    
    // Verifier si une conversation existe deja
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM messages 
        WHERE (envoyeur_id = :m AND destinataire_id = :a)
           OR (envoyeur_id = :a AND destinataire_id = :m)
    ");
    $stmt->execute([':m' => $mentor_id, ':a' => $apprenant_id]);
    $conversation_existe = $stmt->fetchColumn() > 0;
    
    if (!$conversation_existe) {
        // Recuperer les infos pour personnaliser le message
        $stmtSess = $pdo->prepare("
            SELECT vsd.matiere_nom, vsd.date_session, vsd.heure_debut, vsd.heure_fin
            FROM vue_sessions_details vsd
            WHERE vsd.id = :sid
        ");
        $stmtSess->execute([':sid' => $session_id]);
        $session = $stmtSess->fetch();
        
        $message_bienvenue = "🎉 **Votre session a été confirmée !**\n\n" .
                             "📚 **Matière :** " . ($session['matiere_nom'] ?? 'la matiere') . "\n" .
                             "📅 **Date :** " . date('d/m/Y', strtotime($session['date_session'])) . "\n" .
                             "⏰ **Horaire :** " . date('H:i', strtotime($session['heure_debut'])) . " - " . date('H:i', strtotime($session['heure_fin'])) . "\n\n" .
                             "💬 Vous pouvez utiliser ce chat pour :\n" .
                             "• Discuter des details de la session\n" .
                             "• Partager des liens ou documents\n" .
                             "• Poser vos questions\n\n" .
                             "Bonne preparation ! 📚";
        
        $stmt = $pdo->prepare("
            INSERT INTO messages (envoyeur_id, destinataire_id, contenu, date_envoi, est_systeme)
            VALUES (:mentor, :apprenant, :contenu, NOW(), 1)
        ");
        $stmt->execute([
            ':mentor' => $mentor_id,
            ':apprenant' => $apprenant_id,
            ':contenu' => $message_bienvenue
        ]);
        
        return true;
    }
    return false;
}


// ------------------------------------------------------------
//  Confirmer une session (mentor) + CREATION CONVERSATION AUTO
// ------------------------------------------------------------
function confirmer_session($session_id, $mentor_id, $lien_session = null) {
    $pdo = get_pdo();
    
    // Recuperer l'apprenant avant de confirmer
    $stmt = $pdo->prepare("SELECT apprenant_id FROM sessions WHERE id = :id AND mentor_id = :mid");
    $stmt->execute([':id' => $session_id, ':mid' => $mentor_id]);
    $session = $stmt->fetch();
    
    if (!$session) return false;
    
    $apprenant_id = $session['apprenant_id'];
    
    // Confirmer la session
    $stmt = $pdo->prepare("
        UPDATE sessions
        SET statut       = 'confirmee',
            lien_session = :lien
        WHERE id        = :id
          AND mentor_id = :mid
          AND statut    = 'en_attente'
    ");
    $stmt->execute([
        ':lien' => $lien_session,
        ':id'   => $session_id,
        ':mid'  => $mentor_id
    ]);
    
    // 🔥 CREER LA CONVERSATION AUTOMATIQUEMENT
    initier_conversation_apres_confirmation($session_id, $mentor_id, $apprenant_id);
    
    // Notifications
    $mentor_info = $pdo->prepare("SELECT prenom, nom FROM utilisateurs WHERE id = :id");
    $mentor_info->execute([':id' => $mentor_id]);
    $mentor = $mentor_info->fetch();
    
    creer_notification(
        $apprenant_id,
        'session_confirmee',
        '✅ Session confirmée',
        'Votre session a été confirmée par ' . $mentor['prenom'] . ' ! Vous pouvez maintenant discuter.',
        '/?url=message/conversation&user_id=' . $mentor_id
    );
    
    creer_notification(
        $mentor_id,
        'session_confirmee',
        '✅ Session confirmée',
        'Vous avez confirmé la session. Vous pouvez maintenant discuter avec votre étudiant.',
        '/?url=message/conversation&user_id=' . $apprenant_id
    );
    
    return true;
}


// ------------------------------------------------------------
//  Annuler une session (mentor OU apprenant)
// ------------------------------------------------------------
function annuler_session($session_id, $user_id, $motif = null) {
    $pdo = get_pdo();

    $stmt = $pdo->prepare("SELECT * FROM sessions WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $session_id]);
    $session = $stmt->fetch();

    if (!$session) return false;

    if ($session['mentor_id'] != $user_id && $session['apprenant_id'] != $user_id) {
        return false;
    }

    $heure_session      = strtotime($session['date_session'] . ' ' . $session['heure_debut']);
    $est_tardive        = ($heure_session - time()) < (2 * 3600) ? 1 : 0;
    $est_tardive_mentor = ($session['mentor_id'] == $user_id) ? $est_tardive : 0;

    $pdo->prepare("
        UPDATE sessions
        SET statut             = 'annulee',
            motif_annulation   = :motif,
            annulation_tardive = :tardive
        WHERE id = :id
    ")->execute([
        ':motif'   => $motif,
        ':tardive' => $est_tardive_mentor,
        ':id'      => $session_id,
    ]);

    // Libere le creneau si annulation par le mentor
    if ($session['mentor_id'] == $user_id && $session['disponibilite_id']) {
        $pdo->prepare("UPDATE disponibilites SET est_reservee = 0 WHERE id = :id")
            ->execute([':id' => $session['disponibilite_id']]);
    }

    return true;
}


// ------------------------------------------------------------
//  Marquer les sessions terminees automatiquement
// ------------------------------------------------------------
function marquer_sessions_terminees() {
    $pdo = get_pdo();
    $pdo->prepare("
        UPDATE sessions
        SET statut = 'terminee'
        WHERE statut = 'confirmee'
          AND CONCAT(date_session, ' ', heure_fin) < NOW()
    ")->execute();
}


// ------------------------------------------------------------
//  Statistiques pour le dashboard etudiant
// ------------------------------------------------------------
function get_stats_etudiant($user_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT
            SUM(statut = 'terminee')                                AS total_sessions,
            SUM(statut IN ('en_attente','confirmee')
                AND date_session >= CURDATE())                       AS sessions_a_venir,
            COUNT(DISTINCT IF(mentor_id <> :uid, mentor_id, NULL))  AS mentors_contactes,
            (SELECT COUNT(*) FROM evaluations
             WHERE apprenant_id = :uid2)                            AS evaluations
        FROM sessions
        WHERE apprenant_id = :uid3
    ");
    $stmt->execute([':uid' => $user_id, ':uid2' => $user_id, ':uid3' => $user_id]);
    return $stmt->fetch();
}


// ------------------------------------------------------------
//  Statistiques pour le dashboard mentor
// ------------------------------------------------------------
function get_stats_mentor($mentor_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT
            SUM(statut = 'terminee')                          AS sessions_realisees,
            SUM(statut = 'en_attente')                        AS demandes_en_attente,
            SUM(statut IN ('en_attente','confirmee')
                AND date_session >= CURDATE())                 AS sessions_a_venir,
            (SELECT note_moyenne   FROM mentors_profils
             WHERE utilisateur_id = :mid2)                    AS note_moyenne,
            (SELECT nb_evaluations FROM mentors_profils
             WHERE utilisateur_id = :mid3)                    AS nb_evaluations
        FROM sessions
        WHERE mentor_id = :mid
    ");
    $stmt->execute([':mid' => $mentor_id, ':mid2' => $mentor_id, ':mid3' => $mentor_id]);
    return $stmt->fetch();
}


// ============================================================
//  NOUVELLES FONCTIONS
// ============================================================


// ------------------------------------------------------------
//  Refuser une demande (mentor uniquement, statut en_attente)
//  Libere le creneau pour d autres etudiants
// ------------------------------------------------------------
function refuser_session($session_id, $mentor_id, $motif = '') {
    $pdo = get_pdo();

    $stmt = $pdo->prepare("
        SELECT * FROM sessions
        WHERE id = :id AND mentor_id = :mid AND statut = 'en_attente'
        LIMIT 1
    ");
    $stmt->execute([':id' => $session_id, ':mid' => $mentor_id]);
    $session = $stmt->fetch();

    if (!$session) return false;

    $pdo->prepare("
        UPDATE sessions
        SET statut = 'annulee', motif_annulation = :motif
        WHERE id   = :id
    ")->execute([':motif' => trim($motif), ':id' => $session_id]);

    if ($session['disponibilite_id']) {
        $pdo->prepare("UPDATE disponibilites SET est_reservee = 0 WHERE id = :id")
            ->execute([':id' => $session['disponibilite_id']]);
    }

    return true;
}


// ------------------------------------------------------------
//  Cree une notification in-app pour un utilisateur
// ------------------------------------------------------------
function creer_notification($user_id, $type, $titre, $contenu, $lien = '') {
    try {
        $pdo = get_pdo();
        $pdo->prepare("
            INSERT INTO notifications
                (utilisateur_id, type, titre, contenu, lien, lu, created_at)
            VALUES
                (:uid, :type, :titre, :contenu, :lien, 0, NOW())
        ")->execute([
            ':uid'     => $user_id,
            ':type'    => $type,
            ':titre'   => $titre,
            ':contenu' => $contenu,
            ':lien'    => $lien,
        ]);
    } catch (Exception $e) {
        // Silencieux si la table notifications n existe pas encore
    }
}


// ------------------------------------------------------------
//  Recupere un creneau par ID en verifiant le mentor
//  Utilisee par reserver_controller
// ------------------------------------------------------------
function get_disponibilite_par_id_par_mentor($dispo_id, $mentor_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT d.*, ma.nom AS matiere_nom
        FROM disponibilites d
        INNER JOIN matieres ma ON ma.id = d.matiere_id
        WHERE d.id        = :id
          AND d.mentor_id = :mentor
        LIMIT 1
    ");
    $stmt->execute([':id' => $dispo_id, ':mentor' => $mentor_id]);
    return $stmt->fetch();
}


// ------------------------------------------------------------
//  Historique des sessions d un mentor (hors en_attente)
//  Utilisee par demandes_controller
// ------------------------------------------------------------
function get_historique_sessions_mentor($mentor_id, $limite = 15) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT
            s.id,
            s.date_session,
            s.heure_debut,
            s.heure_fin,
            s.statut,
            s.mode_session,
            s.lien_session,
            CONCAT(u.prenom, ' ', u.nom) AS apprenant_nom_complet,
            u.photo                      AS apprenant_photo,
            ma.nom                       AS matiere_nom
        FROM sessions s
        INNER JOIN utilisateurs u  ON u.id  = s.apprenant_id
        INNER JOIN matieres    ma  ON ma.id = s.matiere_id
        WHERE s.mentor_id = :mid
          AND s.statut   != 'en_attente'
        ORDER BY s.date_session DESC, s.heure_debut DESC
        LIMIT :lim
    ");
    $stmt->bindValue(':mid', $mentor_id, PDO::PARAM_INT);
    $stmt->bindValue(':lim', $limite,    PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}