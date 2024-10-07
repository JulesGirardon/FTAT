<?php
if (($_SESSION['is_logged_in'] && $_SESSION['statut'] !== "Admin") || !$_SESSION['is_logged_in']) {
    $_SESSION['error'] = 'not_admin';

    echo "<h1 style='color:red'>Vous devez être Admin pour accéder à cette page</h1>";
}else {
    $equipes = getAllTeams();

if ($equipes):
    ?>

    <div class="projet">
        <h2>Tâches assignées par équipe pour le projet</h2>

        <?php foreach ($equipes as $equipe):
            $taches = getAllTaskOfTeam($equipe['IdEq']);?>

            <h1><?php echo $equipe['NomEqPrj']; ?></h1>

            <?php if ($taches): ?>
            <table border="1" cellpadding="5">
                <tr>
                    <th>Sprint</th>
                    <th>Titre de la tâche</th>
                    <th>Statut</th>
                    <th>Utilisateur assigné</th>
                </tr>

                <?php foreach ($taches as $tache):
                    $sprint = getSprintFromTask($tache['IdT']); ?>

                    <tr>
                        <td>
                            <?php
                            if ($sprint){
                                echo "Sprint #" . $sprint['IdS'];
                            } else{
                                echo "Pas de sprint";
                            }

                            ?>
                        </td>
                        <td><?php echo $tache['TitreT']; ?></td>
                        <td>
                            <?php
                            $state = getStateFromTask($tache['IdT']);
                            if ($state){
                                echo "Sprint #" . $state;
                            } else{
                                echo "Pas d'état";
                            }

                            ?>
                        </td>

                        <td>
                            <?php
                            $user = getUserFromTask($tache['IdT']);
                            if($user){
                                echo $user['PrenomU'] . ' ' . $user['NomU'];
                            }else{
                                echo "Pas d'utilisateur assigné";
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Aucune tâche assignée à cette équipe.</p>
        <?php endif; ?>

        <?php endforeach; ?>
    </div>

<?php else: ?>
    <p>Aucune équipe trouvée dans ce projet.</p>
<?php endif; }?>