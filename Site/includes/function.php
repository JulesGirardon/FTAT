<?php

/**
 * Liste des rôles : Scrum Master, Product Owner, Member
 */
function getIdRole($role) {
    include "../includes/connexionBDD.php";
    $tab_role = ['Scrum Master', 'Product Owner', 'Member'];

    try {
        if (isset($bdd) && in_array($role, $tab_role)) {
            $sql_IdR_SM = "SELECT IdR FROM ftat.roles WHERE DescR=:role";
            $stmt = $bdd->prepare($sql_IdR_SM);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_ASSOC);

            return $id['IdR'];
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return null;
}

function getProjectWhereScrumMaster($IdU) {
    include "../includes/connexionBDD.php";

    try {
        if (isset($IdU) && isset($bdd)){
            $sql = "SELECT p.IdP, p.NomP
                FROM ftat.projets AS p
                JOIN ftat.rolesutilisateurprojet AS rup ON rup.IdP = p.IdP
                WHERE rup.IdU = :IdU AND rup.IdR = ftat.getIdRole('Scrum Master')";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':IdU', $IdU);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return null;
}

function getIdTeamFromProject($IdP) {
    include "../includes/connexionBDD.php";

    try {
        if (isset($IdP) && isset($bdd)){
            $sql = "SELECT e.IdEq
                FROM ftat.equipesprj AS e
                JOIN ftat.projets AS p ON p.IdEq = e.IdEq
                WHERE p.IdP = :IdP";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':IdP', $IdP);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['IdEq'];
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return null;
}