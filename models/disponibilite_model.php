<?php
// models/disponibilite_model.php

function creer_disponibilite(int $mentor_id, int $matiere_id, string $date,
                              string $debut, string $fin, string $mode): array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
        SELECT COUNT(*) FROM disponibilites
        WHERE mentor_id=:mid AND date_dispo=:date
          AND (heure_debut < :fin AND heure_fin > :debut)
    ");
    $stmt->execute([':mid'=>$mentor_id,':date'=>$date,':debut'=>$debut,':fin'=>$fin]);
    if ($stmt->fetchColumn() > 0) return ['erreur'=>'Tu as deja un creneau sur cet horaire.'];

    $pdo->prepare("
        INSERT INTO disponibilites (mentor_id,matiere_id,date_dispo,heure_debut,heure_fin,mode_session)
        VALUES (:mid,:mat,:date,:debut,:fin,:mode)
    ")->execute([':mid'=>$mentor_id,':mat'=>$matiere_id,':date'=>$date,
                 ':debut'=>$debut,':fin'=>$fin,':mode'=>$mode]);
    return ['id'=>(int)$pdo->lastInsertId()];
}

function get_disponibilites_mentor(int $mentor_id): array {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT d.*, m.nom AS matiere_nom
        FROM disponibilites d INNER JOIN matieres m ON m.id=d.matiere_id
        WHERE d.mentor_id=:mid AND d.date_dispo>=CURDATE()
        ORDER BY d.date_dispo ASC, d.heure_debut ASC
    ");
    $stmt->execute([':mid'=>$mentor_id]);
    return $stmt->fetchAll();
}

function get_disponibilites_libres(int $mentor_id, ?int $matiere_id=null): array {
    $pdo    = get_pdo();
    $params = [':mid'=>$mentor_id];
    $extra  = '';
    if ($matiere_id) { $extra='AND d.matiere_id=:mat'; $params[':mat']=$matiere_id; }
    $stmt = $pdo->prepare("
        SELECT d.*, m.nom AS matiere_nom
        FROM disponibilites d INNER JOIN matieres m ON m.id=d.matiere_id
        WHERE d.mentor_id=:mid AND d.est_reservee=0 AND d.date_dispo>=CURDATE() $extra
        ORDER BY d.date_dispo ASC, d.heure_debut ASC
    ");
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function get_disponibilite_by_id(int $id): array|false {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("
        SELECT d.*, m.nom AS matiere_nom
        FROM disponibilites d INNER JOIN matieres m ON m.id=d.matiere_id
        WHERE d.id=:id LIMIT 1
    ");
    $stmt->execute([':id'=>$id]);
    return $stmt->fetch();
}

function supprimer_disponibilite(int $id, int $mentor_id): bool {
    $pdo  = get_pdo();
    $stmt = $pdo->prepare("DELETE FROM disponibilites WHERE id=:id AND mentor_id=:mid AND est_reservee=0");
    $stmt->execute([':id'=>$id,':mid'=>$mentor_id]);
    return $stmt->rowCount() > 0;
}