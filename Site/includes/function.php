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

function getEquipeFromUser($id_user){
    include 'connexionBDD.php';
    try {
        $sql = "SELECT equipesprj.* FROM equipesprj JOIN membre_equipe ON equipesprj.IdEq = membre_equipe.IdEq WHERE membre_equipe.IdU = :id_user"; 
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
        $sql = "SELECT prioritestaches.Priorite FROM prioritestaches JOIN taches ON prioritestaches.idPriorite = taches.IdPriorite WHERE taches.IdT = :id_task";
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

function getSprintsFromProject($id_projet) {
    include 'connexionBDD.php';

    try{
    $sql = "SELECT * FROM sprints WHERE IdEq = (SELECT IdEq FROM projets WHERE IdP = :id_projet)";
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
function getActiveSprintOfTeam($id_team) {
    include "../includes/connexionBDD.php"; 
    try{
    $sql = "SELECT sprints.* FROM sprints WHERE :current_date BETWEEN sprints.DateDebS AND sprints.DateFinS AND sprints.IdEq = :id_team";
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

function getNoAssignedTaskInProject($id_projet){
    include 'connexionBDD.php';
    try{
        $sql = "SELECT taches.* FROM taches LEFT JOIN sprintbacklog ON taches.IdT = sprintbacklog.IdT WHERE sprintbacklog.IdT IS NULL AND taches.IdP = :id_projet";
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

function getTeamByID($id_team){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM equipesprj WHERE IdEq = :id_team";
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

function getTeamsOfProject($id_projet){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM equipesprj WHERE equipesprj.IdP = :id_projet";
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

function getSprintFromTask($id_task){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM sprints JOIN sprintbacklog ON sprints.IdS = sprintbacklog.IdS WHERE sprintbacklog.IdT = :id_tache";
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

function getEveryState(){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM etatstaches";
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

function isTaskCompleted($id_task){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM sprintbacklog WHERE sprintbacklog.IdT = :id_task";
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

function calculVelocite($id_sprint){
    include 'connexionBDD.php';

    try{
        $sql = "SELECT * FROM taches JOIN sprintbacklog ON taches.IdT = sprintbacklog.IdT WHERE sprintbacklog.IdS = :id_sprint AND sprintbacklog.IdEtat = 5";
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

    $sql = "UPDATE sprints SET VelociteEqPrj = :velocite WHERE IdS = :id_sprint";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id_sprint',$id_sprint);
    $stmt->bindParam(':velocite',$velocite);
    $stmt->execute();

    return;

}