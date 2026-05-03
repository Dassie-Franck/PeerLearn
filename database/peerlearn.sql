-- ============================================================
--  PeerLearn — Script SQL Complet
--  Version : 1.0 | Mars 2026
--  Tables   : 11
--  Vues     : 4
--  Triggers : 4
-- ============================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- ------------------------------------------------------------
-- Suppression des tables si elles existent deja (reset propre)
-- ------------------------------------------------------------
DROP TABLE IF EXISTS journaux_admin;
DROP TABLE IF EXISTS signalements;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS evaluations;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS sessions;
DROP TABLE IF EXISTS disponibilites;
DROP TABLE IF EXISTS utilisateurs_matieres;
DROP TABLE IF EXISTS matieres;
DROP TABLE IF EXISTS mentors_profils;
DROP TABLE IF EXISTS utilisateurs;

-- ============================================================
--  TABLE 1 : utilisateurs
--  Entite centrale — tout inscrit est un utilisateur
-- ============================================================
CREATE TABLE utilisateurs (
    id            INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nom           VARCHAR(100)    NOT NULL,
    prenom        VARCHAR(100)    NOT NULL,
    email         VARCHAR(191)    NOT NULL,
    mot_de_passe  VARCHAR(255)    NOT NULL,
    role          ENUM('etudiant','admin') NOT NULL DEFAULT 'etudiant',
    est_mentor    TINYINT(1)      NOT NULL DEFAULT 0,
    mentor_valide TINYINT(1)      NOT NULL DEFAULT 0,
    statut        ENUM('actif','suspendu','banni') NOT NULL DEFAULT 'actif',
    photo         VARCHAR(255)    DEFAULT NULL,
    created_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uk_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 2 : mentors_profils
--  Extension 1-1 de utilisateurs quand est_mentor = 1
-- ============================================================
CREATE TABLE mentors_profils (
    id                     INT UNSIGNED NOT NULL AUTO_INCREMENT,
    utilisateur_id         INT UNSIGNED NOT NULL,
    bio                    TEXT         DEFAULT NULL,
    experience             TEXT         DEFAULT NULL,
    note_moyenne           DECIMAL(3,2) NOT NULL DEFAULT 0.00,
    nb_evaluations         INT UNSIGNED NOT NULL DEFAULT 0,
    statut_dispo           ENUM('disponible','occupe','inactif') NOT NULL DEFAULT 'disponible',
    nb_annulations_tardives TINYINT UNSIGNED NOT NULL DEFAULT 0,
    created_at             DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at             DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uk_utilisateur (utilisateur_id),
    CONSTRAINT fk_mp_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 3 : matieres
--  Referentiel des disciplines disponibles sur la plateforme
-- ============================================================
CREATE TABLE matieres (
    id         INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    nom        VARCHAR(100)  NOT NULL,
    categorie  VARCHAR(100)  DEFAULT NULL,
    actif      TINYINT(1)    NOT NULL DEFAULT 1,
    created_at DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uk_nom (nom)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 4 : utilisateurs_matieres
--  Liaison N-N entre utilisateurs et matieres
--  type_relation : 'enseigne' (mentor) ou 'apprend' (etudiant)
-- ============================================================
CREATE TABLE utilisateurs_matieres (
    utilisateur_id INT UNSIGNED NOT NULL,
    matiere_id     INT UNSIGNED NOT NULL,
    type_relation  ENUM('enseigne','apprend') NOT NULL DEFAULT 'apprend',

    PRIMARY KEY (utilisateur_id, matiere_id, type_relation),
    CONSTRAINT fk_um_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_um_matiere
        FOREIGN KEY (matiere_id) REFERENCES matieres(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 5 : disponibilites
--  Creneaux publies par les mentors
-- ============================================================
CREATE TABLE disponibilites (
    id           INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    mentor_id    INT UNSIGNED  NOT NULL,
    matiere_id   INT UNSIGNED  NOT NULL,
    date_dispo   DATE          NOT NULL,
    heure_debut  TIME          NOT NULL,
    heure_fin    TIME          NOT NULL,
    mode_session ENUM('presentiel','en_ligne') NOT NULL DEFAULT 'en_ligne',
    est_reservee TINYINT(1)    NOT NULL DEFAULT 0,
    created_at   DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_dispo_mentor
        FOREIGN KEY (mentor_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_dispo_matiere
        FOREIGN KEY (matiere_id) REFERENCES matieres(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 6 : sessions
--  Entite centrale du tutorat — relie mentor et apprenant
-- ============================================================
CREATE TABLE sessions (
    id               INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    mentor_id        INT UNSIGNED  NOT NULL,
    apprenant_id     INT UNSIGNED  NOT NULL,
    disponibilite_id INT UNSIGNED  DEFAULT NULL,
    matiere_id       INT UNSIGNED  NOT NULL,
    date_session     DATE          NOT NULL,
    heure_debut      TIME          NOT NULL,
    heure_fin        TIME          NOT NULL,
    statut           ENUM('en_attente','confirmee','terminee','annulee') NOT NULL DEFAULT 'en_attente',
    mode_session     ENUM('presentiel','en_ligne') NOT NULL DEFAULT 'en_ligne',
    lien_session     VARCHAR(500)  DEFAULT NULL,
    motif_annulation TEXT          DEFAULT NULL,
    annulation_tardive TINYINT(1)  NOT NULL DEFAULT 0,
    created_at       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    -- Regle metier : un mentor ne peut pas etre son propre apprenant
    CONSTRAINT chk_no_autoresa CHECK (mentor_id <> apprenant_id),
    CONSTRAINT fk_sess_mentor
        FOREIGN KEY (mentor_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_sess_apprenant
        FOREIGN KEY (apprenant_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_sess_dispo
        FOREIGN KEY (disponibilite_id) REFERENCES disponibilites(id)
        ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT fk_sess_matiere
        FOREIGN KEY (matiere_id) REFERENCES matieres(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 7 : messages
--  Messagerie privee entre etudiants
-- ============================================================
CREATE TABLE messages (
    id             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    envoyeur_id    INT UNSIGNED  NOT NULL,
    destinataire_id INT UNSIGNED NOT NULL,
    contenu        TEXT          NOT NULL,
    fichier_joint  VARCHAR(255)  DEFAULT NULL,
    lu             TINYINT(1)    NOT NULL DEFAULT 0,
    signale        TINYINT(1)    NOT NULL DEFAULT 0,
    date_envoi     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_msg_envoyeur
        FOREIGN KEY (envoyeur_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_msg_destinataire
        FOREIGN KEY (destinataire_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    -- Index pour accelerer le polling JS
    INDEX idx_destinataire_lu (destinataire_id, lu),
    INDEX idx_conversation (envoyeur_id, destinataire_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 8 : evaluations
--  Notes post-session — une seule par session (UNIQUE)
-- ============================================================
CREATE TABLE evaluations (
    id             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    session_id     INT UNSIGNED  NOT NULL,
    apprenant_id   INT UNSIGNED  NOT NULL,
    mentor_id      INT UNSIGNED  NOT NULL,
    note           TINYINT       NOT NULL,
    commentaire    TEXT          DEFAULT NULL,
    reponse_mentor TEXT          DEFAULT NULL,
    visible        TINYINT(1)    NOT NULL DEFAULT 1,
    created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    -- Regle metier : une seule evaluation par session
    UNIQUE KEY uk_session (session_id),
    -- Regle metier : note entre 1 et 5
    CONSTRAINT chk_note CHECK (note BETWEEN 1 AND 5),
    CONSTRAINT fk_eval_session
        FOREIGN KEY (session_id) REFERENCES sessions(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_eval_apprenant
        FOREIGN KEY (apprenant_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_eval_mentor
        FOREIGN KEY (mentor_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 9 : notifications
--  Alertes in-app pour chaque utilisateur
-- ============================================================
CREATE TABLE notifications (
    id             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    utilisateur_id INT UNSIGNED  NOT NULL,
    type           ENUM(
                     'nouvelle_reservation',
                     'reservation_confirmee',
                     'reservation_annulee',
                     'nouveau_message',
                     'nouvelle_evaluation',
                     'profil_mentor_valide',
                     'profil_mentor_rejete',
                     'profil_suspendu',
                     'rappel_session'
                   ) NOT NULL,
    titre          VARCHAR(200)  NOT NULL,
    contenu        TEXT          NOT NULL,
    lu             TINYINT(1)    NOT NULL DEFAULT 0,
    lien           VARCHAR(500)  DEFAULT NULL,
    created_at     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_user_lu (utilisateur_id, lu),
    CONSTRAINT fk_notif_utilisateur
        FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 10 : signalements
--  Signalements de contenus abusifs
-- ============================================================
CREATE TABLE signalements (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    signale_par INT UNSIGNED  NOT NULL,
    type_cible  ENUM('message','evaluation','profil') NOT NULL,
    cible_id    INT UNSIGNED  NOT NULL,
    motif       TEXT          NOT NULL,
    statut      ENUM('en_attente','traite','rejete') NOT NULL DEFAULT 'en_attente',
    traite_par  INT UNSIGNED  DEFAULT NULL,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_sig_signale_par
        FOREIGN KEY (signale_par) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_sig_traite_par
        FOREIGN KEY (traite_par) REFERENCES utilisateurs(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
--  TABLE 11 : journaux_admin
--  Log de toutes les actions de l administrateur
-- ============================================================
CREATE TABLE journaux_admin (
    id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    admin_id    INT UNSIGNED  NOT NULL,
    action      VARCHAR(100)  NOT NULL,
    description TEXT          DEFAULT NULL,
    ip_address  VARCHAR(45)   DEFAULT NULL,
    date_action DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_log_admin
        FOREIGN KEY (admin_id) REFERENCES utilisateurs(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


SET FOREIGN_KEY_CHECKS = 1;


-- ============================================================
--  TRIGGERS (4)
-- ============================================================

-- ------------------------------------------------------------
--  TRIGGER 1 : Recalcul automatique de la note moyenne
--              du mentor apres chaque nouvelle evaluation
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_recalcul_note_apres_insert;
DELIMITER $$
CREATE TRIGGER trg_recalcul_note_apres_insert
AFTER INSERT ON evaluations
FOR EACH ROW
BEGIN
    UPDATE mentors_profils
    SET
        note_moyenne   = (
            SELECT ROUND(AVG(note), 2)
            FROM evaluations
            WHERE mentor_id = NEW.mentor_id
              AND visible   = 1
        ),
        nb_evaluations = (
            SELECT COUNT(*)
            FROM evaluations
            WHERE mentor_id = NEW.mentor_id
              AND visible   = 1
        )
    WHERE utilisateur_id = NEW.mentor_id;
END$$
DELIMITER ;


-- ------------------------------------------------------------
--  TRIGGER 2 : Recalcul automatique de la note moyenne
--              apres suppression ou masquage d une evaluation
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_recalcul_note_apres_update;
DELIMITER $$
CREATE TRIGGER trg_recalcul_note_apres_update
AFTER UPDATE ON evaluations
FOR EACH ROW
BEGIN
    UPDATE mentors_profils
    SET
        note_moyenne   = (
            SELECT COALESCE(ROUND(AVG(note), 2), 0.00)
            FROM evaluations
            WHERE mentor_id = NEW.mentor_id
              AND visible   = 1
        ),
        nb_evaluations = (
            SELECT COUNT(*)
            FROM evaluations
            WHERE mentor_id = NEW.mentor_id
              AND visible   = 1
        )
    WHERE utilisateur_id = NEW.mentor_id;
END$$
DELIMITER ;


-- ------------------------------------------------------------
--  TRIGGER 3 : Liberation automatique du creneau
--              quand une session est annulee
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_liberer_creneau_annulation;
DELIMITER $$
CREATE TRIGGER trg_liberer_creneau_annulation
AFTER UPDATE ON sessions
FOR EACH ROW
BEGIN
    -- Si le statut passe a 'annulee' et qu il y a une disponibilite liee
    IF NEW.statut = 'annulee' AND OLD.statut <> 'annulee'
       AND NEW.disponibilite_id IS NOT NULL THEN
        UPDATE disponibilites
        SET est_reservee = 0
        WHERE id = NEW.disponibilite_id;
    END IF;
END$$
DELIMITER ;


-- ------------------------------------------------------------
--  TRIGGER 4 : Suspension automatique du profil mentor
--              apres 3 annulations tardives
-- ------------------------------------------------------------
DROP TRIGGER IF EXISTS trg_suspension_annulations_tardives;
DELIMITER $$
CREATE TRIGGER trg_suspension_annulations_tardives
AFTER UPDATE ON sessions
FOR EACH ROW
BEGIN
    DECLARE total_tardives INT;

    -- Detecte une nouvelle annulation tardive par le mentor
    IF NEW.statut = 'annulee'
       AND OLD.statut <> 'annulee'
       AND NEW.annulation_tardive = 1 THEN

        -- Incremente le compteur dans mentors_profils
        UPDATE mentors_profils
        SET nb_annulations_tardives = nb_annulations_tardives + 1
        WHERE utilisateur_id = NEW.mentor_id;

        -- Recupere le nouveau total
        SELECT nb_annulations_tardives INTO total_tardives
        FROM mentors_profils
        WHERE utilisateur_id = NEW.mentor_id;

        -- Si 3 annulations tardives ou plus => suspension
        IF total_tardives >= 3 THEN
            UPDATE utilisateurs
            SET statut = 'suspendu'
            WHERE id = NEW.mentor_id;

            UPDATE mentors_profils
            SET statut_dispo = 'inactif'
            WHERE utilisateur_id = NEW.mentor_id;
        END IF;
    END IF;
END$$
DELIMITER ;


-- ============================================================
--  VUES (4)
-- ============================================================

-- ------------------------------------------------------------
--  VUE 1 : vue_mentors
--  Affiche uniquement les mentors valides et actifs
--  Utilisee pour la recherche et le listing des mentors
-- ------------------------------------------------------------
DROP VIEW IF EXISTS vue_mentors;
CREATE VIEW vue_mentors AS
SELECT
    u.id,
    u.nom,
    u.prenom,
    u.email,
    u.photo,
    mp.bio,
    mp.experience,
    mp.note_moyenne,
    mp.nb_evaluations,
    mp.statut_dispo,
    mp.nb_annulations_tardives
FROM utilisateurs u
INNER JOIN mentors_profils mp ON mp.utilisateur_id = u.id
WHERE u.est_mentor    = 1
  AND u.mentor_valide = 1
  AND u.statut        = 'actif'
  AND mp.statut_dispo <> 'inactif';


-- ------------------------------------------------------------
--  VUE 2 : vue_sessions_details
--  Sessions enrichies avec noms mentor/apprenant et matiere
--  Utilisee pour les dashboards et calendriers
-- ------------------------------------------------------------
DROP VIEW IF EXISTS vue_sessions_details;
CREATE VIEW vue_sessions_details AS
SELECT
    s.id,
    s.statut,
    s.mode_session,
    s.lien_session,
    s.date_session,
    s.heure_debut,
    s.heure_fin,
    s.created_at,
    -- Mentor
    s.mentor_id,
    CONCAT(um.prenom, ' ', um.nom) AS mentor_nom_complet,
    um.photo                        AS mentor_photo,
    -- Apprenant
    s.apprenant_id,
    CONCAT(ua.prenom, ' ', ua.nom) AS apprenant_nom_complet,
    ua.photo                        AS apprenant_photo,
    -- Matiere
    s.matiere_id,
    m.nom                           AS matiere_nom
FROM sessions s
INNER JOIN utilisateurs um ON um.id = s.mentor_id
INNER JOIN utilisateurs ua ON ua.id = s.apprenant_id
INNER JOIN matieres     m  ON m.id  = s.matiere_id;


-- ------------------------------------------------------------
--  VUE 3 : vue_messages_non_lus
--  Compte les messages non lus par destinataire
--  Utilisee pour le badge de messagerie
-- ------------------------------------------------------------
DROP VIEW IF EXISTS vue_messages_non_lus;
CREATE VIEW vue_messages_non_lus AS
SELECT
    destinataire_id  AS utilisateur_id,
    COUNT(*)         AS nb_non_lus
FROM messages
WHERE lu      = 0
  AND signale = 0
GROUP BY destinataire_id;


-- ------------------------------------------------------------
--  VUE 4 : vue_evaluations_visibles
--  Evaluations publiques avec noms et notes
--  Utilisee pour la fiche publique du mentor
-- ------------------------------------------------------------
DROP VIEW IF EXISTS vue_evaluations_visibles;
CREATE VIEW vue_evaluations_visibles AS
SELECT
    e.id,
    e.session_id,
    e.note,
    e.commentaire,
    e.reponse_mentor,
    e.created_at,
    -- Mentor evalue
    e.mentor_id,
    CONCAT(um.prenom, ' ', um.nom) AS mentor_nom_complet,
    -- Apprenant qui evalue
    e.apprenant_id,
    CONCAT(ua.prenom, ' ', ua.nom) AS apprenant_nom_complet,
    ua.photo                        AS apprenant_photo,
    -- Matiere de la session
    m.nom AS matiere_nom
FROM evaluations e
INNER JOIN utilisateurs um ON um.id = e.mentor_id
INNER JOIN utilisateurs ua ON ua.id = e.apprenant_id
INNER JOIN sessions     s  ON s.id  = e.session_id
INNER JOIN matieres     m  ON m.id  = s.matiere_id
WHERE e.visible = 1;


-- ============================================================
--  DONNEES DE BASE (indispensables au demarrage)
-- ============================================================

-- Compte administrateur par defaut
-- Mot de passe : Admin@1234  (hash bcrypt)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role, statut)
VALUES (
    'Admin',
    'PeerLearn',
    'admin@peerlearn.local',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    'actif'
);

-- Matieres de base
INSERT INTO matieres (nom, categorie) VALUES
('Mathematiques',         'Sciences'),
('Physique',              'Sciences'),
('Chimie',                'Sciences'),
('Informatique',          'Sciences'),
('Algorithmique',         'Sciences'),
('Base de donnees',       'Sciences'),
('Reseaux',               'Sciences'),
('Francais',              'Lettres'),
('Anglais',               'Langues'),
('Espagnol',              'Langues'),
('Histoire-Geographie',   'Sciences Humaines'),
('Economie',              'Sciences Humaines'),
('Comptabilite',          'Gestion'),
('Marketing',             'Gestion'),
('Droit',                 'Sciences Humaines');

-- ============================================================
--  FIN DU SCRIPT
-- ============================================================