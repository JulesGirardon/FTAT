-- Inserts pour la table `equipesprj`
INSERT INTO `equipesprj` (`IdEq`, `NomEqPrj`) VALUES
(1, 'Équipe Alpha'),
(2, 'Équipe Beta');

-- Inserts pour la table `idees_bac_a_sable`
INSERT INTO `idees_bac_a_sable` (`Id_Idee_bas`, `desc_Idee_bas`, `IdU`, `IdEq`) VALUES
(1, 'Proposition pour nouvelle API', 1, 1),
(2, 'Idée damélioration UX', 2, 2);

-- Inserts pour la table `roles`
INSERT INTO `roles` (`IdR`, `DescR`) VALUES
(1, 'Développeur'),
(2, 'Scrum Master'),
(3, 'Product Owner');

-- Inserts pour la table `rolesutilisateurprojet`
INSERT INTO `rolesutilisateurprojet` (`IdU`, `IdP`, `IdR`) VALUES
(1, 1, 1), -- Paul, Développeur sur le projet 1
(2, 2, 2); -- Sophie, Scrum Master sur le projet 2

-- Inserts pour la table `sprintbacklog`
INSERT INTO `sprintbacklog` (`IdT`, `IdS`, `IdU`, `IdEtat`) VALUES
(1, 1, 1, 1), -- Tâche 1, Sprint 1, Utilisateur 1, À faire
(2, 2, 2, 2); -- Tâche 2, Sprint 2, Utilisateur 2, En cours

-- Inserts pour la table `sprints`
INSERT INTO `sprints` (`IdS`, `DateDebS`, `DateFinS`, `RetrospectiveS`, `RevueDeSprint`, `IdEq`, `VelociteEqPrj`) VALUES
(1, '2024-09-01', '2024-09-14', 'Bon travail déquipe', 'Produit prêt pour test', 1, 20),
(2, '2024-09-15', '2024-09-28', 'Processus damélioration', 'Produit amélioré', 2, 15);

-- Inserts pour la table `taches`
-- Inserts pour la table `taches`
INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdEq`, `CoutT`, `IdPriorite`) VALUES
(1, 'Tache 1', 'En tant que developpeur, je veux creer une interface', 1, '5', 1),
(2, 'Tache 2', 'En tant qu utilisateur, je veux une meilleure navigation', 2, '3', 2),
(3, 'Tache 3', 'Manger le panier de basket', 2, '1', 1),
(4, 'Tache 4', 'Implementer un systeme de gestion des stocks', 1, '8', 2),
(5, 'Tache 5', 'Creer une API pour l integration avec des systemes tiers', 1, '12', 1),
(6, 'Tache 6', 'Developper une fonctionnalite de reporting personnalisable', 1, '9', 3),
(7, 'Tache 7', 'Mettre en place un systeme de gestion des acces', 1, '7', 2),
(8, 'Tache 8', 'Optimiser les performances du backend', 1, '15', 1),
(9, 'Tache 9', 'Creer une application mobile native', 1, '11', 3),
(10, 'Tache 10', 'Integrer un systeme de machine learning pour la prediction des ventes', 1, '14', 2),
(11, 'Tache 11', 'Ameliorer l experience utilisateur avec des animations fluides', 1, '6', 3),
(12, 'Tache 12', 'Creer un systeme de gestion des projets integres', 1, '10', 1),
(13, 'Tache 13', 'Developper une fonctionnalite de collaboration en temps reel', 1, '13', 2);

-- Inserts pour la table `utilisateurs`
INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `mail`, `MotDePasseU`, `SpecialiteU`, `Statut`) VALUES
(1, 'Durand', 'Paul', 'paul.durand@mail.com', 'password123', 'Développeur', 'User'),
(2, 'Martin', 'Sophie', 'sophie.martin@mail.com', 'password456', 'Scrum Master', 'Admin');
