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

        <form action="../process/create_project_process.php" method="POST">
            <label for="project_name">Nom du projet</label>
            <input type="text" id="project_name" name="project_name">

            <label for="scrum_master">ScrumMaster associé</label>
            <select id="scrum_master" name="scrum_master">
                <?php
                    include "../includes/connexionBDD.php";

                    if (isset($bdd)) {
                        $sql = "SELECT IdU, NomU, PrenomU FROM utilisateurs";
                        $stmt = $bdd->prepare($sql);
                        $stmt->execute();

                        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['IdU'] . "'>" . $row['PrenomU'] . " " . $row['NomU'] . "</option>";
                        }

                    }
                ?>
            </select>

            <button type="submit">Créer le projet</button>
        </form>

    </body>
</html>
