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
        <link href="../styles/style.css" rel="stylesheet">
    </head>
    <body>
        <main class="create_task">
            <h1>Ajout d'un tâche à un projet</h1>
            <form method="POST" action="../process/create_task_process.php">
                <div class="form-group">
                    <input type="text" id="task_title" name="task_title" placeholder="Titre de la tâche">
                </div>

                <div class="form-group">
                    <textarea id="task_userstory" name="task_userstory" rows="5" cols="33" placeholder="User story"></textarea>
                </div>

                <div class="form-group">
                    <select id="task_project" name="task_project">
                        <?php
                        foreach ($projects as $project) {
                            echo "<option value='" . $project['IdP'] . "'>" . $project['NomP'] . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
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
                </div>

                <div class="form-group">
                    <button type="submit">Créer la tâche</button>
                </div>

                <div class="form-group">
                    <a href="../index.php">Revenir à la page d'accueil</a>
                </div>

            </form>
        </main>
    </body>
</html>
