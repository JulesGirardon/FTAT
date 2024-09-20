-- Base de données : `agiletools`

-- --------------------------------------------------------

-- Table `equipesprj` (équipes de projet)
CREATE TABLE `equipesprj` (
  `IdEq` smallint(6) NOT NULL AUTO_INCREMENT,     -- Clé primaire auto-incrémentée
  `NomEqPrj` VARCHAR(50) NOT NULL,                -- Augmentation de la taille du nom de l'équipe
  PRIMARY KEY (`IdEq`)                            -- Définition de la clé primaire ici
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `etatstaches` (états des tâches)
CREATE TABLE `etatstaches` (
  `IdEtat` smallint(4) NOT NULL AUTO_INCREMENT,   -- Ajout de l'AUTO_INCREMENT pour simplifier
  `Etat` varchar(50) NOT NULL,
  PRIMARY KEY (`IdEtat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données initiales de `etatstaches`
INSERT INTO `etatstaches` (`IdEtat`, `Etat`) VALUES
(1, 'A faire'),
(2, 'En cours'),
(3, 'Terminé et TestUnitaire réalisé'),
(4, 'Test Fonctionnel Réalisé / Module intégré dans version'),
(5, 'Intégré dans version de production');

-- --------------------------------------------------------

-- Table `idees_bac_a_sable` (idées dans le bac à sable)
CREATE TABLE `idees_bac_a_sable` (
  `Id_Idee_bas` int(11) NOT NULL AUTO_INCREMENT,  -- AUTO_INCREMENT ajouté pour les idées
  `desc_Idee_bas` varchar(300) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  PRIMARY KEY (`Id_Idee_bas`),                    -- Clé primaire définie ici
  KEY `IdU` (`IdU`),
  KEY `IdEq` (`IdEq`),
  CONSTRAINT `FK_IdeeBAS_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  CONSTRAINT `FK_IdeesBAS_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `prioritestaches` (priorités des tâches)
CREATE TABLE `prioritestaches` (
  `idPriorite` tinyint(1) NOT NULL AUTO_INCREMENT,  -- AUTO_INCREMENT pour simplifier la gestion des priorités
  `Priorite` varchar(15) NOT NULL,
  `valPriorite` tinyint(1) NOT NULL,
  PRIMARY KEY (`idPriorite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Données initiales de `prioritestaches`
INSERT INTO `prioritestaches` (`idPriorite`, `Priorite`, `valPriorite`) VALUES
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

-- Table `roles` (rôles des utilisateurs)
CREATE TABLE `roles` (
  `IdR` smallint(6) NOT NULL AUTO_INCREMENT,       -- AUTO_INCREMENT ajouté pour les rôles
  `DescR` varchar(100) NOT NULL,
  PRIMARY KEY (`IdR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `rolesutilisateurprojet` (liaison utilisateurs-projets-rôles)
CREATE TABLE `rolesutilisateurprojet` (
  `IdU` smallint(6) NOT NULL,
  `IdR` smallint(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  PRIMARY KEY (`IdU`, `IdR`, `IdEq`),              -- Utilisation d'une clé primaire composée
  KEY `FK_RoleUtil_Utilisateurs` (`IdU`),
  CONSTRAINT `FK_RoleUtil_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  CONSTRAINT `FK_RoleUtil_Roles` FOREIGN KEY (`IdR`) REFERENCES `roles` (`IdR`),
  CONSTRAINT `FK_RoleUtil_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `sprintbacklog` (backlog des sprints)
CREATE TABLE `sprintbacklog` (
  `IdT` int(11) NOT NULL,
  `IdS` smallint(6) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEtat` smallint(6) NOT NULL,
  PRIMARY KEY (`IdT`),
  KEY `IdS` (`IdS`),
  KEY `IdU` (`IdU`),
  KEY `IdEtat` (`IdEtat`),
  CONSTRAINT `FK_SB_EtatTaches` FOREIGN KEY (`IdEtat`) REFERENCES `etatstaches` (`IdEtat`),
  CONSTRAINT `FK_SB_Sprints` FOREIGN KEY (`IdS`) REFERENCES `sprints` (`IdS`),
  CONSTRAINT `FK_SB_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`IdT`),
  CONSTRAINT `FK_SB_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `sprints` (sprints)
CREATE TABLE `sprints` (
  `IdS` smallint(6) NOT NULL AUTO_INCREMENT,       -- AUTO_INCREMENT pour les sprints
  `DateDebS` date NOT NULL,
  `DateFinS` date NOT NULL,
  `RetrospectiveS` varchar(300) DEFAULT NULL,
  `RevueDeSprint` varchar(300) DEFAULT NULL,
  `IdEq` smallint(6) NOT NULL,
  `VelociteEqPrj` decimal(10,0) NOT NULL,
  PRIMARY KEY (`IdS`),
  KEY `IdEq` (`IdEq`),
  CONSTRAINT `FK_Sprints_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `taches` (tâches)
CREATE TABLE `taches` (
  `IdT` int(11) NOT NULL AUTO_INCREMENT,           -- AUTO_INCREMENT pour les tâches
  `TitreT` varchar(50) NOT NULL,
  `UserStoryT` varchar(300) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  `CoutT` enum('?', '1', '3', '5', '10', '15', '25', '999') NOT NULL DEFAULT '?',
  `IdPriorite` tinyint(1) NOT NULL,
  PRIMARY KEY (`IdT`),
  KEY `IdPriorite` (`IdPriorite`),
  KEY `IndexIdEq` (`IdEq`),
  CONSTRAINT `FK_TachesEquipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  CONSTRAINT `FK_Taches_Priorite` FOREIGN KEY (`IdPriorite`) REFERENCES `prioritestaches` (`idPriorite`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table `utilisateurs` (utilisateurs)
CREATE TABLE `utilisateurs` (
  `IdU` smallint(6) NOT NULL AUTO_INCREMENT,       -- AUTO_INCREMENT pour les utilisateurs
  `NomU` varchar(50) NOT NULL,
  `PrenomU` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `MotDePAsseU` varchar(255) NOT NULL,
  `SpecialiteU` enum('Développeur', 'Modeleur', 'Animateur', 'UI', 'IA', 'WebComm', 'Polyvalent') NOT NULL DEFAULT 'Polyvalent',
  `Statut` enum('Admin', 'User') NOT NULL DEFAULT 'User',
  PRIMARY KEY (`IdU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

