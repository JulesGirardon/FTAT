<?php

/**
 * Liste des rôles : Scrum Master, Product Owner, Member
 */
function getIdRole($role) {
    include 'connexionBDD.php';

    $tab_role = ['Scrum Master', 'Product Owner', 'Member'];

    if (isset($bdd) && in_array($role, $tab_role)) {
        $sql_IdR_SM = "SELECT IdR FROM ftat.roles WHERE DescR=:role";
        $stmt = $bdd->prepare($sql_IdR_SM);
        $stmt->bindParam(':role', $role);
        $stmt->execute();
        $id = $stmt->fetch(PDO::FETCH_ASSOC);

        return $id['IdR'];
    }
    return null;
}

function getEquipesFromUser($id_user){
    include 'connexionBDD.php';
    try {
        $sql = "SELECT membre_equipe.IdEq FROM membre_equipe WHERE membre_equipe.IdU = :id_user";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $id = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($id && isset($id['IdEq'])) {
            return $id['IdEq']; 
        } else {
            return null;  
        }
    } catch (PDOException $e) {
        return  null;
    }
}

function getProjetsFromUser($id_user) {
    include 'connexionBDD.php';

    try {
        $sql = "SELECT * FROM projets JOIN rolesutilisateurprojet ON projets.IdP = rolesutilisateurprojet.IdP WHERE rolesutilisateurprojet.IdU = :id_user";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($projects) {
            return $projects;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        return null;
    }
}

function getMembresFromProjet($id_projet){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM utilisateurs JOIN rolesutilisateurprojet ON utilisateurs.IdU = rolesutilisateurprojet.IdU  WHERE rolesutilisateurprojet.IdP = :id_projet";
        $stmt = $bdd->prepare($sql);
        $stmt->bindValue(':id_projet', $id_projet);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($users){
            return $users;
        } else{
            return null;
        }
    } catch (PDOException $e){
        return null;
    }
}

function getTachesFromProjet($id_projet){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM taches JOIN prioritestaches ON taches.IdPriorite = prioritestaches.idPriorite WHERE taches.IdP = :id_projet ORDER BY prioritestaches.valPriorite DESC";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('id_projet',$id_projet);
        $stmt->execute();

        $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($taches){
            return $taches;
        } else{
            return null;
        }
    } catch (PDOException $e){
        return null;
    }
}

function getTaskFromUserInProject($id_user,$id_projet){
    include 'connexionBDD.php';
    try{
        $sql = "SELECT * FROM taches JOIN sprintbacklog ON taches.IdT = sprintbacklog.IdT WHERE sprintbacklog.IdU = :id_user AND taches.IdP = :id_projet";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('id_user',$id_user);
        $stmt->bindParam('id_projet',$id_projet);
        $stmt->execute();

        $tache = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($tache){
            return $tache;
        } else{
            return null;
        }
    }catch (PDOException $e){
        return null;

    }
}

function getRoleFromUserInProject($id_user,$id_projet){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM roles JOIN rolesutilisateurprojet ON roles.IdR = rolesutilisateurprojet.IdR WHERE rolesutilisateurprojet.IdU = :id_user AND rolesutilisateurprojet.IdP = :id_projet";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('id_user',$id_user);
        $stmt->bindParam('id_projet',$id_projet);
        $stmt->execute();

        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($role){
            return $role;
        } else{
            return null;
        }
    } catch (PDOException $e){
        return null;

    }
}

function getStateFromTask($id_task){
    include 'connexionBDD.php';
    try{
        $sql = "SELECT etatstaches.Etat FROM etatstaches JOIN sprintbacklog ON sprintbacklog.IdEtat = etatstaches.IdEtat WHERE sprintbacklog.IdT = :id_task";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('id_task',$id_task);
        $stmt->execute();

        $state = $stmt->fetch(PDO::FETCH_COLUMN);
        if ($state){
            return $state;
        } else{
            return null;
        }
    } catch (PDOException $e){
        return null;
    }
}


function getPriorityFromTask($id_task){
    include 'connexionBDD.php';
    try{
        $sql = "SELECT prioritestaches.valPriorite FROM prioritestaches JOIN taches ON prioritestaches.idPriorite = taches.IdPriorite WHERE taches.IdT = :id_task";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('id_task',$id_task);
        $stmt->execute();

        $priority = $stmt->fetch(PDO::FETCH_COLUMN);
        if ($priority){
            return $priority;
        } else{
            return null;
        }
    } catch (PDOException $e){
        return null;
    }
}

function getUserFromTask($id_task){
    include 'connexionBDD.php';
    try{
        $sql = "SELECT * FROM utilisateurs JOIN sprintbacklog ON utilisateurs.IdU = sprintbacklog.IdU WHERE sprintbacklog.IdT = :id_task";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('id_task',$id_task);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user){
            return $user;
        } else{
            return null;
        }
    } catch (PDOException $e){
        return null;
    }
}