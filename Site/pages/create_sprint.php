<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

if (empty(getProjectWhereScrumMaster($_SESSION['user_id']))) {
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
        <title>Create a sprint</title>
        <link href="../styles/style.css" rel="stylesheet">
    </head>
    <body>
        <main>
            <h2>Ajout d'un sprint pour un projet</h2>
            <form method="POST" action="../process/create_sprint_process.php">

                <div class="form-group">
                    <label for="date_deb">Début du sprint</label>
                    <input type="date" id="date_deb" name="date_deb">
                </div>

                <div class="form-group">
                    <label for="date_fin">Fin du sprint</label>
                    <input type="date" id="date_fin" name="date_fin">
                </div>

                <div class="form-group">
                    <select id="select_project_to_add_sprint" name="select_project_to_add_sprint">
                        <?php
                        if (isset($bdd)) {
                            $ProjectOfScrumMaster = getProjectWhereScrumMaster($_SESSION['user_id']);

                            foreach ($ProjectOfScrumMaster as $project) {
                                echo '<option value="'.$project['IdP'].'">'.$project['NomP'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">Créer le sprint</button>
                </div>

                <div class="form-group">
                    <a href="../index.php">Revenir à la page d'accueil</a>
                </div>
            </form>
        </main>
    </body>
</html>
