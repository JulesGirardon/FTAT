-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 07 oct. 2024 à 00:57
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ftat`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayAllDifficulties` ()   BEGIN
    DECLARE enum_values TEXT;
    DECLARE start_pos INT;
    DECLARE end_pos INT;
    DECLARE current_value VARCHAR(10);
    CREATE TEMPORARY TABLE temp_enum_values (
                                                value VARCHAR(10)
    );
    SELECT COLUMN_TYPE INTO enum_values
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = 'taches'
      AND COLUMN_NAME = 'CoutT';
    SET enum_values = SUBSTRING(enum_values, 6, CHAR_LENGTH(enum_values) - 6);
    SET start_pos = 1;
    WHILE start_pos > 0 DO
            SET end_pos = LOCATE(',', enum_values, start_pos);
            IF end_pos = 0 THEN
                SET current_value = TRIM(BOTH '\'' FROM SUBSTRING(enum_values, start_pos));
                SET start_pos = 0;
            ELSE
                SET current_value = TRIM(BOTH '\'' FROM SUBSTRING(enum_values, start_pos, end_pos - start_pos));
                SET start_pos = end_pos + 1;
            END IF;
            INSERT INTO temp_enum_values (value) VALUES (current_value);
        END WHILE;

    SELECT * FROM temp_enum_values;
    DROP TEMPORARY TABLE IF EXISTS temp_enum_values;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllComments` (IN `pIdT` SMALLINT(6))   BEGIN
    SELECT Commentaire
    FROM CoutMembreTaches
    WHERE IdT = pIdT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllTasks` (IN `ID` INT)   BEGIN
    SELECT * FROM taches
    WHERE taches.IdP = ID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getAllVotes` (IN `ID` INT)   BEGIN
    SELECT
        t.IdT,
        COUNT(CASE WHEN cm.CoutMT = '?' THEN 1 END) AS Occurrence_,
        COUNT(CASE WHEN cm.CoutMT = '1' THEN 1 END) AS Occurrence_1,
        COUNT(CASE WHEN cm.CoutMT = '3' THEN 1 END) AS Occurrence_3,
        COUNT(CASE WHEN cm.CoutMT = '5' THEN 1 END) AS Occurrence_5,
        COUNT(CASE WHEN cm.CoutMT = '10' THEN 1 END) AS Occurrence_10,
        COUNT(CASE WHEN cm.CoutMT = '15' THEN 1 END) AS Occurrence_15,
        COUNT(CASE WHEN cm.CoutMT = '25' THEN 1 END) AS Occurrence_25,
        COUNT(CASE WHEN cm.CoutMT = '999' THEN 1 END) AS Occurrence_999
    FROM
        coutmembretaches cm
            JOIN taches t ON cm.IdT = t.IdT
    WHERE
        t.IdP = ID
    GROUP BY
        t.IdT;
END$$

--
-- Fonctions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getIdRole` (`role` VARCHAR(50)) RETURNS INT(11) DETERMINISTIC BEGIN
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
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `insertCoutMembreTache` (`pIdU` SMALLINT, `pIdT` INT, `pCommentaire` VARCHAR(255), `pCoutMT` ENUM('?','1','3','5','10','15','25','999')) RETURNS INT(11)  BEGIN
    DECLARE insertedId INT;

    INSERT INTO CoutMembreTaches (
        IdU, IdT, Commentaire, CoutMT
    ) VALUES (
                 pIdU, pIdT, pCommentaire, pCoutMT
             );

    SET insertedId = LAST_INSERT_ID();

    RETURN insertedId;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `insertCoutTache` (`pIdT` INT, `Cout` ENUM('?','1','3','5','10','15','25','999'), `Approuved` ENUM('0','1')) RETURNS INT(11)  BEGIN
    DECLARE affectedRows INT;
    UPDATE Taches
    SET CoutT = Cout, ApprouvedT = Approuved
    WHERE IdT = pIdT;
    SET affectedRows = ROW_COUNT();
    RETURN affectedRows;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `coutmembretaches`
--

