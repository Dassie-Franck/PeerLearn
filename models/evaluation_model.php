<?php
// ============================================================
//  models/evaluation_model.php
// ============================================================


// Sessions terminées qu'un étudiant peut encore évaluer
function get_sessions_evaluables(int $apprenant_id): array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT s.id, s.date_session, s.heure_debut, s.heure_fin,
               s.mode_session, s.mentor_id,
               CONCAT(u.prenom, ' ', u.nom) AS mentor_nom,
               u.photo                      AS mentor_photo,
               ma.nom                       AS matiere_nom
        FROM sessions s
        INNER JOIN utilisateurs u  ON u.id  = s.mentor_id
        INNER JOIN matieres    ma  ON ma.id = s.matiere_id
        WHERE s.apprenant_id = :aid
          AND s.statut       = 'terminee'
          AND NOT EXISTS (
              SELECT 1 FROM evaluations ev
              WHERE ev.session_id = s.id
          )
        ORDER BY s.date_session DESC
    ");
    $stmt->execute([':aid' => $apprenant_id]);
    return $stmt->fetchAll();
}


// Vérifie qu'une session est bien évaluable par cet étudiant
function get_session_evaluable(int $session_id, int $apprenant_id): array|false {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT s.id, s.mentor_id, s.matiere_id, s.date_session,
               CONCAT(u.prenom, ' ', u.nom) AS mentor_nom,
               u.photo                      AS mentor_photo,
               ma.nom                       AS matiere_nom
        FROM sessions s
        INNER JOIN utilisateurs u  ON u.id  = s.mentor_id
        INNER JOIN matieres    ma  ON ma.id = s.matiere_id
        WHERE s.id           = :sid
          AND s.apprenant_id = :aid
          AND s.statut       = 'terminee'
          AND NOT EXISTS (
              SELECT 1 FROM evaluations ev WHERE ev.session_id = s.id
          )
        LIMIT 1
    ");
    $stmt->execute([':sid' => $session_id, ':aid' => $apprenant_id]);
    return $stmt->fetch();
}


// Soumet une évaluation + met à jour note_moyenne du mentor
function soumettre_evaluation(int $session_id, int $apprenant_id, int $mentor_id,
                               int $note, string $commentaire): bool {
    $pdo = get_pdo();
    try {
        $pdo->beginTransaction();

        // Insère l'évaluation
        $pdo->prepare("
            INSERT INTO evaluations
                (session_id, apprenant_id, mentor_id, note, commentaire, visible, created_at)
            VALUES
                (:sid, :aid, :mid, :note, :comm, 1, NOW())
        ")->execute([
            ':sid'  => $session_id,
            ':aid'  => $apprenant_id,
            ':mid'  => $mentor_id,
            ':note' => $note,
            ':comm' => trim($commentaire),
        ]);

        // Recalcule note_moyenne et nb_evaluations dans mentors_profils
        $pdo->prepare("
            UPDATE mentors_profils
            SET note_moyenne   = (
                    SELECT ROUND(AVG(note), 2)
                    FROM evaluations
                    WHERE mentor_id = :mid AND visible = 1
                ),
                nb_evaluations = (
                    SELECT COUNT(*)
                    FROM evaluations
                    WHERE mentor_id = :mid2 AND visible = 1
                )
            WHERE utilisateur_id = :mid3
        ")->execute([':mid' => $mentor_id, ':mid2' => $mentor_id, ':mid3' => $mentor_id]);

        $pdo->commit();
        return true;

    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}


// Évaluations visibles d'un mentor (pour sa fiche publique)
function get_evaluations_mentor(int $mentor_id): array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT ev.note, ev.commentaire, ev.reponse_mentor, ev.created_at,
               u.prenom, u.nom, u.photo,
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


// Vérifie si un étudiant a déjà évalué une session
function a_deja_evalue(int $session_id): bool {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM evaluations WHERE session_id = :sid");
    $stmt->execute([':sid' => $session_id]);
    return (int)$stmt->fetchColumn() > 0;
}