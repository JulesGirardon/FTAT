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