CREATE TABLE `coutmembretaches` (
  `IdU` smallint(6) NOT NULL,
  `IdT` smallint(6) NOT NULL,
  `Commentaire` varchar(255) DEFAULT NULL,
  `CoutMT` enum('?','1','3','5','10','15','25','999') NOT NULL DEFAULT '?'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `equipesprj`
--

CREATE TABLE `equipesprj` (
  `IdEq` smallint(6) NOT NULL,
  `NomEqPrj` varchar(55) DEFAULT NULL,
  `IdP` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `equipesprj`
--

INSERT INTO `equipesprj` (`IdEq`, `NomEqPrj`, `IdP`) VALUES
(6, 'Flamby Interactive', 6),
(7, 'Ligne 11', 7),
(8, 'BancalePHP', 10),
(9, 'Moon Studio', 9);

-- --------------------------------------------------------

--
-- Structure de la table `etatstaches`
--

CREATE TABLE `etatstaches` (
  `IdEtat` smallint(4) NOT NULL,
  `Etat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etatstaches`
--

INSERT INTO `etatstaches` (`IdEtat`, `Etat`) VALUES
(1, 'A faire'),
(2, 'En cours'),
(3, 'Terminé et Test Unitaire réalisé'),
(4, 'Test Fonctionnel Réalisé / Module intégré dans ver'),
(5, 'Intégré dans version de production');

-- --------------------------------------------------------

--
-- Structure de la table `idees_bac_a_sable`
--

CREATE TABLE `idees_bac_a_sable` (
  `Id_Idee_bas` smallint(6) NOT NULL,
  `desc_Idee_bas` varchar(300) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `idees_bac_a_sable`
--

INSERT INTO `idees_bac_a_sable` (`Id_Idee_bas`, `desc_Idee_bas`, `IdU`, `IdEq`) VALUES
(1, 'Idée : Mettre des musiques de Beyonce', 1, 6),
(2, 'Idée : Cacher un livre dans une bibliothèque', 3, 6),
(3, 'Idée : Ajouter un système d upload d images', 2, 7),
(4, 'Idée : Permettre aux utilsateurs de se suivre entre eux', 1, 7),
(11, 'Idée : Implémenter la fonctionnalité de planning poker', 9, 8),
(12, 'Idée : Implémenter la fonctionnalité de bac à idées', 5, 8),
(13, 'Idée : Ajouter différentes fonctionnalités de déplacement', 1, 9),
(14, 'Idée : Diversifier le bestiaire', 9, 9),
(15, 'Idée : Permettre un backtracking facile', 2, 9),
(16, 'Idée : Modéliser un nouveau type de vaisseau spacial', 8, 9);

-- --------------------------------------------------------

--
-- Structure de la table `membre_equipe`
--

CREATE TABLE `membre_equipe` (
  `IdEq` smallint(6) NOT NULL,
  `IdU` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `membre_equipe`
--

INSERT INTO `membre_equipe` (`IdEq`, `IdU`) VALUES
(6, 1),
(6, 2),
(6, 3),
(6, 4),
(6, 9),
(7, 1),
(7, 2),
(8, 1),
(8, 2),
(8, 5),
(8, 6),
(8, 7),
(8, 8),
(8, 9),
(9, 1),
(9, 2),
(9, 3),
(9, 8),
(9, 9);

-- --------------------------------------------------------

--
-- Structure de la table `prioritestaches`
--

CREATE TABLE `prioritestaches` (
  `idPriorite` tinyint(1) NOT NULL,
  `Priorite` varchar(15) NOT NULL,
  `valPriorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prioritestaches`
--

INSERT INTO `prioritestaches` (`idPriorite`, `Priorite`, `valPriorite`) VALUES
(1, '1', 1),
(2, '2', 2),
(3, '3', 3),
(4, '4', 4),
(5, '5', 5),
(6, 'MUST', 5),
(7, 'SHOULD', 4),
(8, 'Could', 2),
(9, 'WONT', 0);

-- --------------------------------------------------------

--
-- Structure de la table `projets`
--

CREATE TABLE `projets` (
  `IdP` smallint(6) NOT NULL,
  `NomP` varchar(100) NOT NULL,
  `DescriptionP` text DEFAULT NULL,
  `DateDebutP` date DEFAULT NULL,
  `DateFinP` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `projets`
--

INSERT INTO `projets` (`IdP`, `NomP`, `DescriptionP`, `DateDebutP`, `DateFinP`) VALUES
(6, 'PMLK', 'Musée virtuel en hommage à Martin Luther King', '2024-04-01', '2024-07-01'),
(7, 'Stack UnderFlow', 'Site Internet : Blog', '2024-02-01', '2024-03-01'),
(9, 'Event Horizon 2 : The Last Gabion', 'Jeu 3D : Immersion spaciale partie 2', '2025-01-01', '2025-02-01'),
(10, 'FTAT', 'Site Internet : Application de la Méthode agile', '2024-09-01', '2024-10-10');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `IdR` smallint(6) NOT NULL,
  `DescR` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`IdR`, `DescR`) VALUES
(2, 'Scrum Master'),
(3, 'Développeur'),
(4, 'Product Owner'),
(5, 'Client');

-- --------------------------------------------------------

--
-- Structure de la table `rolesutilisateurprojet`
--

CREATE TABLE `rolesutilisateurprojet` (
  `IdU` smallint(6) NOT NULL,
  `IdP` smallint(6) NOT NULL,
  `IdR` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rolesutilisateurprojet`
--

INSERT INTO `rolesutilisateurprojet` (`IdU`, `IdP`, `IdR`) VALUES
(1, 6, 3),
(1, 7, 2),
(1, 9, 4),
(1, 10, 2),
(2, 6, 4),
(2, 7, 4),
(2, 9, 2),
(2, 10, 4),
(3, 6, 3),
(3, 9, 3),
(4, 6, 2),
(5, 10, 3),
(6, 10, 3),
(7, 10, 3),
(8, 9, 3),
(8, 10, 3),
(9, 6, 3),
(9, 9, 3),
(9, 10, 3);

--
-- Déclencheurs `rolesutilisateurprojet`
--
DELIMITER $$
CREATE TRIGGER `before_delete_role_from_project` BEFORE DELETE ON `rolesutilisateurprojet` FOR EACH ROW BEGIN
    DELETE FROM sprintbacklog
    WHERE IdU = OLD.IdU
    AND IdT IN (
       SELECT IdT
       FROM taches
       WHERE IdP = OLD.IdP
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_rolesutilisateurprojet_ProductOwner` BEFORE INSERT ON `rolesutilisateurprojet` FOR EACH ROW BEGIN
    DECLARE count_roles INT;

    IF NEW.IdR = ftat.getIdRole('Product Owner') THEN
        SELECT COUNT(*)
        INTO count_roles
        FROM rolesutilisateurprojet AS rup
        WHERE rup.IdP = NEW.IdP
          AND rup.IdR = ftat.getIdRole('Product Owner');

        IF count_roles > 0 THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Product Owner déjà existant pour ce projet !';
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_insert_rolesutilisateurprojet_ScrumMaster` BEFORE INSERT ON `rolesutilisateurprojet` FOR EACH ROW BEGIN
    DECLARE count_roles INT;

    IF NEW.IdR = ftat.getIdRole('Scrum Master') THEN
        SELECT COUNT(*)
        INTO count_roles
        FROM rolesutilisateurprojet AS rup
        WHERE rup.IdP = NEW.IdP
          AND rup.IdR = ftat.getIdRole('Scrum Master');

        IF count_roles > 0 THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Scrum Master déjà existant pour ce projet !';
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_rolesutilisateurprojet_ProductOwner` BEFORE UPDATE ON `rolesutilisateurprojet` FOR EACH ROW BEGIN
    DECLARE count_roles INT;

    IF NEW.IdR = ftat.getIdRole('Product Owner') THEN
        SELECT COUNT(*)
        INTO count_roles
        FROM rolesutilisateurprojet AS rup
        WHERE rup.IdP = NEW.IdP
          AND rup.IdR = ftat.getIdRole('Product Owner');

        IF count_roles > 0 THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Product Owner déjà existant pour ce projet !';
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `before_update_rolesutilisateurprojet_ScrumMaster` BEFORE UPDATE ON `rolesutilisateurprojet` FOR EACH ROW BEGIN
    DECLARE count_roles INT;

    IF NEW.IdR = ftat.getIdRole('Scrum Master') THEN
        SELECT COUNT(*)
        INTO count_roles
        FROM rolesutilisateurprojet AS rup
        WHERE rup.IdP = NEW.IdP
          AND rup.IdR = ftat.getIdRole('Scrum Master');

        IF count_roles > 0 THEN
            SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Scrum Master déjà existant pour ce projet !';
        END IF;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `sprintbacklog`
--

CREATE TABLE `sprintbacklog` (
  `IdT` smallint(6) NOT NULL,
  `IdS` smallint(6) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEtat` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sprintbacklog`
--

INSERT INTO `sprintbacklog` (`IdT`, `IdS`, `IdU`, `IdEtat`) VALUES
(1, 1, 4, 5),
(2, 1, 3, 5),
(3, 1, 9, 5),
(4, 1, 2, 5),
(5, 1, 1, 5),
(6, 2, 3, 5),
(7, 2, 3, 5),
(8, 2, 1, 5),
(9, 2, 9, 5),
(10, 3, 3, 5),
(11, 4, 2, 5),
(12, 3, 4, 5),
(13, 3, 3, 5),
(14, 3, 1, 5),
(15, 3, 9, 5),
(16, 3, 4, 5),
(17, 4, 3, 5),
(18, 4, 1, 5),
(19, 4, 9, 5),
(20, 4, 4, 5),
(21, 5, 1, 5),
(22, 2, 4, 5),
(23, 2, 2, 5),
(24, 6, 2, 5),
(25, 6, 2, 5),
(26, 6, 1, 5),
(27, 6, 2, 5),
(28, 6, 1, 5),
(29, 6, 1, 5),
(30, 6, 2, 5),
(31, 6, 1, 5),
(32, 7, 1, 5),
(33, 7, 2, 5),
(34, 7, 2, 5),
(35, 7, 5, 5),
(36, 7, 5, 5),
(37, 7, 6, 5),
(38, 7, 1, 5),
(39, 7, 1, 5),
(40, 7, 1, 5),
(41, 7, 8, 5),
(42, 7, 8, 5),
(43, 7, 8, 5),
(44, 7, 9, 5),
(45, 7, 8, 5),
(46, 7, 5, 5),
(47, 7, 8, 5),
(48, 7, 2, 5),
(49, 7, 6, 5),
(50, 7, 1, 5),
(51, 7, 7, 5),
(52, 7, 2, 2),
(53, 7, 2, 3),
(54, 7, 9, 3),
(55, 7, 9, 4),
(56, 7, 2, 2),
(57, 8, 8, 1),
(58, 8, 9, 5),
(59, 8, 2, 1),
(60, 8, 1, 1),
(61, 8, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `sprints`
--

CREATE TABLE `sprints` (
  `IdS` smallint(6) NOT NULL,
  `DateDebS` date NOT NULL,
  `DateFinS` date NOT NULL,
  `RetrospectiveS` varchar(300) DEFAULT NULL,
  `RevueDeSprint` varchar(300) DEFAULT NULL,
  `IdEq` smallint(6) NOT NULL,
  `VelociteEqPrj` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sprints`
--

INSERT INTO `sprints` (`IdS`, `DateDebS`, `DateFinS`, `RetrospectiveS`, `RevueDeSprint`, `IdEq`, `VelociteEqPrj`) VALUES
(1, '2024-01-01', '2024-02-01', 'Mise en place du projet, mise en place des missions. Répartition des tâches', '1ère vue du projet. Définition des objectifs principaux et répartition des tâches', 6, 43),
(2, '2024-02-02', '2024-03-01', 'Avancement dans la production des modèles 3d', 'Création des éléments visuels\r\n', 6, 63),
(3, '2024-03-02', '2024-04-01', 'Création du projet sur Unreal Engine 5 et création des différents levels', 'Création de la première maquette du jeu ', 6, 51),
(4, '2024-04-02', '2024-05-01', 'Avancement des niveaux', 'Production des éléments visuelles et fonctionnelles du jeu', 6, 36),
(5, '2024-05-02', '2024-05-20', 'Le jeu', 'Projet fini', 6, 999),
(6, '2024-02-01', '2024-03-01', 'Création du site', 'Création du site', 7, 89),
(7, '2024-09-02', '2024-10-09', 'Création des insert, functions et autres\r\n', 'Création et déploiement du site', 8, 223),
(8, '2024-10-01', '2025-01-31', NULL, NULL, 9, 25);

--
-- Déclencheurs `sprints`
--
DELIMITER $$
CREATE TRIGGER `check_team_velocity` BEFORE INSERT ON `sprints` FOR EACH ROW BEGIN
    -- Vérifier que la vélocité de l'équipe est positive
    IF NEW.VelociteEqPrj < 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = "Erreur : La vélocité d'un sprint ne peut pas etre négatif.";
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_team_velocity_update` BEFORE UPDATE ON `sprints` FOR EACH ROW BEGIN
    -- Vérifier que la vélocité de l'équipe est positive
    IF NEW.VelociteEqPrj < 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = "Erreur : La vélocité d'un sprint ne peut pas etre négatif.";
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_unique_active_sprint` BEFORE INSERT ON `sprints` FOR EACH ROW BEGIN
    IF EXISTS (
        SELECT 1
        FROM sprints
        WHERE IdEq = NEW.IdEq
        AND (
            (NEW.DateDebS BETWEEN DateDebS AND DateFinS)
            OR
            (NEW.DateFinS BETWEEN DateDebS AND DateFinS)
            OR
            (DateDebS BETWEEN NEW.DateDebS AND NEW.DateFinS)
        )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un autre sprint est déjà actif pour cette équipe dans cette période';
END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_unique_active_sprint_update` BEFORE UPDATE ON `sprints` FOR EACH ROW BEGIN
    IF EXISTS (
        SELECT 1
        FROM sprints
        WHERE IdEq = NEW.IdEq
        AND IdS != OLD.IdS
        AND (
            (NEW.DateDebS BETWEEN DateDebS AND DateFinS)
            OR
            (NEW.DateFinS BETWEEN DateDebS AND DateFinS)
            OR
            (DateDebS BETWEEN NEW.DateDebS AND NEW.DateFinS)
        )
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Un autre sprint est déjà actif pour cette équipe dans cette période';
END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `IdT` smallint(6) NOT NULL,
  `TitreT` varchar(50) NOT NULL,
  `UserStoryT` varchar(300) NOT NULL,
  `IdP` smallint(6) NOT NULL,
  `CoutT` enum('?','1','3','5','10','15','25','999') NOT NULL DEFAULT '?',
  `IdPriorite` tinyint(1) NOT NULL,
  `ApprouvedT` enum('0','1') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdP`, `CoutT`, `IdPriorite`, `ApprouvedT`) VALUES
(1, 'Création de l\'île des droits', 'Mise en place du niveau principale', 6, '10', 6, '1'),
(2, 'Création du niveau 1', 'Bibliothèque', 6, '15', 6, '1'),
(3, 'Création du niveau 3', 'Création du stadium', 6, '5', 5, '1'),
(4, 'Création de l\'inventaire', 'Collecte et sauvegarde des pièces de puzzle', 6, '3', 3, '1'),
(5, 'Création du niveau 2', 'Création de NYC', 6, '10', 6, '1'),
(6, 'Modélisation du temple', 'Modélisation du temple', 6, '3', 9, '1'),
(7, 'Modélisation des assets de la bibliothèque', 'Modélisation des assets de la bibliothèque', 6, '5', 5, '1'),
(8, 'Modélisation des lunettes', 'Modélisation des lunettes', 6, '10', 5, '1'),
(9, 'Modélisation du puzzle', 'Modélisation du puzzle', 6, '15', 5, '1'),
(10, 'Création des matériaux', 'Création des matériaux', 6, '10', 3, '1'),
(11, 'Easter Egg : Le jeu', 'Easter Egg : Le jeu', 6, '10', 1, '1'),
(12, 'Création du projets sur Unreal Engine 5', 'Création du projets sur Unreal Engine 5', 6, '10', 6, '1'),
(13, 'Avancement dans le niveau 1', 'Avancement dans le niveau 1', 6, '10', 7, '1'),
(14, 'Avancement dans le niveau 2', 'Avancement dans le niveau 2', 6, '15', 7, '1'),
(15, 'Avancement dans le niveau 3', 'Avancement dans le niveau 3', 6, '3', 7, '1'),
(16, 'Avancement dans l\'île des droits', 'Avancement dans l\'île des droits', 6, '3', 8, '1'),
(17, 'Finition du niveau 1', 'Finition du niveau 1', 6, '3', 3, '1'),
(18, 'Finition du niveau 2', 'Finition du niveau 2', 6, '5', 1, '1'),
(19, 'Finition du niveau 3', 'Finition du niveau 3', 6, '3', 1, '1'),
(20, 'Finition de l\'île des droits', 'Finition de l\'île des droits', 6, '15', 6, '1'),
(21, 'Création de l\'exécutable', 'Création de l\'exécutable', 6, '999', 7, '1'),
(22, 'Lord du jeu', 'Lord du jeu', 6, '5', 1, '1'),
(23, 'Création de la sauvegarde', 'Création de la sauvegarde', 6, '25', 1, '1'),
(24, 'Création du logo', 'Création du logo', 7, '10', 5, '1'),
(25, 'Création de la page profil', 'Création de la page profil', 7, '15', 5, '1'),
(26, 'Création de la page d\'accueil', 'Création de la page d\'accueil', 7, '25', 5, '1'),
(27, 'Création du système de like', 'Création du système de like', 7, '5', 3, '1'),
(28, 'Finition de la page d\'accueil', 'Finition de la page d\'accueil', 7, '3', 4, '1'),
(29, 'Finition de la page profil', 'Finition de la page profil', 7, '3', 3, '1'),
(30, 'Finition du système de like', 'Finition du système de like', 7, '3', 6, '1'),
(31, 'Création et finition du système de post', 'Création et finition du système de post', 7, '25', 7, '1'),
(32, 'Ajout de projet et définition du ScrumMaster assoc', 'Ajout de projet et définition du ScrumMaster associé ', 10, '25', 6, '1'),
(33, 'Tableau de bord de tous les projets / visualisatio', 'Tableau de bord de tous les projets / visualisation', 10, '10', 7, '1'),
(34, 'Tableau de bord des activités des personnes et équ', 'Tableau de bord des activités des personnes et équipes', 10, '25', 7, '1'),
(35, 'Ajout de membres au projet ', 'Ajout de membres au projet ', 10, '15', 5, '1'),
(36, 'Affectation des rôles ', 'Affectation des rôles ', 10, '3', 6, '1'),
(37, 'Création du product backlog', 'Création du product backlog', 10, '3', 5, '1'),
(38, 'Création d\'un sprint (définition du sprint backlog', 'Création d\'un sprint (définition du sprint backlogs , affectation de tâches, etc…)', 10, '15', 6, '1'),
(39, 'Saisie des rapports d’activité sprint : Mêlées et ', 'Saisie des rapports d’activité sprint : Mêlées et rétrospective', 10, '15', 8, '1'),
(40, 'Saisie des revues de sprint ', 'Saisie des revues de sprint ', 10, '25', 8, '1'),
(41, 'Gestion du bac à sable', 'Gestion du bac à sable', 10, '15', 7, '1'),
(42, 'Consulter son tableau de bord général : Synthèse d', 'Consulter son tableau de bord général : Synthèse de tous les projets ', 10, '10', 7, '1'),
(43, 'Consulter son tableau de bord individuel (liste de', 'Consulter son tableau de bord individuel (liste des projets où il est inscrit, liste de ses tâches) ', 10, '10', 6, '1'),
(44, 'Participer à une session de planning poker', 'Participer à une session de planning poker', 10, '10', 5, '1'),
(45, 'Ajouter une idée dans le bac à sable ', 'Ajouter une idée dans le bac à sable ', 10, '10', 7, '1'),
(46, 'Accès au tableau de bord du projet', 'Accès au tableau de bord du projet', 10, '10', 7, '1'),
(47, 'Ajouter 3 Triggers ', 'Ajouter 3 Triggers ', 10, '3', 5, '1'),
(48, 'Connexion/Inscription', 'Connexion/Inscription', 10, '5', 5, '1'),
(49, 'header ', 'header ', 10, '10', 5, '1'),
(50, 'Création d\'un projet', 'Création d\'un projet', 10, '1', 5, '1'),
(51, 'Définition du projet (description de l’app finale,', 'Définition du projet (description de l’app finale, attente du client)', 10, '3', 8, '1'),
(52, 'Affichage de la page d\'un projet', 'Affichage de la page d\'un projet', 10, '5', 5, '1'),
(53, 'Aside', 'Aside', 10, '5', 5, '1'),
(54, 'Gérer les tâches d’un projet, changement d’état, c', 'Gérer les tâches d’un projet, changement d’état, commentaire sur la tâche', 10, '5', 6, '1'),
(55, 'Lancer une session de planning poker', 'Lancer une session de planning poker', 10, '5', 5, '1'),
(56, 'Ajout d\'utilisateur', 'Ajout d\'utilisateur', 10, '5', 6, '1'),
(57, 'Création du vaisseau', 'Création du vaisseau', 9, '25', 5, '1'),
(58, 'Création de la table', 'Création de la table', 9, '25', 5, '1'),
(59, 'Création du trou noir', 'Création du trou noir', 9, '25', 5, '1'),
(60, 'Création du projet sur Unreal Engine 5', 'Création du projet sur Unreal Engine 5', 9, '25', 5, '1'),
(61, 'Création des textures', 'Création des textures', 9, '25', 5, '1');

--
-- Déclencheurs `taches`
--
DELIMITER $$
CREATE TRIGGER `verify_unique_task_per_team` BEFORE INSERT ON `taches` FOR EACH ROW BEGIN
    DECLARE task_count INT;

    SELECT COUNT(*) INTO task_count
    FROM taches
    WHERE TitreT = NEW.TitreT AND IdP = NEW.IdP;

    IF task_count > 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Erreur : Une tache avec ce titre existe déjà dans cette équipe.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IdU` smallint(6) NOT NULL,
  `NomU` varchar(50) NOT NULL,
  `PrenomU` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `MotDePasseU` varchar(255) NOT NULL,
  `SpecialiteU` enum('Développeur','Modeleur','Animateur','UI','IA','WebComm','Polyvalent') NOT NULL DEFAULT 'Polyvalent',
  `Statut` enum('Admin','User') NOT NULL DEFAULT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `mail`, `MotDePasseU`, `SpecialiteU`, `Statut`) VALUES
(1, 'Girardon', 'Jules', 'jgirardon@mail.com', '$2y$10$xNLYFy2UkDNnzAckOo5M/.8TGi68z4H8Oir0ZSqBtuYs06qtzY9uG', 'Développeur', 'User'),
(2, 'Charlot', 'Eudes', 'echarlot@mail.com', '$2y$10$gMlG.HAJKM6KeAa88NcZX.hDhMh23phiEqN.y5iExMcvMph10nQiK', 'Modeleur', 'User'),
(3, 'Dumas', 'Mathieu', 'mdumas@mail.com', '$2y$10$B2VhiwMDS7Vap2EcfQx6y.qj9/LecfM42vky4zMDSTcgHN3o0so6O', 'Polyvalent', 'User'),
(4, 'DRDB', 'Emilien', 'edrdb@mail.com', '$2y$10$tK1rOpHmze1SRfPmRBPiu.sxKbZwhM23jjbqCBDpWW6RY8pSqzYTu', 'UI', 'User'),
(5, 'Hullot', 'Johan', 'jhullot@mail.com', '$2y$10$FGL/aJYQCqPDz.GYQVbmBueGRBKmNj.PuhZ.dMB3uCCrXQMXMfQF2', 'Animateur', 'User'),
(6, 'Degironde', 'Antoine', 'adegironde@mail.com', '$2y$10$z7WJk0WOU8apoXa/YfqfX.ISX45/wUCXIeE8AYAFP7xStezsCnGfS', 'WebComm', 'User'),
(7, 'Destarac', 'Antonin', 'adestarac@mail.com', '$2y$10$DyXGsay8GUiY/ZhoFEOoPeuYKjyTalPpjKOBebO6FRj67A1.eCFf2', 'Développeur', 'User'),
(8, 'Vial', 'Lucas', 'lvial@mail.com', '$2y$10$NuQDNyw7CcohCTSDlZazseZjgVD0Y.qgBOnGlryRiuWb8GeP1.YAi', 'Développeur', 'User'),
(9, 'Haen', 'Remi', 'rhaen@mail.com', '$2y$10$SXFMM5fodwVZAaYpECXZkeun0mFaUcLj56DvAyqWi5qzvj.AOQYW.', 'Polyvalent', 'User'),
(10, 'Admin', 'Admin', 'admin@mail.com', '$2y$10$pw9lR.igowL97yoTTmtjBO0qwr0wLuZ/S5xsKzbI36a7kIhfLLT8W', 'Polyvalent', 'Admin');

--
-- Déclencheurs `utilisateurs`
--
DELIMITER $$
CREATE TRIGGER `before_delete_user_from_project` BEFORE DELETE ON `utilisateurs` FOR EACH ROW BEGIN
    DELETE FROM sprintbacklog
    WHERE IdU = OLD.IdU;
    END
$$
DELIMITER ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `coutmembretaches`
--
ALTER TABLE `coutmembretaches`
  ADD PRIMARY KEY (`IdU`,`IdT`),
  ADD KEY `FK_CoutMembreTaches_Taches` (`IdT`);

--
-- Index pour la table `equipesprj`
--
ALTER TABLE `equipesprj`
  ADD PRIMARY KEY (`IdEq`),
  ADD KEY `FK_Equipes_Projets` (`IdP`);

--
-- Index pour la table `etatstaches`
--
ALTER TABLE `etatstaches`
  ADD PRIMARY KEY (`IdEtat`);

--
-- Index pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  ADD PRIMARY KEY (`Id_Idee_bas`),
  ADD KEY `FK_IdeesBAS_Utilisateurs` (`IdU`),
  ADD KEY `FK_IdeesBAS_Equipes` (`IdEq`);

--
-- Index pour la table `membre_equipe`
--
ALTER TABLE `membre_equipe`
  ADD PRIMARY KEY (`IdEq`,`IdU`),
  ADD KEY `FK_MembreEquipe_Utilisateurs` (`IdU`);

--
-- Index pour la table `prioritestaches`
--
ALTER TABLE `prioritestaches`
  ADD PRIMARY KEY (`idPriorite`);

--
-- Index pour la table `projets`
--
ALTER TABLE `projets`
  ADD PRIMARY KEY (`IdP`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdR`);

--
-- Index pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD PRIMARY KEY (`IdU`,`IdP`,`IdR`),
  ADD KEY `FK_RoleUtil_Projets` (`IdP`),
  ADD KEY `FK_RoleUtil_Roles` (`IdR`);

--
-- Index pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  ADD PRIMARY KEY (`IdT`),
  ADD KEY `FK_SB_Sprints` (`IdS`),
  ADD KEY `FK_SB_Utilisateurs` (`IdU`),
  ADD KEY `FK_SB_EtatTaches` (`IdEtat`);

--
-- Index pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`IdS`),
  ADD KEY `FK_Sprints_Equipes` (`IdEq`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`IdT`),
  ADD KEY `FK_Taches_Projets` (`IdP`),
  ADD KEY `FK_Taches_Priorite` (`IdPriorite`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IdU`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `equipesprj`
--
ALTER TABLE `equipesprj`
  MODIFY `IdEq` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  MODIFY `Id_Idee_bas` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `projets`
--
ALTER TABLE `projets`
  MODIFY `IdP` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `IdR` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `sprints`
--
ALTER TABLE `sprints`
  MODIFY `IdS` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `IdT` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `IdU` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `coutmembretaches`
--
ALTER TABLE `coutmembretaches`
  ADD CONSTRAINT `FK_CoutMembreTaches_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`IdT`),
  ADD CONSTRAINT `FK_CoutMembreTaches_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `equipesprj`
--
ALTER TABLE `equipesprj`
  ADD CONSTRAINT `FK_Equipes_Projets` FOREIGN KEY (`IdP`) REFERENCES `projets` (`IdP`);

--
-- Contraintes pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  ADD CONSTRAINT `FK_IdeesBAS_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_IdeesBAS_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `membre_equipe`
--
ALTER TABLE `membre_equipe`
  ADD CONSTRAINT `FK_MembreEquipe_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_MembreEquipe_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD CONSTRAINT `FK_RoleUtil_Projets` FOREIGN KEY (`IdP`) REFERENCES `projets` (`IdP`),
  ADD CONSTRAINT `FK_RoleUtil_Roles` FOREIGN KEY (`IdR`) REFERENCES `roles` (`IdR`),
  ADD CONSTRAINT `FK_RoleUtil_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  ADD CONSTRAINT `FK_SB_EtatTaches` FOREIGN KEY (`IdEtat`) REFERENCES `etatstaches` (`IdEtat`),
  ADD CONSTRAINT `FK_SB_Sprints` FOREIGN KEY (`IdS`) REFERENCES `sprints` (`IdS`),
  ADD CONSTRAINT `FK_SB_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`IdT`),
  ADD CONSTRAINT `FK_SB_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD CONSTRAINT `FK_Sprints_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`);

--
-- Contraintes pour la table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `FK_Taches_Priorite` FOREIGN KEY (`IdPriorite`) REFERENCES `prioritestaches` (`idPriorite`),
  ADD CONSTRAINT `FK_Taches_Projets` FOREIGN KEY (`IdP`) REFERENCES `projets` (`IdP`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
