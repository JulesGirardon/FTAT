<?php
include "./includes/connexionBDD.php";

// Utilisateur connecté
$id_user = $_SESSION['user_id'];

// Projets dont l'utilisateur fait partie 
$projets = getProjetsFromUser($id_user);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Utilisateur</title>
</head>
<body>

<h1>Page de <?php echo $_SESSION['user_name']; ?></h1>

<?php if ($projets): ?>
    <!-- Pour chaque projet auquel l'utilisateur participe -->
    <?php foreach ($projets as $projet): ?>
        <div class="projet">
            <h2>Projet : <?php echo $projet['NomP']; ?></h2>

            <!-- Affichage des tâches de l'utilisateur pour ce projet -->
            <h3>Tâches assignées à l'utilisateur</h3>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Titre de la tâche</th>
                    <th>Description</th>
                    <th>Statut</th>
                </tr>
                <?php
                // Récupérer les tâches assignées à l'utilisateur pour ce projet
                $taches = getTaskFromUserInProject($id_user, $projet['IdP']);
                if ($taches):
                    foreach ($taches as $tache): ?>
                        <tr>
                            <td><?php echo $tache['TitreT']; ?></td>
                            <td><?php echo $tache['UserStoryT']; ?></td>
                            <td><?php echo getStateFromTask($tache['IdT']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Aucune tâche assignée dans ce projet.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <!-- Affichage des sprints en cours et à venir pour ce projet -->
            <h3>Sprints en cours et à venir</h3>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Sprint</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Statut</th>
                </tr>
                <?php
                // Récupérer les sprints en cours et à venir pour ce projet
                $sprints = getSprintsFromProject($projet['IdP']);
                $currentDate = new DateTime();
                if ($sprints):
                    foreach ($sprints as $sprint):
                        $startDate = new DateTime($sprint['DateDebS']);
                        $endDate = new DateTime($sprint['DateFinS']);
                        if ($startDate <= $currentDate && $endDate >= $currentDate || $startDate > $currentDate): ?>
                            <tr>
                                <td><?php echo "Sprint #" . $sprint['IdS']; ?></td>
                                <td><?php echo $sprint['DateDebS']; ?></td>
                                <td><?php echo $sprint['DateFinS']; ?></td>
                                <td><?php echo ($startDate <= $currentDate && $endDate >= $currentDate) ? 'En cours' : 'À venir'; ?></td>
                            </tr>
                        <?php endif;
                    endforeach;
                else: ?>
                    <tr>
                        <td colspan="4">Aucun sprint en cours ou à venir.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <!-- Affichage de l'équipe de l'utilisateur pour ce projet -->
            <h3>Équipe de l'utilisateur dans ce projet</h3>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Nom de l'équipe</th>
                    <th>Membres de l'équipe</th>
                </tr>
                <?php
                // Récupérer l'équipe de l'utilisateur dans ce projet
                $equipe = getEquipeFromUserInProject($id_user, $projet['IdP']);
                if ($equipe): ?>
                    <tr>
                        <td><?php echo $equipe['NomEqPrj']; ?></td>
                        <td>
                            <?php
                            $membres = getMembresFromEquipe($equipe['IdEq']);
                            foreach ($membres as $membre) {
                                echo $membre['PrenomU'] . " " . $membre['NomU'] . "<br>";
                            }
                            ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Pas d'équipe assignée.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun projet assigné à cet utilisateur.</p>
<?php endif; ?>

</body>
</html>
