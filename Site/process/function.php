<?php

/**
 * Liste des rôles : Scrum Master, Product Owner, Member
 */
function getIdRole($role) {
    include "../includes/connexionBDD.php";

    $tab_role = ['Scrum Master', 'Product Owner', 'Member'];

    if (isset($bdd) && in_array($role, $tab_role)) {
        $sql_IdR_SM = "SELECT IdR FROM ftat.roles WHERE DescR=:role";
        $stmt = $bdd->prepare($sql_IdR_SM);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC);

        return $id['IdR'];
    }
    return -1;
}