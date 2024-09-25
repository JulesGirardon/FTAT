-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 20 sep. 2024 à 08:13
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
-- Base de données : `agiletools`
--

-- --------------------------------------------------------

--
-- Structure de la table `equipesprj`
--

CREATE TABLE `equipesprj` (
  `IdEq` smallint(11) NOT NULL AUTO_INCREMENT,
  `NomEqPrj` VARCHAR(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(3, 'Terminé et TestUnitaire réalisé'),
(4, 'Test Fonctionnel Réalisé / Module intégré dans ver'),
(5, 'intégré dans version de production');

-- --------------------------------------------------------

--
-- Structure de la table `idees_bac_a_sable`
--

CREATE TABLE `idees_bac_a_sable` (
  `Id_Idee_bas` int(11) NOT NULL AUTO_INCREMENT,
  `desc_Idee_bas` varchar(300) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(6, 'MUST (MoSCoW)', 5),
(7, 'SHOULD (MoSCoW)', 4),
(8, 'Could ', 2),
(9, 'WONT (MoSCoW)', 0);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `IdR` smallint(6) NOT NULL,
  `DescR` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `rolesutilisateurprojet`
--

CREATE TABLE `rolesutilisateurprojet` (
  `IdU` smallint(6) NOT NULL,
  `IdR` smallint(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sprintbacklog`
--

CREATE TABLE `sprintbacklog` (
  `IdT` int(11) NOT NULL,
  `IdS` smallint(6) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEtat` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sprints`
--

CREATE TABLE `sprints` (
  `IdS` smallint(6) NOT NULL AUTO_INCREMENT,
  `DateDEbS` date NOT NULL,
  `DateFinS` date NOT NULL,
  `RetrospectiveS` varchar(300) DEFAULT NULL,
  `RevueDeSprint` varchar(300) DEFAULT NULL,
  `IdEq` smallint(6) NOT NULL,
    `VelociteEqPrj` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `IdT` int(11) NOT NULL AUTO_INCREMENT,
  `TitreT` varchar(50) NOT NULL,
  `UserStoryT` varchar(300) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  `CoutT` enum('?','1','3','5','10','15','25','999') NOT NULL DEFAULT '?',
  `IdPriorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IdU` smallint(6) NOT NULL AUTO_INCREMENT,
  `NomU` varchar(50) NOT NULL,
  `PrenomU` varchar(50) NOT NULL,
  `MotDePAsseU` varchar(15) NOT NULL,
  `SpecialiteU` enum('Développeur','Modeleur','Animateur','UI','IA','WebComm','Polyvalent') NOT NULL DEFAULT 'Polyvalent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `equipesprj`
--
ALTER TABLE `equipesprj`
  ADD PRIMARY KEY (`IdEq`);

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
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdEq` (`IdEq`);

--
-- Index pour la table `prioritestaches`
--
ALTER TABLE `prioritestaches`
  ADD PRIMARY KEY (`idPriorite`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdR`);

--
-- Index pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD PRIMARY KEY (`IdR`,`IdEq`),
  ADD KEY `IdR` (`IdR`),
  ADD KEY `IdEq` (`IdEq`),
  ADD KEY `FK_RoleUtil_Utilisateurs` (`IdU`);

--
-- Index pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  ADD PRIMARY KEY (`IdT`),
  ADD KEY `IdS` (`IdS`),
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdEtat` (`IdEtat`);

--
-- Index pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`IdS`),
  ADD KEY `IdEq` (`IdEq`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`IdT`),
  ADD KEY `IdPriorite` (`IdPriorite`),
  ADD KEY `IndexIdEq` (`IdEq`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IdU`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  ADD CONSTRAINT `FK_IdeeBAS_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_IdeesBAS_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD CONSTRAINT `FK_RoleUtil_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
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
  ADD CONSTRAINT `FK_TachesEquipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_Taches_Priorite` FOREIGN KEY (`IdPriorite`) REFERENCES `prioritestaches` (`idPriorite`);

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
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
