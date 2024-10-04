<?php

/**
 * Liste des rôles : Scrum Master, Product Owner, Member
 */
function getIdRole($role) {
    include "connexionBDD.php";
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
    include "connexionBDD.php";

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

function getEquipeFromUser($id_user){
    include 'connexionBDD.php';

    if(isset($bdd, $id_user)){
        try {
            $sql = "SELECT equipesprj.* FROM ftat.equipesprj JOIN ftat.membre_equipe ON equipesprj.IdEq = membre_equipe.IdEq WHERE membre_equipe.IdU = :id_user";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $equipe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($equipe) {
                return $equipe;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return  null;
        }
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

function getRevueFromSprint($id_sprint) {
    include 'connexionBDD.php';

    if (isset($bdd, $id_sprint)) {
        $sql = "SELECT s.RevueDeSprint FROM ftat.sprints AS s WHERE s.IdS = :idSprint";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idSprint', $id_sprint);
        $stmt->execute();
        $retro = $stmt->fetch(PDO::FETCH_ASSOC);

        return $retro['RevueDeSprint'] ? $retro['RevueDeSprint'] : null;
    }
}

function getProjects() {
    include 'connexionBDD.php';

    if (isset($bdd)) {
        $sql = "SELECT p.IdP, p.NomP FROM ftat.projets AS p";
        $stmt = $bdd->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProjectsWithRolesForUser($idU) {
    include 'connexionBDD.php';

    if (isset($bdd)) {
        $sql = "SELECT p.IdP, p.NomP, r.DescR FROM ftat.projets AS p JOIN ftat.rolesutilisateurprojet AS rup ON rup.IdP = p.IdP JOIN ftat.roles AS r ON r.IdR = rup.IdR WHERE rup.IdU = :idU";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idU', $idU);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getProjectsWithScrumMaster()
{
    include 'connexionBDD.php';

    $idR_ScrumMaster = getIdRole("Scrum Master");

    if (isset($bdd)) {
        $sql = "SELECT p.IdP, p.NomP, u.IdU, u.PrenomU, u.NomU FROM ftat.projets AS p JOIN ftat.rolesutilisateurprojet AS rup ON rup.IdP = p.IdP JOIN ftat.utilisateurs AS u ON u.IdU = rup.IdU WHERE rup.IdR = :idR";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':idR', $idR_ScrumMaster);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    return null;
}

function getAllRoles() {
    include 'connexionBDD.php';
    try{
        if (isset($bdd)) {
            $sql = "SELECT * FROM ftat.roles";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
    } catch (PDOException $e){
        echo $e->getMessage();
    }
    return null;
}

function getAllUsers() {
    include 'connexionBDD.php';
    try{
        if (isset($bdd)) {
            $sql = "SELECT * FROM ftat.utilisateurs";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        }
    } catch (PDOException $e){
        echo $e->getMessage();
    }
    return null;
}

function getAllUsersNotInProjet($id_project) {
    include 'connexionBDD.php';
    try{
        if (isset($bdd, $id_project)) {
            $sql = "SELECT u.IdU, u.NomU, u.PrenomU FROM ftat.utilisateurs AS u JOIN ftat.rolesutilisateurprojet AS rup ON rup.IdU = u.IdU WHERE rup.IdP NOT IN (SELECT rup2.IdP FROM ftat.rolesutilisateurprojet AS rup2 WHERE rup2.IdU = :id_project)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_project',$id_project);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e){
        echo $e->getMessage();
    }
    return null;
}

function getEquipesFromUser($id_user){
    include 'connexionBDD.php';
    if(isset($bdd)) {
        try {
            $sql = "SELECT membre_equipe.IdEq FROM ftat.membre_equipe WHERE membre_equipe.IdU = :id_user";
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
}

function getProjetsFromUser($id_user) {
    include 'connexionBDD.php';

    if (isset($bdd, $id_user)) {
        try {
            $sql = "SELECT * FROM ftat.projets JOIN ftat.rolesutilisateurprojet ON projets.IdP = rolesutilisateurprojet.IdP WHERE rolesutilisateurprojet.IdU = :id_user";
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
}

function getMembresFromProjet($id_projet){
    include 'connexionBDD.php';

    if (isset($bdd, $id_projet)) {
        try{
            $sql = "SELECT * FROM ftat.utilisateurs JOIN ftat.rolesutilisateurprojet ON utilisateurs.IdU = rolesutilisateurprojet.IdU  WHERE rolesutilisateurprojet.IdP = :id_projet";
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
}

function getTachesFromProjet($id_projet){
    include 'connexionBDD.php';

    if(isset($bdd, $id_projet)) {
        try{
            $sql = "SELECT * FROM ftat.taches JOIN ftat.prioritestaches ON taches.IdPriorite = prioritestaches.idPriorite WHERE taches.IdP = :id_projet ORDER BY prioritestaches.valPriorite DESC";
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
}

function getTaskFromUserInProject($id_user, $id_projet){
    include 'connexionBDD.php';

    if(isset($bdd, $id_user, $id_projet)) {
        try{
            $sql = "SELECT * FROM ftat.taches JOIN ftat.sprintbacklog ON taches.IdT = sprintbacklog.IdT WHERE sprintbacklog.IdU = :id_user AND taches.IdP = :id_projet";
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
}

function getRoleFromUserInProject($id_user,$id_projet){
    include 'connexionBDD.php';

    if (isset($bdd, $id_user, $id_projet)) {
        try{
            $sql = "SELECT * FROM ftat.roles JOIN ftat.rolesutilisateurprojet ON roles.IdR = rolesutilisateurprojet.IdR WHERE rolesutilisateurprojet.IdU = :id_user AND rolesutilisateurprojet.IdP = :id_projet";
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
}

function getStateFromTask($id_task){
    include 'connexionBDD.php';

    if(isset($bdd, $id_task)) {
        try{
            $sql = "SELECT ftat.etatstaches.Etat FROM ftat.etatstaches JOIN ftat.sprintbacklog ON sprintbacklog.IdEtat = etatstaches.IdEtat WHERE sprintbacklog.IdT = :id_task";
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
}


function getPriorityFromTask($id_task){
    include 'connexionBDD.php';

    if(isset($bdd, $id_task)) {
        try{
            $sql = "SELECT ftat.prioritestaches.Priorite FROM ftat.prioritestaches JOIN ftat.taches ON prioritestaches.idPriorite = taches.IdPriorite WHERE taches.IdT = :id_task";
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
}

function getUserFromTask($id_task){
    include 'connexionBDD.php';

    if(isset($bdd, $id_task)) {
        try{
            $sql = "SELECT * FROM ftat.utilisateurs JOIN ftat.sprintbacklog ON utilisateurs.IdU = sprintbacklog.IdU WHERE sprintbacklog.IdT = :id_task";
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
}

function getSprintsFromProject($id_projet) {
    include 'connexionBDD.php';

    if(isset($bdd, $id_projet)) {
        try{
            $sql = "SELECT * FROM ftat.sprints WHERE IdEq = (SELECT IdEq FROM ftat.projets WHERE IdP = :id_projet)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
            $stmt->execute();
            $sprint = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($sprint){
                return $sprint;
            } else{
                return null;
            }

        } catch (PDOException $e){
            return null;
        }
    }
}
function getActiveSprintOfTeam($id_team) {
    include "./includes/connexionBDD.php";

    if(isset($bdd, $id_team)) {
        try{
            $sql = "SELECT sprints.* FROM ftat.sprints WHERE :current_date BETWEEN sprints.DateDebS AND sprints.DateFinS AND sprints.IdEq = :id_team";
            $stmt = $bdd->prepare($sql);

            $current_date = date('Y-m-d');
            $stmt->bindParam(':current_date', $current_date);
            $stmt->bindParam(':id_team', $id_team, PDO::PARAM_INT);

            $stmt->execute();

            $sprint = $stmt->fetch(PDO::FETCH_ASSOC);

            return $sprint ? $sprint : null;

        } catch (PDOException $e){
            return null;
        }
    }
}

function getNoAssignedTaskInProject($id_projet){
    include 'connexionBDD.php';

    if (isset($bdd, $id_projet)) {
        try{
            $sql = "SELECT taches.* FROM ftat.taches LEFT JOIN ftat.sprintbacklog ON taches.IdT = ftat.sprintbacklog.IdT WHERE sprintbacklog.IdT IS NULL AND taches.IdP = :id_projet";
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
}

function getTeamByID($id_team){
    include 'connexionBDD.php';

    if(isset($bdd, $id_team)) {
        try{
            $sql = "SELECT * FROM ftat.equipesprj WHERE IdEq = :id_team";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_team',$id_team);
            $stmt->execute();

            $equipe = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($equipe){
                return $equipe;
            } else{
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }
}

function getTeamsOfProject($id_projet){
    include 'connexionBDD.php';

    if (isset($bdd, $id_projet)) {
        try{
            $sql = "SELECT * FROM ftat.equipesprj WHERE equipesprj.IdP = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_projet',$id_projet);
            $stmt->execute();

            $equipe = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($equipe){
                return $equipe;
            } else{
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }
}

function getSprintFromTask($id_task){
    include 'connexionBDD.php';

    if (isset($bdd, $id_task)) {
        try{
            $sql = "SELECT * FROM ftat.sprints JOIN ftat.sprintbacklog ON sprints.IdS = sprintbacklog.IdS WHERE sprintbacklog.IdT = :id_tache";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam('id_tache',$id_task);
            $stmt->execute();

            $sprint = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($sprint){
                return $sprint;
            } else{
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }
}

function getEveryState(){
    include 'connexionBDD.php';

    if(isset($bdd)) {
        try{
            $sql = "SELECT * FROM ftat.etatstaches";
            $stmt = $bdd->prepare($sql);
            $stmt->execute();

            $etats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($etats){
                return $etats;
            } else{
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }
}

function isTaskCompleted($id_task){
    include 'connexionBDD.php';

    if(isset($bdd, $id_task)) {
        try{
            $sql = "SELECT * FROM ftat.sprintbacklog WHERE sprintbacklog.IdT = :id_task";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_tache',$id_task);
            $stmt->execute();

            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($task['IdEtat'] === 5){
                $is_completed = true;
            } else{
                return false;
            }
        } catch (PDOException $e){
            return false;
        }
    }
}

function calculVelocite($id_sprint){
    include 'connexionBDD.php';

    if(isset($bdd, $id_sprint)) {
        try{
            $sql = "SELECT * FROM ftat.taches JOIN ftat.sprintbacklog ON taches.IdT = sprintbacklog.IdT WHERE sprintbacklog.IdS = :id_sprint AND sprintbacklog.IdEtat = 5";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_sprint',$id_sprint);
            $stmt->execute();

            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e){
            return false;
        }
        $velocite = 0;
        foreach ($tasks as $task){
            $velocite += $task['CoutT'];
        }

        $sql = "UPDATE ftat.sprints SET VelociteEqPrj = :velocite WHERE IdS = :id_sprint";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_sprint',$id_sprint);
        $stmt->bindParam(':velocite',$velocite);
        $stmt->execute();

        return;
    }
}