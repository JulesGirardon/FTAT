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
            $sql = "SELECT me.IdEq FROM ftat.membre_equipe AS me WHERE me.IdU = :id_user";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->execute();
            $id = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($id)) {
                return $id;
            } else {
                return null;
            }
        }
    } catch (PDOException $e) {
        return  null;
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

function getProjetsFromUserAndIDProjets($id_user, $id_projet) {
    include 'connexionBDD.php';

    if (isset($bdd, $id_user, $id_projet)) {
        try {
            $sql = "SELECT * FROM ftat.projets JOIN ftat.rolesutilisateurprojet ON projets.IdP = rolesutilisateurprojet.IdP WHERE rolesutilisateurprojet.IdU = :id_user AND projets.IdP = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->execute();

            $project = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($project) {
                return $project;
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
            $sql = "SELECT * FROM ftat.sprints AS s WHERE s.IdEq IN (SELECT IdEq FROM ftat.equipesprj AS eqp WHERE eqp.IdP = :id_projet)";
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
    include "connexionBDD.php";

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

        return 0;
    }
}

function getBacOfProject($id_projet){
    include 'connexionBDD.php';

    if(isset($bdd, $id_projet)) {
        try {
            $sql = "SELECT * FROM idees_bac_a_sable JOIN equipesprj ON idees_bac_a_sable.IdEq = equipesprj.IdEq WHERE equipesprj.IdP = :id_projet";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->execute();
            $bac = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $bac ? $bac : null;

        } catch (PDOException $e){
            echo $e->getMessage();
            return $e;
        }
    } else{
        return "prout";
    }
}

function getUserFromId($IdU) {
    include 'connexionBDD.php';

    if(isset($bdd, $IdU)) {
        try {
            $sql = "SELECT * FROM ftat.utilisateurs AS u WHERE u.IdU = :IdU";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':IdU', $IdU);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user ? $user : null;

        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}

function getEquipeFromId($IdEq) {
    include 'connexionBDD.php';

    if(isset($bdd, $IdEq)) {
        try {
            $sql = "SELECT * FROM ftat.equipesprj AS epj WHERE epj.IdEq = :IdEq";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':IdEq', $IdEq);
            $stmt->execute();
            $equipe = $stmt->fetch(PDO::FETCH_ASSOC);

            return $equipe ? $equipe : null;

        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}

function getEquipeFromIdSprint($id_sprint) {
    include 'connexionBDD.php';

    if(isset($bdd, $id_sprint)) {
        try {
            $sql = "SELECT * FROM ftat.equipesprj AS epj JOIN ftat.sprints AS s ON s.IdEq = epj.IdEq WHERE s.IdS = :IdS";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':IdS', $id_sprint);
            $stmt->execute();
            $equipe = $stmt->fetch(PDO::FETCH_ASSOC);
            return $equipe ? $equipe : null;

        } catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}

function displayAllTasks($currPrj)
{
    include 'connexionBDD.php';

    if(isset($bdd, $currPrj)) {
        try {
            $stmt = $bdd->prepare("CALL getAllTasks(:project_id)");
            $stmt->bindParam(':project_id', $currPrj);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }
}

function displayAllVotes($currPrj)
{
    include 'connexionBDD.php';

    if(isset($bdd, $currPrj)) {
        try {
            $stmt = $bdd->prepare("CALL getAllVotes(:project_id)");
            $stmt->bindParam(':project_id', $currPrj);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }
}

function insertCout($idU, $idT, $commentaire, $coutMt)
{
    include 'connexionBDD.php';

    if(isset($bdd, $idU, $idT, $commentaire, $coutMt)) {
        try {
            $stmt = $bdd->prepare("SELECT insertCoutMembreTache(:idU, :idT, :commentaire, :coutMt)");
            $stmt->bindParam(':idU', $idU);
            $stmt->bindParam(':idT', $idT);
            $stmt->bindParam(':commentaire', $commentaire);
            $stmt->bindParam(':coutMt', $coutMt);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }
}

function displayAllDifficulties()
{
    include 'connexionBDD.php';

    if(isset($bdd)) {
        try {
            $stmt = $bdd->prepare("CALL displayAllDifficulties()");
            $stmt->execute();
            $difficulties = [];

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $difficulties[] = $row;
                }
            }
            $stmt->closeCursor();
            return $difficulties;
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }
}
function insertCoutScrum($idT, $cout)
{
    include 'connexionBDD.php';

    if(isset($bdd, $idT, $cout)) {
        try {
            $stmt = $bdd->prepare("SELECT insertCoutTache(:idT, :cout, :bool)");
            $stmt->bindParam(':idT', $idT);
            $stmt->bindParam(':cout', $cout);
            $stmt->bindValue(':bool', 1);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }

}

function displayAllComments($task_id)
{
   include 'connexionBDD.php';

   if(isset($bdd, $task_id)) {
       try {
           $stmt = $bdd->prepare("CALL getAllComments(:task_id)");
           $stmt->bindParam(':task_id', $task_id);
           $stmt->execute();
           return $stmt->fetchAll(PDO::FETCH_ASSOC);
       } catch (PDOException $e) {
           echo "Erreur de base de données : " . $e->getMessage();
           exit;
       }
   }
}

function getEquipeFromUserInProject($idUser, $idProjet) {
    include 'connexionBDD.php';

    if (isset($bdd, $idUser, $idProjet)) {
        try {
            $sql = "SELECT * FROM ftat.equipesprj JOIN ftat.membre_equipe ON equipesprj.IdEq = membre_equipe.IdEq WHERE membre_equipe.IdU = :idUser AND equipesprj.IdP = :idProjet";

            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindParam(':idProjet', $idProjet, PDO::PARAM_INT);
            $stmt->execute();
            $equipe = $stmt->fetch();

            if ($equipe) {
                return $equipe;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }
}

function getMembresFromEquipe($idEquipe) {
    include 'connexionBDD.php';

    if(isset($bdd, $idEquipe)) {
        try {
            $sql = "SELECT * FROM ftat.membre_equipe JOIN ftat.utilisateurs ON membre_equipe.IdU = utilisateurs.IdU WHERE membre_equipe.IdEq = :idEquipe";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':idEquipe', $idEquipe);
            $stmt->execute();
            $membres = $stmt->fetchAll();

            if ($membres) {
                return $membres;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }
}

function getPourcentageProjet($id_projet) {
    include 'connexionBDD.php';

    if(isset($bdd, $id_projet)) {
        try {
            $sql = "SELECT * FROM ftat.taches AS t WHERE t.IdP = :IdP";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':IdP', $id_projet);
            $stmt->execute();
            $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $total_tache = count($taches);

            $tache_fini_sql = "SELECT * FROM ftat.sprintbacklog AS sb JOIN ftat.taches AS t ON t.IdT = sb.IdT WHERE sb.IdEtat = 5 AND t.IdP = :IdP";
            $stmt = $bdd->prepare($tache_fini_sql);
            $stmt->bindParam(':IdP', $id_projet);
            $stmt->execute();
            $taches_fini = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $total_tache_fini = count($taches_fini);

            if ($total_tache_fini != 0) {
                return ($total_tache_fini / $total_tache) * 100;
            } else {
                return 0;
            }


        } catch (PDOException $e) {
            return null;
        }
    }
}

function isInATeamInProjet($id_user, $id_projet) {
    include 'connexionBDD.php';
    
    if (isset($bdd, $id_user, $id_projet)) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM membre_equipe 
                    JOIN equipesprj ON membre_equipe.IdEq = equipesprj.IdEq 
                    JOIN projets ON projets.IdP = equipesprj.IdP 
                    WHERE membre_equipe.IdU = :id_user AND equipesprj.IdP = :id_projet";
                    
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->execute();

            $count = $stmt->fetchColumn();

            return $count > 0; 
            
        } catch (PDOException $e) {
            return null;
        }
    }
    return null;
}


function isInTeam($id_user,$id_equipe){
    include 'connexionBDD.php';


    if ($bdd || isset($id_user, $id_equipe)) {
        try{
            $sql = "SELECT COUNT(*) FROM membre_equipe WHERE membre_equipe.IdU = :id_user AND membre_equipe.IdEq = :id_equipe";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_equipe', $id_equipe);
            $stmt->execute();

            $count = $stmt->fetchColumn();
            if ($count > 0){
                return true;
            } else{
                return false;
            }

        
        } catch(PDOException $e){
            return null;
        }
    } else{
        return null;
    }
}