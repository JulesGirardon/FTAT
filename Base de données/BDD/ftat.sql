SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS sprintbacklog;
DROP TABLE IF EXISTS sprints;
DROP TABLE IF EXISTS taches;
DROP TABLE IF EXISTS rolesutilisateurprojet;
DROP TABLE IF EXISTS idees_bac_a_sable;
DROP TABLE IF EXISTS etatstaches;
DROP TABLE IF EXISTS prioritestaches;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS equipesprj;
DROP TABLE IF EXISTS projets;

SET FOREIGN_KEY_CHECKS = 1;

-- Base de données : `agiletools`

-- --------------------------------------------------------
-- Table des équipes de projet
-- --------------------------------------------------------

CREATE TABLE equipesprj (
                            IdEq SMALLINT(6) NOT NULL AUTO_INCREMENT,
                            NomEqPrj VARCHAR(100) NOT NULL,
                            PRIMARY KEY (IdEq)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des projets
-- --------------------------------------------------------

CREATE TABLE projets (
                         IdP SMALLINT(6) NOT NULL AUTO_INCREMENT,
                         NomP VARCHAR(100) NOT NULL,
                         DescriptionP TEXT,
                         DateDebutP DATE,
                         DateFinP DATE,
                         IdEq SMALLINT(6) NOT NULL, -- L'équipe responsable du projet
                         PRIMARY KEY (IdP),
                         CONSTRAINT FK_Projets_Equipes FOREIGN KEY (IdEq) REFERENCES equipesprj(IdEq)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des utilisateurs
-- --------------------------------------------------------

CREATE TABLE utilisateurs (
                              IdU SMALLINT(6) NOT NULL AUTO_INCREMENT,
                              NomU VARCHAR(50) NOT NULL,
                              PrenomU VARCHAR(50) NOT NULL,
                              mail VARCHAR(50) NOT NULL,
                              MotDePasseU VARCHAR(255) NOT NULL,
                              SpecialiteU ENUM('Développeur','Modeleur','Animateur','UI','IA','WebComm','Polyvalent') NOT NULL DEFAULT 'Polyvalent',
                              Statut ENUM('Admin','User') NOT NULL DEFAULT 'User',
                              PRIMARY KEY (IdU)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des rôles
-- --------------------------------------------------------

CREATE TABLE roles (
                       IdR SMALLINT(6) NOT NULL AUTO_INCREMENT,
                       DescR VARCHAR(100) NOT NULL,
                       PRIMARY KEY (IdR)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des états de tâche
-- --------------------------------------------------------

CREATE TABLE etatstaches (
                             IdEtat SMALLINT(4) NOT NULL,
                             Etat VARCHAR(50) NOT NULL,
                             PRIMARY KEY (IdEtat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des états de tâches
INSERT INTO etatstaches (IdEtat, Etat) VALUES
                                           (1, 'A faire'),
                                           (2, 'En cours'),
                                           (3, 'Terminé et Test Unitaire réalisé'),
                                           (4, 'Test Fonctionnel Réalisé / Module intégré dans version'),
                                           (5, 'Intégré dans version de production');

-- --------------------------------------------------------
-- Table des priorités de tâches
-- --------------------------------------------------------

CREATE TABLE prioritestaches (
                                 idPriorite TINYINT(1) NOT NULL,
                                 Priorite VARCHAR(15) NOT NULL,
                                 valPriorite TINYINT(1) NOT NULL,
                                 PRIMARY KEY (idPriorite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des priorités de tâches
INSERT INTO prioritestaches (idPriorite, Priorite, valPriorite) VALUES
                                                                    (1, '1', 1),
                                                                    (2, '2', 2),
                                                                    (3, '3', 3),
                                                                    (4, '4', 4),
                                                                    (5, '5', 5),
                                                                    (6, 'MUST (MoSCoW)', 5),
                                                                    (7, 'SHOULD (MoSCoW)', 4),
                                                                    (8, 'Could', 2),
                                                                    (9, 'WONT (MoSCoW)', 0);

-- --------------------------------------------------------
-- Table des idées bac à sable
-- --------------------------------------------------------

CREATE TABLE idees_bac_a_sable (
                                   Id_Idee_bas INT(11) NOT NULL AUTO_INCREMENT,
                                   desc_Idee_bas VARCHAR(300) NOT NULL,
                                   IdU SMALLINT(6) NOT NULL,
                                   IdP SMALLINT(6) NOT NULL,
                                   PRIMARY KEY (Id_Idee_bas),
                                   CONSTRAINT FK_IdeesBAS_Utilisateurs FOREIGN KEY (IdU) REFERENCES utilisateurs(IdU),
                                   CONSTRAINT FK_IdeesBAS_Equipes FOREIGN KEY (IdP) REFERENCES projets(IdP)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des tâches
-- --------------------------------------------------------

CREATE TABLE taches (
                        IdT INT(11) NOT NULL AUTO_INCREMENT,
                        TitreT VARCHAR(50) NOT NULL,
                        UserStoryT VARCHAR(300) NOT NULL,
                        IdP SMALLINT(6) NOT NULL,
                        CoutT ENUM('?', '1', '3', '5', '10', '15', '25', '999') NOT NULL DEFAULT '?',
                        IdPriorite TINYINT(1) NOT NULL,
                        PRIMARY KEY (IdT),
                        CONSTRAINT FK_Taches_Projets FOREIGN KEY (IdP) REFERENCES projets(IdP),
                        CONSTRAINT FK_Taches_Priorite FOREIGN KEY (IdPriorite) REFERENCES prioritestaches(idPriorite)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des sprints
-- --------------------------------------------------------

CREATE TABLE sprints (
                         IdS SMALLINT(6) NOT NULL AUTO_INCREMENT,
                         DateDebS DATE NOT NULL,
                         DateFinS DATE NOT NULL,
                         RetrospectiveS VARCHAR(300) DEFAULT NULL,
                         RevueDeSprint VARCHAR(300) DEFAULT NULL,
                         IdP SMALLINT(6) NOT NULL,
                         VelociteEqPrj DECIMAL(10, 0) NOT NULL,
                         PRIMARY KEY (IdS),
                         CONSTRAINT FK_Sprints_Projets FOREIGN KEY (IdP) REFERENCES projets(IdP)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table du sprint backlog
-- --------------------------------------------------------

CREATE TABLE sprintbacklog (
                               IdT INT(11) NOT NULL,
                               IdS SMALLINT(6) NOT NULL,
                               IdU SMALLINT(6) NOT NULL,
                               IdEtat SMALLINT(6) NOT NULL,
                               PRIMARY KEY (IdT),
                               CONSTRAINT FK_SB_Taches FOREIGN KEY (IdT) REFERENCES taches(IdT),
                               CONSTRAINT FK_SB_Sprints FOREIGN KEY (IdS) REFERENCES sprints(IdS),
                               CONSTRAINT FK_SB_Utilisateurs FOREIGN KEY (IdU) REFERENCES utilisateurs(IdU),
                               CONSTRAINT FK_SB_EtatTaches FOREIGN KEY (IdEtat) REFERENCES etatstaches(IdEtat)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des rôles utilisateur projet
-- --------------------------------------------------------

CREATE TABLE rolesutilisateurprojet (
                                        IdU SMALLINT(6) NOT NULL,
                                        IdP SMALLINT(6) NOT NULL, -- L'utilisateur a un rôle spécifique dans un projet
                                        IdR SMALLINT(6) NOT NULL,
                                        PRIMARY KEY (IdU, IdP, IdR),
                                        CONSTRAINT FK_RoleUtil_Projets FOREIGN KEY (IdP) REFERENCES projets(IdP),
                                        CONSTRAINT FK_RoleUtil_Utilisateurs FOREIGN KEY (IdU) REFERENCES utilisateurs(IdU),
                                        CONSTRAINT FK_RoleUtil_Roles FOREIGN KEY (IdR) REFERENCES roles(IdR)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table des membres d'équipe (relation N:N entre équipes et utilisateurs)
-- --------------------------------------------------------

CREATE TABLE membre_equipe (
                               IdEq SMALLINT(6) NOT NULL,
                               IdU SMALLINT(6) NOT NULL,
                               PRIMARY KEY (IdEq, IdU),
                               CONSTRAINT FK_MembreEquipe_Equipes FOREIGN KEY (IdEq) REFERENCES equipesprj(IdEq),
                               CONSTRAINT FK_MembreEquipe_Utilisateurs FOREIGN KEY (IdU) REFERENCES utilisateurs(IdU)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DELIMITER $$

CREATE FUNCTION getIdRole(role VARCHAR(50))
    RETURNS INT
    DETERMINISTIC
BEGIN
    DECLARE roleId INT;

    IF role IN ('Scrum Master', 'Product Owner', 'Member') THEN
        SELECT IdR
        INTO roleId
        FROM ftat.roles
        WHERE DescR = role;

        RETURN roleId;
    ELSE
        RETURN -1;
    END IF;
END $$

DELIMITER ;

-- Ce trigger s'assure qu'une équipe ne peut pas avoir deux taches avec le meme titre dans un projet.
DELIMITER $$

CREATE TRIGGER verify_unique_task_per_team
BEFORE INSERT ON taches
FOR EACH ROW
BEGIN
    DECLARE task_count INT;
    
    SELECT COUNT(*) INTO task_count
    FROM taches
    WHERE TitreT = NEW.TitreT AND IdEq = NEW.IdEq;

    IF task_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Erreur : Une tache avec ce titre existe déjà dans cette équipe.';
    END IF;
END$$

DELIMITER ;

-- Ces triggers s'assure que la vélocité d'une équipe ne peut pas etre négative.
  DELIMITER $$

CREATE TRIGGER check_team_velocity
BEFORE INSERT ON sprints
FOR EACH ROW
BEGIN
  -- Vérifier que la vélocité de l'équipe est positive
  IF NEW.VelociteEqPrj < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = "Erreur : La vélocité d'un sprint ne peut pas etre négatif.";
  END IF;
END; $$

CREATE TRIGGER check_team_velocity_update
BEFORE UPDATE ON sprints
FOR EACH ROW
BEGIN
  -- Vérifier que la vélocité de l'équipe est positive
  IF NEW.VelociteEqPrj < 0 THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = "Erreur : La vélocité d'un sprint ne peut pas etre négatif.";
  END IF;
END; $$

DELIMITER ;

-- Ce trigger s'assure que les taches ne peut t'etre assignés que a des utilisateurs qui apparatiennent a l'équipe
DELIMITER $$

CREATE TRIGGER prevent_invalid_task_assignment
BEFORE INSERT ON sprintbacklog
FOR EACH ROW
BEGIN
  DECLARE team_id_of_user SMALLINT;
  
  -- Récupère le team ID de l'utilisateur assigné à la tache
  SELECT IdEq INTO team_id_of_user
  FROM utilisateurs
  WHERE IdU = NEW.IdU;

  -- Si la team de l'utilisateur ne correspond pas à la team assignée à la tache, empêche l'assignation
  IF team_id_of_user != (SELECT IdEq FROM taches WHERE IdT = NEW.IdT) THEN
    SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = "Erreur: L'Utilisateur n'appartient pas à la même équipe que la tâche.";
  END IF;
END; $$

DELIMITER ;

COMMIT;
