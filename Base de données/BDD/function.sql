DELIMITER $$

CREATE FUNCTION getIdRole(role VARCHAR(50))
    RETURNS INT
    DETERMINISTIC
BEGIN
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
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS getAllTasks;
DROP FUNCTION IF EXISTS insertCoutMembreTache;
DROP PROCEDURE IF EXISTS getAllVotes;
DROP PROCEDURE IF EXISTS displayAllDifficulties;
DELIMITER $$

CREATE PROCEDURE getAllTasks(IN ID INT)
BEGIN
SELECT * FROM taches
WHERE taches.IdP = ID;
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

DROP FUNCTION IF EXISTS insertCoutTache;
DELIMITER $$
CREATE FUNCTION insertCoutTache(
    pIdT INT,
    Cout ENUM('?', '1', '3', '5', '10', '15', '25', '999'),
    Approuved ENUM('0', '1')
        ) RETURNS INT
BEGIN
    DECLARE affectedRows INT;
UPDATE Taches
SET CoutT = Cout, ApprouvedT = Approuved
WHERE IdT = pIdT;
SET affectedRows = ROW_COUNT();
RETURN affectedRows;
END $$

DELIMITER ;

DROP PROCEDURE IF EXISTS getAllVotes;
DELIMITER $$
CREATE PROCEDURE getAllVotes(IN ID INT)
BEGIN
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

DELIMITER ;

DROP PROCEDURE IF EXISTS getAllComments;
DELIMITER $$
CREATE PROCEDURE getAllComments(IN pIdT SMALLINT(6))
BEGIN
SELECT Commentaire
FROM CoutMembreTaches
WHERE IdT = pIdT;
END $$
DELIMITER ;



DELIMITER $$
CREATE PROCEDURE displayAllDifficulties()
BEGIN
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
DELIMITER ;