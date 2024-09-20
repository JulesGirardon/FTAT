-- Inserts pour la table `equipesprj`
INSERT INTO `equipesprj` (`IdEq`, `NomEqPrj`, `VelociteEqPrj`) VALUES
(1, 101, 15),
(2, 102, 20);

-- Inserts pour la table `etatstaches` (les données sont déjà présentes)
-- (1, 'A faire'),
-- (2, 'En cours'),
-- (3, 'Terminé et TestUnitaire réalisé'),
-- (4, 'Test Fonctionnel Réalisé / Module intégré dans ver'),
-- (5, 'intégré dans version de production');

-- Inserts pour la table `idees_bac_a_sable`
INSERT INTO `idees_bac_a_sable` (`Id_Idee_bas`, `desc_Idee_bas`, `IdU`, `IdEq`) VALUES
(1, 'Nouvelle fonctionnalité X', 1, 1),
(2, 'Amélioration de l’interface Y', 2, 2);

-- Inserts pour la table `prioritestaches` (les données sont déjà présentes)
-- (1, '1', 1),
-- (2, '2', 2),
-- (3, '3', 3),
-- (4, '4', 4),
-- (5, '5', 5),
-- (6, 'MUST (MoSCoW)', 5),
-- (7, 'SHOULD (MoSCoW)', 4),
-- (8, 'Could', 2),
-- (9, 'WONT (MoSCoW)', 0);

-- Inserts pour la table `roles`
INSERT INTO `roles` (`IdR`, `DescR`) VALUES
(1, 'Développeur'),
(2, 'Scrum Master');

-- Inserts pour la table `rolesutilisateurprojet`
INSERT INTO `rolesutilisateurprojet` (`IdU`, `IdR`, `IdEq`) VALUES
(1, 1, 1),
(2, 2, 2);

-- Inserts pour la table `sprintbacklog`
INSERT INTO `sprintbacklog` (`IdT`, `IdS`, `IdU`, `IdEtat`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 2);

-- Inserts pour la table `sprints`
INSERT INTO `sprints` (`IdS`, `DateDEbS`, `DateFinS`, `RetrospectiveS`, `RevueDeSprint`, `IdEq`) VALUES
(1, '2024-09-01', '2024-09-14', 'Bon travail d\'équipe', 'Produit prêt pour test', 1),
(2, '2024-09-15', '2024-09-28', 'Amélioration du processus', 'Produit optimisé', 2);

-- Inserts pour la table `taches`
INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdEq`, `CoutT`, `IdPriorite`) VALUES
(1, 'Tâche 1', 'En tant que développeur, je veux créer une interface', 1, '5', 1),
(2, 'Tâche 2', 'En tant qu’utilisateur, je veux une meilleure navigation', 2, '3', 2);

-- Inserts pour la table `utilisateurs`
INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `MotDePAsseU`, `SpecialiteU`) VALUES
(1, 'Durand', 'Paul', 'password123', 'Développeur'),
(2, 'Martin', 'Sophie', 'password456', 'Modeleur');
