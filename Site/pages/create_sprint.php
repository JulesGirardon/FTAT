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
        <link href="../styles/style.css">
    </head>
    <body>
        <form method="POST" action="../process/create_sprint_process.php">
            <label for="date_deb">Date de début du sprint</label>
            <input type="date" id="date_deb" name="date_deb">

            <label for="date_fin">Date de fin du sprint</label>
            <input type="date" id="date_fin" name="date_fin">

            <label for="select_project_to_add_sprint">Projet</label>
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

            <button type="submit">Créer le sprint</button>
        </form>


        <script>
            var now = new Date();
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);
            var day = ('0' + now.getDate()).slice(-2);
            var datetime_deb = year + '-' + month + '-' + day;

            var dateDebInput = document.getElementById("date_deb");
            var dateFinInput = document.getElementById("date_fin");

            dateDebInput.value = datetime_deb;
            dateDebInput.min = datetime_deb;

            dateFinInput.value = datetime_deb;
            dateFinInput.min = datetime_deb;

            dateDebInput.addEventListener('input', function() {
                dateFinInput.value = this.value;
                dateFinInput.min = this.value;
            });
        </script>
    </body>
</html>
