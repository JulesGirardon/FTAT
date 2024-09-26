<?php
    session_start();

    include "../includes/connexionBDD.php";
    include "../includes/function.php";
    if (isset($_GET['id'])) {
        $id_projet = (int)$_GET['id']; 
    } else {
        echo "Aucun projet sélectionné.";
    }

    //projet 

    $sql = "SELECT * FROM projets WHERE IdP = :id_projet";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id_projet', $id_projet);
    $stmt->execute();

    $projet = $stmt->fetch(PDO::FETCH_ASSOC);
    //Role de l'utilisateur consultat la page
    $role = getRoleFromUserInProject($_SESSION['user_id'],$projet['IdP']);
    $is_scrum_master = $role['DescR'] == "Scrum Master";
    //membres du projet
    $membres = getMembresFromProjet($id_projet);
    //taches du projet
    $taches = getTachesFromProjet($id_projet);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "../includes/aside.php"; ?>

    <?php echo "<h1>" . $projet['NomP'] . "</h1>" ?>

    <h2>Membres</h2>
    <table border="1" cellpadding="5">
        <!-- Entete du tableau membres -->
        <tr>
            <th>Utilisateur</th>
            <th>Spécialité</th>
            <th>Rôle</th>
            <th>Tâches</th>
            <th>Modifier utilisateur</th>
        </tr>
        <!-- Contenu du tableau membres -->
        <?php if (isset($membres) && $membres): ?>
            <?php foreach ($membres as $membre):?>
                <tr>
                    <td>
                        <?php 
                         echo $membre['PrenomU'] . ' ' . $membre['NomU'];
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo $membre['SpecialiteU'];
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo getRoleFromUserInProject($membre['IdU'], $projet['IdP'])['DescR'];
                        ?>
                    </td>
                    <td>
                        <?php 
                        $tasks = getTaskFromUserInProject($membre['IdU'], $projet['IdP']);
                        if (isset($tasks)){
                            foreach ($tasks as $task){
                                echo "<p>" . $task['TitreT'] . "</p>";
                            }
                        }else {
                            echo "Aucune tâche assignée ";
                            if ($is_scrum_master) 
                                echo "<a href='assign_task.php'>Assigner une tache</a>";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <li>
                <?php echo isset($message) ? $message : "Aucun projet disponible."; ?>
            </li>
         <?php endif; ?>
    </table>

    <h3>
        <?php if ($is_scrum_master) echo '<a href="add_user.php?id=<?php echo $projet[\'IdP\']; ?>">Ajouter un membre au projet</a>' ?>
    </h3>
    <?php if (isset($_GET['error']) && $_GET['error'] == "error"){
        echo "<p>Une erreur est survenue</p>";
    } 
    ?>


    <h2>Tâches</h2>
    <table border="1" cellpadding="5">
        <!-- Entete du tableau taches -->
        <tr>
            <th>Tâches</th>
            <th>Priorité</th>
            <th>Utilisateur assigné</th>
            <th>Statut</th>
            <th>Modifier Tâche</th>
        </tr>

        <!-- Contenu du tableau taches -->
        <?php if (isset($taches) && $taches): ?>
            <?php foreach ($taches as $tache):?>
                <tr>
                    <td>
                        <?php 
                         echo $tache['TitreT'];
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo getPriorityFromTask($tache['IdT']);
                        ?>
                    </td>
                    <td>
                        <?php
                            $user = getUserFromTask($tache['IdT']);
                            echo $user['PrenomU'] . ' ' . $user['NomU'];
                        ?>
                    </td>
                    <td>
                        <?php 
                        $state = getStateFromTask($tache['IdT']);
                        if ($state) {
                            echo "<p>" . $state . "</p>";
                        } else {
                            echo "État non sélectionné";
                        }  
                        ?>
                    </td>
                    <td>
                        <?php 
                            
                        ?>
                    </td>
                </tr>
            <?php endforeach; endif;?>
    </table>

    <h3>
        <?php if ($is_scrum_master) echo "<a href='#'>Ajouter une Tâche</a>" ?>
    </h3>
</body>
</html>