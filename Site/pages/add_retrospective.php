<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

$tab_project = getProjectWhereScrumMaster($_SESSION['user_id']);

if (empty($tab_project)) {
    echo "<p style='color: red;'>Aucun projet trouvé</p>";
    exit;
}

$id_project = isset($_POST['project']) ? $_POST['project'] : null;
$id_sprint = isset($_POST['sprint']) ? $_POST['sprint'] : null;
$action = null;
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create a task</title>
        <link href="../styles/style.css" rel="stylesheet">
        <style>
            #selectSprint-form {
                display: <?php echo $id_project ? 'block' : 'none'; ?>;
                margin-top: 20px;
            }
            #addRetro-form {
                display: <?php echo $id_sprint ? 'block' : 'none'; ?>;
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <form id="selectProjectForm" method="POST">
            <label for="project">Sélectionner un projet</label>
            <select id="project" name="project" onchange="this.form.submit()">
                <option value="">-- Sélectionner un projet --</option>
                <?php
                foreach ($tab_project as $project) {
                    $selected = ($project['IdP'] == $id_project) ? "selected" : "";
                    echo "<option value='" . $project['IdP'] . "' $selected>" . $project['NomP'] . "</option>";
                }
                ?>
            </select>
        </form>

        <form id="selectSprintForm" method="POST">
            <div id="selectSprint-form">
                <input type="hidden" name="project" value="<?php echo $id_project; ?>">
                <label for="sprint">Sélectionner un sprint avec sa date de début</label>
                <select id="sprint" name="sprint" onchange="this.form.submit()">
                    <option value="">-- Sélectionner un sprint --</option>
                    <?php
                    if (isset($bdd)) {
                        $sql = "SELECT s.IdS, s.DateDebS FROM ftat.sprints AS s WHERE s.IdP = :idProject";
                        $stmt = $bdd->prepare($sql);
                        $stmt->bindParam(':idProject', $id_project);
                        $stmt->execute();
                        $tab_sprints = $stmt->fetchAll();

                        foreach ($tab_sprints as $sprint) {
                            $selected = ($sprint['IdS'] == $id_sprint) ? "selected" : "";
                            echo "<option value='" . $sprint['IdS'] . "' $selected>" . $sprint['DateDebS'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </form>

        <div id="addRetro-form">
            <form method="POST" action="../process/add_retrospective_process.php">
                <input type="hidden" name="project" value="<?php echo $id_project; ?>">
                <input type="hidden" name="id_sprint" value="<?php echo $id_sprint; ?>">

                <label for="s_retro">Ajouter ou modifier votre rétrospective</label>
                <textarea id="s_retro" name="s_retro"><?php
                    $retro = getRetroFromSprint($id_sprint);
                    echo $retro ? $retro : "";
                    ?></textarea>

                <button type="submit">Valider la rétrospective</button>
            </form>
        </div>
    </body>
</html>
