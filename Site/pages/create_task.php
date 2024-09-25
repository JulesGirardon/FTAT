<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

$projects = getProjectWhereScrumMaster($_SESSION['user_id']);

if (empty($projects)) {
    echo "<p style='color: red;'>Aucun projet trouvé</p>";
    exit;
}

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Create a task</title>
        <link href="../styles/style.css">
    </head>
    <body>
    <form method="POST" action="../process/create_task_process.php">
        <label for="task_title">Titre de la tâche</label>
        <input type="text" id="task_title" name="task_title">

        <label for="task_userstory">Titre de la tâche</label>
        <textarea id="task_userstory" name="task_userstory" rows="5" cols="33"></textarea>

        <label for="task_project">Sélectionner le projet</label>
        <select id="task_project" name="task_project">
            <?php
                foreach ($projects as $project) {
                    echo "<option value='" . $project['IdP'] . "'>" . $project['NomP'] . "</option>";
                }
            ?>
        </select>

        <label for="task_idpriority">Sélectionner le priorité</label>
        <select id="task_idpriority" name="task_idpriority">
            <?php
                if (isset($bdd)) {
                    $sql = "SELECT pt.idPriorite, pt.Priorite  FROM ftat.prioritestaches AS pt";
                    $stmt = $bdd->prepare($sql);
                    $stmt->execute();
                    $priorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($priorites as $priorite) {
                        echo "<option value='" . $priorite['idPriorite'] . "'>" . $priorite['Priorite'] . "</option>";
                    }
                }


            ?>
        </select>

        <button type="submit">Créer la tâche</button>
    </form>
    </body>
</html>
