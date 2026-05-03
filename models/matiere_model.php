<?php
// ============================================================
//  models/matiere_model.php
// ============================================================

// Toutes les matieres actives
function get_toutes_matieres(): array {
    $pdo = get_pdo();
    return $pdo->query("
        SELECT id, nom, categorie
        FROM matieres
        WHERE actif = 1
        ORDER BY categorie, nom
    ")->fetchAll();
}

// Matieres groupees par categorie
function get_matieres_par_categorie(): array {
    $groupees = [];
    foreach (get_toutes_matieres() as $m) {
        $groupees[$m['categorie']][] = $m;
    }
    return $groupees;
}

// Matieres apprises par un etudiant
function get_matieres_etudiant(int $user_id): array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT m.id, m.nom, m.categorie
        FROM matieres m
        INNER JOIN utilisateurs_matieres um ON um.matiere_id = m.id
        WHERE um.utilisateur_id = :uid
          AND um.type_relation  = 'apprend'
          AND m.actif = 1
        ORDER BY m.nom
    ");
    $stmt->execute([':uid' => $user_id]);
    return $stmt->fetchAll();
}

// Matieres enseignees par un mentor
function get_matieres_mentor(int $user_id): array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT m.id, m.nom, m.categorie
        FROM matieres m
        INNER JOIN utilisateurs_matieres um ON um.matiere_id = m.id
        WHERE um.utilisateur_id = :uid
          AND um.type_relation  = 'enseigne'
          AND m.actif = 1
        ORDER BY m.nom
    ");
    $stmt->execute([':uid' => $user_id]);
    return $stmt->fetchAll();
}

// Mettre a jour les matieres apprises
function mettre_a_jour_matieres_etudiant(int $user_id, array $ids): void {
    $pdo = get_pdo();
    $pdo->prepare("
        DELETE FROM utilisateurs_matieres
        WHERE utilisateur_id = :uid AND type_relation = 'apprend'
    ")->execute([':uid' => $user_id]);

    if (!empty($ids)) {
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs_matieres (utilisateur_id, matiere_id, type_relation)
            VALUES (:uid, :mid, 'apprend')
        ");
        foreach ($ids as $mid) {
            $stmt->execute([':uid' => $user_id, ':mid' => (int)$mid]);
        }
    }
}

// Mettre a jour les matieres enseignees
function mettre_a_jour_matieres_mentor(int $user_id, array $ids): void {
    $pdo = get_pdo();
    $pdo->prepare("
        DELETE FROM utilisateurs_matieres
        WHERE utilisateur_id = :uid AND type_relation = 'enseigne'
    ")->execute([':uid' => $user_id]);

    if (!empty($ids)) {
        $stmt = $pdo->prepare("
            INSERT INTO utilisateurs_matieres (utilisateur_id, matiere_id, type_relation)
            VALUES (:uid, :mid, 'enseigne')
        ");
        foreach ($ids as $mid) {
            $stmt->execute([':uid' => $user_id, ':mid' => (int)$mid]);
        }
    }
}