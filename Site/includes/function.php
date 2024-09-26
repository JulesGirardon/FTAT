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

function getEquipeFromUser($id_user){
    include 'connexionBDD.php';
    try {
        if (isset($bdd)) {
            $sql = "SELECT ftat.membre_equipe.IdEq FROM ftat.membre_equipe WHERE ftat.membre_equipe.IdU = :id_user";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($id && isset($id['IdEq'])) {
                return $id['IdEq'];
            } else {
                return null;
            }
        }
    } catch (PDOException $e) {
        return  null;
    }
}

function getProjetsFromUser($id_user) {
    include 'connexionBDD.php';

    $id_equipe = getEquipeFromUser($id_user);
    if (!$id_equipe) {
        return null;
    }

    try {
        if (isset($bdd)) {
            $sql = "SELECT * FROM ftat.projets WHERE ftat.projets.IdEq = :id_equipe";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_equipe', $id_equipe);
            $stmt->execute();

            $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($projects) {
                return $projects;
            } else {
                return null;
            }
        }
    } catch (PDOException $e) {
        return null;
    }
}

function getMembresFromProjet($id_projet) {
    include 'connexionBDD.php';

    try{
        if(isset($bdd)) {
            $sql = "SELECT * FROM ftat.utilisateurs JOIN ftat.membre_equipe ON utilisateurs.IdU = membre_equipe.IdU JOIN ftat.projets ON ftat.projets.IdEq = membre_equipe.IdEq WHERE projets.IdP = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_projet',$id_projet);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($users){
                return $users;
            } else{
                return null;
            }
        }

    } catch (PDOExeption $e){
        return null;
    }
}

function getUserFromProject($id_projet) {
    include 'connexionBDD.php';

    try{
        if(isset($bdd)) {
            $sql = "SELECT * FROM ftat.utilisateurs AS u JOIN ftat.rolesutilisateurprojet AS rup ON rup.IdU = u.IdU WHERE rup.IdP = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_projet',$id_projet);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($users){
                return $users;
            } else{
                return null;
            }
        }
    } catch (PDOException $e){
        return null;
    }
}

function getTachesFromProjet($id_projet) {
    include 'connexionBDD.php';

    try{
        if (isset($bdd)) {
            $sql = "SELECT * FROM ftat.taches WHERE ftat.taches.IdP = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_projet',$id_projet);
            $stmt->execute();

            $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($taches){
                return $taches;
            } else{
                return null;
            }
        }
    } catch (PDOExeption $e){
        return null;
    }
}

function getRetroFromSprint($id_sprint) {
    include 'connexionBDD.php';

    if (isset($bdd, $id_sprint)) {
        $sql = "SELECT s.RetrospectiveS FROM ftat.sprints AS s WHERE s.IdS = :idSprint";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idSprint', $id_sprint);
        $stmt->execute();
        $retro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $retro['RetrospectiveS'] ? $retro['RetrospectiveS'] : null;
    }
}