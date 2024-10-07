<?php
if (($_SESSION['is_logged_in'] && $_SESSION['statut'] !== "Admin") || !$_SESSION['is_logged_in']) {
    $_SESSION['error'] = 'not_admin';

    echo "<h1 style='color:red'>Vous devez être Admin pour accéder à cette page</h1>";
}else{
    $users = getAllUsers();

    if ($users):
        ?>

        <div class="projet">
            <h2>Tâches assignées aux utilisateurs</h2>

            <?php foreach ($users as $user):
                $taches = getAllTaskOfUser($user['IdU']); ?>

                <h1><?php echo $user['PrenomU'] . " " . $user['NomU']; ?></h1>

                <?php if ($taches): ?>
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
                        $sprint = getSprintFromTask($tache['IdT']); ?>

                        <tr>
                            <td><?php echo $projet['NomP']; ?></td>
                            <td><?php echo $equipe['NomEqPrj']; ?></td>
                            <td><?php echo "Sprint #" . $sprint['IdS']; ?></td>
                            <td><?php echo $tache['TitreT']; ?></td>
                            <td><?php echo getStateFromTask($tache['IdT']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>Aucune tâche assignée à cet utilisateur.</p>
            <?php endif; ?>

            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p>Aucun utilisateur trouvé dans la base de données.</p>
    <?php endif; ?>
<?php } ?>