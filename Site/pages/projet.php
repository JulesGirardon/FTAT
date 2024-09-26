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
    $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
    $stmt->execute();

    $projet = $stmt->fetch(PDO::FETCH_ASSOC);

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

        <tr>
            <th>Utilisateur</th>
            <th>Spécialité</th>
            <th>Rôle</th>
            <th>Tâches</th>
            <th>Statut</th>
        </tr>

        <?php if (isset($membres) && $membres): ?>
    <?php foreach ($membres as $membre): ?>
        <tr>
            <td>
                <?php 
                if (isset($membre['PrenomU']) && isset($membre['NomU'])) {
                    echo $membre['PrenomU'] . ' ' . $membre['NomU'];
                } else {
                    echo "Nom et prénom non sélectionnés";
                }
                ?>
            </td>
            <td>
                <?php 
                if (isset($membre['SpecialiteU'])) {
                    echo $membre['SpecialiteU'];
                } else {
                    echo "Spécialité non sélectionnée";
                }
                ?>
            </td>
            <td>
                <?php 
                $role = getRoleFromUserInProject($membre['IdU'], $projet['IdP']);
                if (isset($role['DescR'])) {
                    echo $role['DescR'];
                } else {
                    echo "Rôle non sélectionné";
                }
                ?>
            </td>
            <td>
                <?php 
                $task = getTaskFromUserInProject($membre['IdU'], $projet['IdP']);
                if (isset($task['TitreT'])) {
                    echo $task['TitreT'];
                } else {
                    echo "Aucune tâche assignée";
                }
                ?>
            </td>
            <td>
                <?php 
                if (isset($task['IdT'])) {
                    $state = getStateFromTaskOfUser($task['IdT'], $membre['IdU']);
                    if ($state) {
                        echo $state;
                    } else {
                        echo "État non sélectionné";
                    }
                } else {
                    echo "Aucune tâche assignée";
                }
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
        <a href="add_user.php?id=<?php echo $projet['IdP']; ?>">Ajouter un membre au projet</a>
    </h3>
    <?php if (isset($_GET['error']) && $_GET['error'] == "error"){
        echo "<p>Une erreur est survenue</p>";
    } 
    ?>


    <h2>Tâches</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Tâches</th>
            <th>Priorité</th>
            <th>Membre</th>
            <th>Complétion</th>
            <th>Modifier Tâche</th>
        </tr>
        <tr>
            <td>Tâche 1</td>
            <td>Élevée</td>
            <td>Membre 1</td>
            <td>100%</td>
            <td><a href="#">Modifier</a></td>
        </tr>
        <tr>
            <td>Tâche 2</td>
            <td>Moyenne</td>
            <td>Membre 2</td>
            <td>50%</td>
            <td><a href="#">Modifier</a></td>
        </tr>
    </table>

    <h3><a href="#">Ajouter une Tâche</a></h3>
</body>
</html>