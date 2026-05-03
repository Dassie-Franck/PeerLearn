<?php
// ============================================================
//  models/mentor_model.php
// ============================================================
//  NB: get_matieres_mentor()  → définie dans matiere_model.php
//      get_matieres_actives() → définie dans matiere_model.php
//                               (alias : get_toutes_matieres)
// ============================================================


// ------------------------------------------------------------
//  Profil mentor d'un utilisateur (espace mentor)
// ------------------------------------------------------------
function get_profil_mentor(int $user_id): array|false {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT mp.*, u.nom, u.prenom, u.email, u.photo,
               u.statut, u.est_mentor, u.mentor_valide
        FROM mentors_profils mp
        INNER JOIN utilisateurs u ON u.id = mp.utilisateur_id
        WHERE mp.utilisateur_id = :uid
        LIMIT 1
    ");
    $stmt->execute([':uid' => $user_id]);
    return $stmt->fetch();
}


// ------------------------------------------------------------
//  Mettre à jour bio et expérience du mentor
// ------------------------------------------------------------
function mettre_a_jour_profil_mentor(int $user_id, string $bio, string $experience): void {
    $pdo = get_pdo();
    $pdo->prepare("
        UPDATE mentors_profils
        SET bio = :bio, experience = :experience
        WHERE utilisateur_id = :uid
    ")->execute([':bio' => $bio, ':experience' => $experience, ':uid' => $user_id]);
}


// ------------------------------------------------------------
//  Changer le statut de disponibilité du mentor
// ------------------------------------------------------------
function changer_statut_dispo(int $user_id, string $statut): void {
    $pdo = get_pdo();
    $pdo->prepare("
        UPDATE mentors_profils
        SET statut_dispo = :statut
        WHERE utilisateur_id = :uid
    ")->execute([':statut' => $statut, ':uid' => $user_id]);
}


// ------------------------------------------------------------
//  Fiche publique d'un mentor
//  Utilisée par fiche_mentor_controller et reserver_controller
// ------------------------------------------------------------
function get_fiche_mentor(int $mentor_id): array|false {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT u.id, u.nom, u.prenom, u.photo,
               mp.bio, mp.experience, mp.note_moyenne,
               mp.nb_evaluations, mp.statut_dispo
        FROM utilisateurs u
        INNER JOIN mentors_profils mp ON mp.utilisateur_id = u.id
        WHERE u.id          = :id
          AND u.est_mentor    = 1
          AND u.mentor_valide = 1
          AND u.statut        = 'actif'
        LIMIT 1
    ");
    $stmt->execute([':id' => $mentor_id]);
    return $stmt->fetch();
}


// ------------------------------------------------------------
//  Créneaux disponibles d'un mentor (futurs, non réservés)
//  Utilisée par fiche_mentor_controller
// ------------------------------------------------------------
function get_disponibilites_mentor_profile(int $mentor_id): array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT d.id, d.date_dispo, d.heure_debut, d.heure_fin,
               d.mode_session, d.matiere_id,
               ma.nom AS matiere_nom
        FROM disponibilites d
        LEFT JOIN matieres ma ON ma.id = d.matiere_id
        WHERE d.mentor_id    = :id
          AND d.est_reservee = 0
          AND d.date_dispo   >= CURDATE()
        ORDER BY d.date_dispo ASC, d.heure_debut ASC
    ");
    $stmt->execute([':id' => $mentor_id]);
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Évaluations publiques d'un mentor
//  Utilisée par fiche_mentor_controller
// ------------------------------------------------------------
// function get_evaluations_mentor(int $mentor_id): array {
//     $pdo  = get_pdo();
//     $stmt = $pdo->prepare("
//         SELECT ev.note, ev.commentaire, ev.reponse_mentor, ev.created_at,
//                u.prenom, u.nom, u.photo,
//                ma.nom AS matiere_nom
//         FROM evaluations ev
//         INNER JOIN utilisateurs u  ON u.id  = ev.apprenant_id
//         INNER JOIN sessions    s   ON s.id  = ev.session_id
//         LEFT  JOIN matieres    ma  ON ma.id = s.matiere_id
//         WHERE ev.mentor_id = :id
//           AND ev.visible   = 1
//         ORDER BY ev.created_at DESC
//         LIMIT 10
//     ");
//     $stmt->execute([':id' => $mentor_id]);
//     return $stmt->fetchAll();
// }


// ------------------------------------------------------------
//  Récupère un créneau par ID en vérifiant qu'il appartient
//  au bon mentor — utilisée par reserver_controller
// ------------------------------------------------------------
function get_disponibilite_par_id(int $dispo_id, int $mentor_id): array|false {
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
//  Liste des mentors validés (pour la recherche)
// ------------------------------------------------------------
function get_mentors_valides(?int $matiere_id = null, ?float $note_min = null, ?string $recherche = null): array {
    $pdo    = get_pdo();
    $params = [];
    $where  = [];

    $sql = "
        SELECT u.id, u.nom, u.prenom, u.photo,
               mp.bio, mp.note_moyenne, mp.nb_evaluations, mp.statut_dispo,
               IFNULL((
                   SELECT COUNT(*) FROM sessions s
                   WHERE s.mentor_id = u.id AND s.statut = 'terminee'
               ), 0) AS total_sessions,
               GROUP_CONCAT(m.nom ORDER BY m.nom SEPARATOR ', ') AS matieres
        FROM utilisateurs u
        INNER JOIN mentors_profils mp ON mp.utilisateur_id = u.id
        LEFT JOIN utilisateurs_matieres um
               ON um.utilisateur_id = u.id AND um.type_relation = 'enseigne'
        LEFT JOIN matieres m ON m.id = um.matiere_id AND m.actif = 1
        WHERE u.est_mentor    = 1
          AND u.mentor_valide = 1
          AND u.statut        = 'actif'
    ";

    if (!empty($matiere_id)) {
        $where[] = "u.id IN (
            SELECT utilisateur_id FROM utilisateurs_matieres
            WHERE matiere_id = :mid AND type_relation = 'enseigne'
        )";
        $params[':mid'] = $matiere_id;
    }
    if (!empty($note_min)) {
        $where[] = "mp.note_moyenne >= :note_min";
        $params[':note_min'] = $note_min;
    }
    if (!empty($recherche)) {
        $where[] = "(u.nom LIKE :rech OR u.prenom LIKE :rech2)";
        $params[':rech']  = '%' . $recherche . '%';
        $params[':rech2'] = '%' . $recherche . '%';
    }

    if (!empty($where)) {
        $sql .= " AND " . implode(' AND ', $where);
    }

    $sql .= " GROUP BY u.id ORDER BY mp.note_moyenne DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}


// ------------------------------------------------------------
//  Recherche de mentors avec filtres avancés
//  Utilisée par recherche_controller
// ------------------------------------------------------------
function rechercher_mentors(array $filtres = []): array {
    $pdo    = get_pdo();
    $params = [];

    $matiere  = trim($filtres['matiere']  ?? '');
    $nom      = trim($filtres['nom']      ?? '');
    $note_min = (float)($filtres['note_min'] ?? 0);
    $mode     = trim($filtres['mode']     ?? '');
    $tri      = in_array($filtres['tri'] ?? '', ['note', 'nom', 'sessions'])
                    ? $filtres['tri'] : 'note';

    $sql = "
        SELECT
            u.id, u.nom, u.prenom, u.photo,
            mp.bio, mp.note_moyenne, mp.nb_evaluations,
            GROUP_CONCAT(DISTINCT ma.nom ORDER BY ma.nom SEPARATOR ', ') AS matieres_liste,
            COUNT(DISTINCT s.id) AS total_sessions
        FROM utilisateurs u
        INNER JOIN mentors_profils mp ON mp.utilisateur_id = u.id
        LEFT JOIN utilisateurs_matieres um
               ON um.utilisateur_id = u.id AND um.type_relation = 'enseigne'
        LEFT JOIN matieres ma ON ma.id = um.matiere_id AND ma.actif = 1
        LEFT JOIN sessions  s  ON s.mentor_id = u.id AND s.statut = 'terminee'
        WHERE u.est_mentor    = 1
          AND u.mentor_valide = 1
          AND u.statut        = 'actif'
    ";

    if ($matiere !== '') {
        $sql .= " AND ma.nom LIKE :matiere";
        $params[':matiere'] = '%' . $matiere . '%';
    }
    if ($nom !== '') {
        $sql .= " AND (u.nom LIKE :nom OR u.prenom LIKE :nom2)";
        $params[':nom']  = '%' . $nom . '%';
        $params[':nom2'] = '%' . $nom . '%';
    }
    if ($note_min > 0) {
        $sql .= " AND mp.note_moyenne >= :note_min";
        $params[':note_min'] = $note_min;
    }
    if ($mode !== '') {
        $sql .= " AND EXISTS (
            SELECT 1 FROM disponibilites d
            WHERE d.mentor_id    = u.id
              AND d.mode_session = :mode
              AND d.est_reservee = 0
              AND d.date_dispo   >= CURDATE()
        )";
        $params[':mode'] = $mode;
    }

    $sql .= " GROUP BY u.id, mp.bio, mp.note_moyenne, mp.nb_evaluations";
    $sql .= match($tri) {
        'nom'      => " ORDER BY u.nom ASC",
        'sessions' => " ORDER BY total_sessions DESC",
        default    => " ORDER BY mp.note_moyenne DESC, mp.nb_evaluations DESC",
    };

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}