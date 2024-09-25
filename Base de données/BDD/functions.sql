DROP PROCEDURE IF EXISTS getAllTasks;
DROP FUNCTION IF EXISTS insertCoutMembreTache;

DELIMITER $$

CREATE PROCEDURE getAllTasks(IN ID INT)
BEGIN
    SELECT * FROM taches
    WHERE taches.IdEq = ID;
END$$

DELIMITER ;

DELIMITER //

CREATE FUNCTION insertCoutMembreTache(pIdU SMALLINT, pIdT INT, pCommentaire VARCHAR(255), pCoutMT ENUM('?', '1', '3', '5', '10', '15', '25', '999')) RETURNS INT
BEGIN
    DECLARE insertedId INT;
    
    INSERT INTO CoutMembreTaches (
        IdU, IdT, Commentaire, CoutMT
    ) VALUES (
        pIdU, pIdT, pCommentaire, pCoutMT
    );
    
    SET insertedId = LAST_INSERT_ID();
    
    RETURN insertedId;
END //

DELIMITER ;
