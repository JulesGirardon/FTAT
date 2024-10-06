INSERT INTO `projets` (`IdP`, `NomP`, `DescriptionP`, `DateDebutP`, `DateFinP`)
VALUES 
    ('1', 'PMLK', 'Jeu 3D : Hommage à Martin Luther King', '2024-04-01', '2024-07-01'),
    ('2', 'Stack UnderFlow', 'Site Internet : Blog', '2024-02-01', '2024-03-01'),
    ('3', 'Event horizon 1', 'Jeu 3D : Immersion spaciale partie 1', '2023-10-01', '2024-12-31'),
    ('4', 'Event horizon 2 : The Last Gabion', 'Jeu 3D : Immersion spaciale partie 2', '2025-01-01', '2025-08-01'),
    ('5', 'FTAT', 'Site Internet : Application de la Méthode agile', '2024-09-01', '2024-10-10');

INSERT INTO `equipesprj`(`IdEq`, `NomEqPrj`, `IdP`)
VALUES
    ('1','Flamby Interactive','1'),
    ('2','Ligne 11','2'),
    ('3','Pesquet Team','3'),
    ('4','Moon Studio','4'),
    ('5','Webeco','5');


INSERT INTO `roles`(`IdR`, `DescR`)
VALUES
('1', 'Admin'),
('2', 'Scrum Master'),
('3', 'Développeur'),
('4', 'Product Owner'),
('5', 'Client');

INSERT INTO `idees_bac_a_sable`(`Id_Idee_bas`, `desc_Idee_bas`, `IdU`, `IdEq`)
VALUES 
('1','Idée : Mettre des musiques de Beyonce','1','1'),
('2','Idée : Cacher un livre dans une bibliothèque','3','1'),
('3','Idée : Ajouter un système d upload d images','2','2'),
('4','Idée : Permettre aux utilsateurs de se suivre entre eux','1','2'),
('5','Idée : Ajouter différentes fonctionnalités de déplacement','1','3'),
('6','Idée : Diversifier le bestiaire','1','3'),
('7','Idée : Permettre un backtracking facile','2','4'),
('8','Idée : Modéliser un nouveau type de vaisseau spacial','8','4'),
('9','Idée : Implémenter la fonctionnalité de planning poker','9','5'),
('10','Idée : Implémenter la fonctionnalité de bac à idées','1','5');

INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdP`, `CoutT`, `IdPriorite`,`ApprouvedT`) VALUES
(1, 'Tache 1', 'En tant que developpeur, je veux creer une interface', 1, '5', 1, '0'),
(2, 'Tache 2', 'En tant qu utilisateur, je veux une meilleure navigation', 2, '3', 2, '0'),
(3, 'Tache 3', 'Manger le panier de basket', 3, '1', 1, '0'),
(4, 'Tache 4', 'Implementer un systeme de gestion des stocks', 4, '8', 2, '0'),
(5, 'Tache 5', 'Creer une API pour l integration avec des systemes tiers', 5, '15', 1, '0'),
(6, 'Tache 6', 'Developper une fonctionnalite de reporting personnalisable', 1, '25', 3, '0'),
(7, 'Tache 7', 'Mettre en place un systeme de gestion des acces', 2, '7', 2, '0'),
(8, 'Tache 8', 'Optimiser les performances du backend', 3, '15', 1, '0'),
(9, 'Tache 9', 'Creer une application mobile native', 4, '5', 3, '0'),
(10, 'Tache 10', 'Integrer un systeme de machine learning pour la prediction des ventes', 1, '999', 2, '0'),
(11, 'Tache 11', 'Ameliorer l experience utilisateur avec des animations fluides', 2, '?', 3, '0'),
(12, 'Tache 12', 'Creer un systeme de gestion des projets integres', 3, '10', 1, '0'),
(13, 'Tache 13', 'Developper une fonctionnalite de collaboration en temps reel', 4, '1', 2, '0');

--  insert sprints & sprintback (Je ne comprends pas comment ça marche svp x)))-- 
--  Marche pas  function ftat.getIdRole doesnt exist (elle existe) --
INSERT INTO `rolesutilisateurprojet`(`IdU`, `IdP`, `IdR`)
VALUES
('1','5','1'),
('2','5','2'),
('3','1','3'),
('4','1','1'),
('5','5','3'),
('6','5','3'),
('7','5','3'),
('8','4','3'),
('9','4','3');

INSERT INTO `membre_equipe`(`IdEq`, `IdU`)
VALUES
('1','1'),
('1','2'),
('1','3'),
('1','4'),
('1','9'),
('2','1'),
('2','2'),
('3','1'),
('3','2'),
('3','3'),
('3','8'),
('3','9'),
('4','1'),
('4','2'),
('4','3'),
('4','8'),
('4','9'),
('5','1'),
('5','2'),
('5','6'),
('5','7'),
('5','8'),
('5','9');