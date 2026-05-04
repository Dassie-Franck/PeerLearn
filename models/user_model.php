<?php
// ============================================================
//  models/user_model.php
// ============================================================

// Cost bcrypt fixé à 10 — compatible o2switch (mutualisé)
// PASSWORD_DEFAULT peut hériter du cost serveur (12-14) → timeout 504
define('BCRYPT_OPTIONS', ['cost' => 10]);

function creer_utilisateur(string $nom, string $prenom, string $email, string $mdp): int {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, statut)
        VALUES (:nom, :prenom, :email, :mdp, 'etudiant', 'actif')
    ");
    $stmt->execute([
        ':nom'    => $nom,
        ':prenom' => $prenom,
        ':email'  => $email,
        ':mdp'    => password_hash($mdp, PASSWORD_BCRYPT, BCRYPT_OPTIONS),
    ]);
    return (int) $pdo->lastInsertId();
}

function trouver_utilisateur_par_email(string $email): array|false {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    return $stmt->fetch();
}

function trouver_utilisateur_par_id(int $id): array|false {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function email_existe(string $email): bool {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateurs WHERE email = :email");
    $stmt->execute([':email' => $email]);
    return (int) $stmt->fetchColumn() > 0;
}

function mettre_a_jour_profil(int $id, string $nom, string $prenom, ?string $photo = null): void {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        UPDATE utilisateurs
        SET nom = :nom, prenom = :prenom, photo = COALESCE(:photo, photo)
        WHERE id = :id
    ");
    $stmt->execute([':nom' => $nom, ':prenom' => $prenom, ':photo' => $photo, ':id' => $id]);
}

function mettre_a_jour_mot_de_passe(int $id, string $nouveau_mdp): void {
    $pdo  = get_pdo();
    $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = :mdp WHERE id = :id")
        ->execute([
            ':mdp' => password_hash($nouveau_mdp, PASSWORD_BCRYPT, BCRYPT_OPTIONS),
            ':id'  => $id,
        ]);
}

function demander_profil_mentor(int $user_id, string $bio, string $experience): void {
    $pdo = get_pdo();
    $pdo->prepare("UPDATE utilisateurs SET est_mentor = 1, mentor_valide = 0 WHERE id = :id")
        ->execute([':id' => $user_id]);
    $pdo->prepare("
        INSERT INTO mentors_profils (utilisateur_id, bio, experience)
        VALUES (:uid, :bio, :exp)
        ON DUPLICATE KEY UPDATE bio = VALUES(bio), experience = VALUES(experience)
    ")->execute([':uid' => $user_id, ':bio' => $bio, ':exp' => $experience]);
}

function changer_statut_utilisateur(int $id, string $statut): void {
    $pdo  = get_pdo();
    $pdo->prepare("UPDATE utilisateurs SET statut = :statut WHERE id = :id")
        ->execute([':statut' => $statut, ':id' => $id]);
}

function valider_mentor(int $user_id, bool $valide): void {
    $pdo = get_pdo();
    $pdo->prepare("UPDATE utilisateurs SET mentor_valide = :v WHERE id = :id")
        ->execute([':v' => $valide ? 1 : 0, ':id' => $user_id]);
    if (!$valide) {
        $pdo->prepare("UPDATE utilisateurs SET est_mentor = 0 WHERE id = :id")
            ->execute([':id' => $user_id]);
    }
}

function get_tous_les_utilisateurs(): array {
    $pdo = get_pdo();
    return $pdo->query("
        SELECT id, nom, prenom, email, role, est_mentor, mentor_valide, statut, created_at
        FROM utilisateurs ORDER BY created_at DESC
    ")->fetchAll();
}

function compter_utilisateurs(): array|false {
    $pdo = get_pdo();
    return $pdo->query("
        SELECT
            COUNT(*)                                   AS total,
            SUM(role = 'etudiant')                     AS etudiants,
            SUM(est_mentor = 1 AND mentor_valide = 1)  AS mentors_valides,
            SUM(est_mentor = 1 AND mentor_valide = 0)  AS mentors_en_attente,
            SUM(statut = 'suspendu')                   AS suspendus
        FROM utilisateurs
    ")->fetch();
}