DROP PROCEDURE IF EXISTS getAllTasks;
DROP FUNCTION IF EXISTS insertCoutMembreTache;
DROP PROCEDURE IF EXISTS getAllVotes;

DELIMITER $$

CREATE PROCEDURE getAllTasks(IN ID INT)
BEGIN
    SELECT * FROM taches
    WHERE taches.IdEq = ID;
END$$


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
END $$

DELIMITER $$

CREATE PROCEDURE getAllVotes(IN ID INT)
BEGIN
    SELECT *
    FROM coutmembretaches
    JOIN taches ON coutmembretaches.idT = taches.idT
    WHERE taches.IdEq = ID;
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE displayAllDifficulties()
BEGIN
  DECLARE enum_values TEXT;
  DECLARE start_pos INT;
  DECLARE end_pos INT;
  DECLARE current_value VARCHAR(10);
  
  -- Créer une table temporaire pour stocker les valeurs
  CREATE TEMPORARY TABLE temp_enum_values (
    value VARCHAR(10)
  );
  
  -- Extrait les informations du champ CoutT
  SELECT COLUMN_TYPE INTO enum_values
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_NAME = 'taches'
  AND COLUMN_NAME = 'CoutT';
  
  -- Extrait la partie entre 'enum(' et ')' pour obtenir uniquement les valeurs de l'énumération
  SET enum_values = SUBSTRING(enum_values, 6, CHAR_LENGTH(enum_values) - 6);
  
  -- Boucle pour parcourir les valeurs de l'énumération et les insérer dans la table temporaire
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
    
    -- Insère chaque valeur dans la table temporaire
    INSERT INTO temp_enum_values (value) VALUES (current_value);
  END WHILE;
  
  -- Sélectionne toutes les valeurs dans un tableau
  SELECT * FROM temp_enum_values;
  
  -- Supprime la table temporaire
  DROP TEMPORARY TABLE IF EXISTS temp_enum_values;
END$$



DELIMITER ;

