<?php
$id_user = $_SESSION['user_id'];
$taches = getAllTaskOfUser($id_user);
$user = getUserById($id_user);
if ($taches):
    ?>

    <div class="projet">
        <h2>Tâches assignées à <?php echo $user['PrenomU']; ?></h2>

        <table border="1" cellpadding="5">
            <tr>
                <th>Projet</th>
                <th>Équipe</th>
                <th>Sprint</th>
                <th>Titre de la tâche</th>
                <th>Statut</th>
            </tr>

            <?php foreach ($taches as $tache):
                $projet = getProjectFromTask($tache['IdT']);
                $equipe = getTeamFromTask($tache['IdT']);
                $sprint = getSprintFromTask($tache['IdT']);
                ?>
                <tr>
                    <td><?php echo $projet['NomP']; ?></td>
                    <td><?php echo $equipe['NomEqPrj']; ?></td>
                    <td><?php echo "Sprint #" . $sprint['IdS']; ?></td>
                    <td><?php echo $tache['TitreT']; ?></td>
                    <td><?php echo getStateFromTask($tache['IdT']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

<?php else: ?>
    <p>Aucune tâche assignée à cet utilisateur.</p>
<?php endif; ?>