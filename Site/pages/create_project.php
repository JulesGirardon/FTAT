<?php
session_start();

if (($_SESSION['is_logged_in'] && $_SESSION['statut'] !== "Admin") || !$_SESSION['is_logged_in']) {
    $_SESSION['error'] = 'not_admin';
    header("Location: ../index.php");
    exit();
}
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Create a project</title>
        <link href="../styles/style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <main class="add_project">
            <h1>Créer un projet</h1>
            <form action="../process/create_project_process.php" method="POST">
                <div class="form-group">
                    <input type="text" id="project_name" name="project_name" placeholder="Nom du projet">
                </div>

                <div class="form-group">
                    <textarea id="project_description" name="project_description" placeholder="Description du projet"></textarea>
                </div>

                <div class="form-group">
                    <input type="date" id="project_date_fin" name="project_date_fin">
                </div>

                <div class="form-group">
                    <label for="scrum_master">ScrumMaster associé</label>
                    <select id="scrum_master" name="scrum_master">
                        <?php
                        include "../includes/connexionBDD.php";

                        if (isset($bdd)) {
                            $sql = "SELECT IdU, NomU, PrenomU FROM ftat.utilisateurs";
                            $stmt = $bdd->prepare($sql);
                            $stmt->execute();

                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='" . $row['IdU'] . "'>" . $row['PrenomU'] . " " . $row['NomU'] . "</option>";
                            }

                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">Créer le projet</button>
                </div>

                <div class="form-group">
                    <a href="../index.php">Revenir à la page d'accueil</a>
                </div>
            </form>

            <script>
                var now = new Date();
                var year = now.getFullYear();
                var month = ('0' + (now.getMonth() + 1)).slice(-2);
                var day = ('0' + now.getDate()).slice(-2);
                var datetime = year + '-' + month + '-' + day;

                document.getElementById("project_date_fin").value = datetime;
                document.getElementById("project_date_fin").min = datetime;
            </script>
        </main>
    </body>
</html>
