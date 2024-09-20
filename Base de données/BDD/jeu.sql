-- Inserts pour la table `equipesprj`
INSERT INTO `equipesprj` (`IdEq`, `NomEqPrj`, `VelociteEqPrj`) VALUES
(1, 101, 15),
(2, 102, 20);

-- Inserts pour la table `idees_bac_a_sable`
INSERT INTO `idees_bac_a_sable` (`Id_Idee_bas`, `desc_Idee_bas`, `IdU`, `IdEq`) VALUES
(1, 'Nouvelle fonctionnalité X', 1, 1),
(2, 'Amélioration de l’interface Y', 2, 2);

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
(2, 'Tâche 2', 'En tant qu’utilisateur, je veux une meilleure navigation', 2, '3', 2),
(3, 'Tâche 3', 'Manger le panier de basket', 2, '1', 1),
(4, 'Tâche 4', 'Implémenter un système de gestion des stocks', 1, '8', 2),
(5, 'Tâche 5', 'Créer une API pour lintégration avec des systèmes tiers', 1, '12', 1),
(6, 'Tâche 6', 'Développer une fonctionnalité de reporting personnalisable', 1, '9', 3),
(7, 'Tâche 7', 'Mettre en place un système de gestion des accès', 1, '7', 2),
(8, 'Tâche 8', 'Optimiser les performances du backend', 1, '15', 1),
(9, 'Tâche 9', 'Créer une application mobile native', 1, '11', 3),
(10, 'Tâche 10', 'Intégrer un système de machine learning pour la prédiction des ventes', 1, '14', 2),
(11, 'Tâche 11', 'Améliorer lexpérience utilisateur avec des animations fluides', 1, '6', 3),
(12, 'Tâche 12', 'Créer un système de gestion des projets intégrés', 1, '10', 1),
(13, 'Tâche 13', 'Développer une fonctionnalité de collaboration en temps réel', 1, '13', 2);

-- Inserts pour la table `utilisateurs`
INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `MotDePAsseU`, `SpecialiteU`) VALUES
(1, 'Durand', 'Paul', 'password123', 'Développeur'),
(2, 'Martin', 'Sophie', 'password456', 'Modeleur');
