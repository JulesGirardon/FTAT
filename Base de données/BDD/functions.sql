DROP PROCEDURE IF EXISTS getAllTasks;
DELIMITER $$

CREATE PROCEDURE getAllTasks()
BEGIN
    SELECT
        t.IdT AS TacheID,
        t.TitreT AS Titre,
        t.UserStoryT AS UserStory,
        eq.NomEqPrj AS NomEquipe,
        t.CoutT AS Cout,
        p.Priorite AS Priorite,
        es.Etat AS EtatTache
    FROM
        taches t
    JOIN
        equipesprj eq ON t.IdEq = eq.IdEq
    JOIN
        prioritestaches p ON t.IdPriorite = p.idPriorite
    JOIN
        sprintbacklog sb ON t.IdT = sb.IdT
    JOIN
        etatstaches es ON sb.IdEtat = es.IdEtat;
END$$

DELIMITER ;
