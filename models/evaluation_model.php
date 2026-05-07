<?php
// ============================================================
//  models/evaluation_model.php
// ============================================================

/**
 * Vérifie si une session a déjà été évaluée
 */
function session_deja_evaluee($session_id, $apprenant_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM evaluations WHERE session_id = :sid
    ");
    $stmt->execute([':sid' => $session_id]);
    return (int)$stmt->fetchColumn() > 0;
}

/**
 * Crée une évaluation
 */
function creer_evaluation($session_id, $mentor_id, $apprenant_id, $note, $commentaire) {
    $pdo = get_pdo();

    // Sécurité anti-doublon
    if (session_deja_evaluee($session_id, $apprenant_id)) {
        return ['erreur' => 'Vous avez déjà évalué cette session.'];
    }

    // Sécurité anti-auto-évaluation
    if ($mentor_id == $apprenant_id) {
        return ['erreur' => 'Vous ne pouvez pas vous auto-évaluer.'];
    }

    if ($note < 1 || $note > 5) {
        return ['erreur' => 'La note doit être comprise entre 1 et 5.'];
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO evaluations
                (session_id, mentor_id, apprenant_id, note, commentaire, visible, created_at)
            VALUES
                (:sid, :mid, :aid, :note, :comm, 1, NOW())
        ");
        $stmt->execute([
            ':sid'  => $session_id,
            ':mid'  => $mentor_id,
            ':aid'  => $apprenant_id,
            ':note' => $note,
            ':comm' => $commentaire,
        ]);

        mettre_a_jour_note_mentor($mentor_id);

        // Notification mentor
        if (function_exists('creer_notification')) {
            creer_notification(
                $mentor_id,
                'nouvelle_evaluation',
                '⭐ Nouvelle évaluation',
                'Un étudiant vous a noté ' . $note . '/5.',
                '/mentor'
            );
        }

        return ['success' => true, 'id' => $pdo->lastInsertId()];

    } catch (PDOException $e) {
        error_log('Erreur évaluation : ' . $e->getMessage());
        return ['erreur' => 'Erreur technique. Veuillez réessayer.'];
    }
}

/**
 * Met à jour la note moyenne du mentor
 */
function mettre_a_jour_note_mentor($mentor_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT ROUND(AVG(note), 1) AS moyenne, COUNT(*) AS total
        FROM evaluations
        WHERE mentor_id = :mid AND visible = 1
    ");
    $stmt->execute([':mid' => $mentor_id]);
    $res = $stmt->fetch();

    $pdo->prepare("
        UPDATE mentors_profils
        SET note_moyenne   = :note,
            nb_evaluations = :nb
        WHERE utilisateur_id = :mid
    ")->execute([
        ':note' => $res['moyenne'] ?? 0,
        ':nb'   => $res['total']   ?? 0,
        ':mid'  => $mentor_id,
    ]);
}

/**
 * Évaluations visibles d'un mentor (fiche publique)
 */
function get_evaluations_mentor($mentor_id) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT
            ev.id,
            ev.note,
            ev.commentaire,
            ev.reponse_mentor,
            ev.created_at,
            u.prenom,
            u.nom,
            u.photo,
            ma.nom AS matiere_nom
        FROM evaluations ev
        INNER JOIN utilisateurs u  ON u.id  = ev.apprenant_id
        INNER JOIN sessions    s   ON s.id  = ev.session_id
        LEFT  JOIN matieres    ma  ON ma.id = s.matiere_id
        WHERE ev.mentor_id = :mid
          AND ev.visible   = 1
        ORDER BY ev.created_at DESC
        LIMIT 10
    ");
    $stmt->execute([':mid' => $mentor_id]);
    return $stmt->fetchAll();
}

/**
 * Réponse du mentor à une évaluation
 */
function repondre_evaluation($evaluation_id, $mentor_id, $reponse) {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE evaluations
        SET reponse_mentor = :reponse
        WHERE id        = :id
          AND mentor_id = :mid
    ");
    $stmt->execute([
        ':reponse' => trim($reponse),
        ':id'      => $evaluation_id,
        ':mid'     => $mentor_id,
    ]);
    return $stmt->rowCount() > 0;
}
