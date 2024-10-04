<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

// Récupérer tous les projets du Scrum Master connecté
$tab_project = getProjectWhereScrumMaster($_SESSION['user_id']);

if (empty($tab_project)) {
    echo "<p style='color: red;'>Aucun projet trouvé</p>";
    exit;
}

// Récupérer l'ID du projet sélectionné si le formulaire a été soumis
$id_project = isset($_POST['sb_projet']) ? $_POST['sb_projet'] : null;

?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create a task</title>
        <link href="../styles/style.css" rel="stylesheet">
        <style>
            #sprintbacklog-form {
                display: <?php echo $id_project ? 'block' : 'none'; ?>; /* Affiche le formulaire si un projet est sélectionné */
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <main>
            <h1>Créer un sprintbacklog</h1>
            <!-- Formulaire pour sélectionner un projet -->
            <form id="selectProjectForm" method="POST">
                <div class="form-group">
                    <label for="sb_projet">Sélectionner un projet</label>
                    <select id="sb_projet" name="sb_projet" onchange="this.form.submit()">
                        <option value="">-- Sélectionner un projet --</option>
                        <?php
                        foreach ($tab_project as $project) {
                            $selected = ($project['IdP'] == $id_project) ? "selected" : "";
                            echo "<option value='" . $project['IdP'] . "' $selected>" . $project['NomP'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </form>

            <!-- Formulaire caché, visible seulement si un projet a été sélectionné -->
            <div id="sprintbacklog-form">
                <form id="sprintForm" method="POST" action="../process/create_sprintbacklog_process.php">
                    <div class="form-group">
                        <label for="sb_taches">Sélectionner une tâche</label>
                        <select id="sb_taches" name="sb_taches">
                            <?php
                            // Récupérer les tâches du projet sélectionné
                            if ($id_project && isset($bdd)) {
                                $sql = "SELECT t.IdT, t.TitreT FROM ftat.taches AS t WHERE t.IdP = :id_project";
                                $stmt = $bdd->prepare($sql);
                                $stmt->bindParam(':id_project', $id_project);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['IdT'] . "'>" . $row['TitreT'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sb_sprint">Sélectionner un sprint</label>
                        <select id="sb_sprint" name="sb_sprint">
                            <?php
                            // Récupérer les sprints du projet sélectionné
                            if ($id_project && isset($bdd)) {
                                $sql = "SELECT s.IdS, s.RetrospectiveS FROM ftat.sprints AS s WHERE s.IdP = :id_project";
                                $stmt = $bdd->prepare($sql);
                                $stmt->bindParam(':id_project', $id_project);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['IdS'] . "'>" . $row['RetrospectiveS'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sb_etat">Sélectionner un état</label>
                        <select id="sb_etat" name="sb_etat">
                            <?php
                            // Récupérer tous les états
                            if (isset($bdd)) {
                                $sql = "SELECT et.IdEtat, et.Etat FROM ftat.etatstaches AS et";
                                $stmt = $bdd->prepare($sql);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='" . $row['IdEtat'] . "'>" . $row['Etat'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sb_user">Sélectionner un utilisateur</label>
                        <select id="sb_user" name="sb_user">
                            <?php
                            if (isset($bdd)) {
                                foreach (getUserFromProject($id_project) as $user) {
                                    echo "<option value='" . $user['IdU'] . "'>" . $user['NomU'] . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit">Ajouter le Sprint Backlog</button>
                    </div>
                </form>
            </div>
            <div class="form-group">
                <a href="../index.php">Revenir à la page d'accueil</a>
            </div>
        </main>
    </body>
</html>
