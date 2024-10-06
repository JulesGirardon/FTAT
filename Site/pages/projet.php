<?php
include "./includes/connexionBDD.php";
if (isset($_GET['id'])) {
    $id_projet = (int)$_GET['id'];
} else {
    echo "Aucun projet sélectionné.";
}

$_SESSION["currPrj"] = isset($id_projet) ? $id_projet : null;


if (isset($bdd)) {
    $sql = "SELECT * FROM ftat.projets WHERE IdP = :id_projet";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id_projet', $id_projet);
    $stmt->execute();
    $projet = $stmt->fetch(PDO::FETCH_ASSOC);
    //Role de l'utilisateur consultat la page
    $role = getRoleFromUserInProject($_SESSION['user_id'], $projet['IdP']);
    if (!$role && $_SESSION['statut'] != 'Admin') {
        header("Location: ./index.php");

    } else if ($role) {
        $is_scrum_master = $role['DescR'] == "Scrum Master";
        $is_product_owner = $role['DescR'] == "Product Owner";
    }

//membres du projet
    $membres = getMembresFromProjet($id_projet);
//taches du projet
    $taches = getTachesFromProjet($id_projet);
//sprints du projet
    $sprints = getSprintsFromProject($id_projet);
//equipes du projet
    $equipes = getTeamsOfProject($id_projet);
//sprint actif du projet
    $sprints_actifs = [];  //Tableau de tableau
    foreach ($equipes as $equipe){
        $sprints_actifs[] = getActiveSprintOfTeam($equipe['IdEq']);
    }

    $bacs = getBacOfProject($id_projet);;

}
?>

<div class="projet">
    <?php echo "<h1>" . $projet['NomP'] . "</h1>" ?>
    <div class="projet-info">
        <div class="projet-membres">
            <div class="projet-element">
                <h2>Membres</h2>
            </div>

            <div class="projet-list-membres"">
                <table border="1" cellpadding="5">
                    <!-- Entete du tableau membres -->
                    <tr>
                        <th>Utilisateur</th>
                        <th>Spécialité</th>
                        <th>Rôle</th>
                        <th>Tâches</th>
                    </tr>
                    <!-- Contenu du tableau membres -->
                    <?php if (isset($membres) && $membres): ?>
                        <?php foreach ($membres as $membre):?>
                            <tr>
                                <td>
                                    <?php
                                    echo $membre['PrenomU'] . ' ' . $membre['NomU'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo $membre['SpecialiteU'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    echo getRoleFromUserInProject($membre['IdU'], $projet['IdP'])['DescR'];
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $tasks = getTaskFromUserInProject($membre['IdU'], $projet['IdP']);
                                    if (!empty($tasks)){
                                        foreach ($tasks as $task){
                                            echo "<p>" . $task['TitreT'] . "</p>";
                                        }
                                    } else {
                                        echo "Aucune tâche assignée ";
                                        if (isset($is_scrum_master) && $is_scrum_master):
                                        ?>
                                        <?php $tasks = getNoAssignedTaskInProject($projet['IdP']); ?>
                                        <?php if ($tasks): ?>
                                        <form action="./process/assign_task_process.php" method="POST">
                                            <input type="hidden" name="id_sprint" value="<?php echo getActiveSprintOfTeam(getEquipeFromUser($membre['IdU']))['IdS']; ?>">
                                            <input type="hidden" name="id_user" value="<?php echo $membre['IdU']; ?>">
                                            <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">

                                            <div class="form-group">
                                                <select name="id_task" id="id_task" required>
                                                    <option value="">-- Sélectionner une tâche --</option>
                                                    <?php foreach($tasks as $task):?>
                                                        <option value="<?php echo $task['IdT']; ?>"><?php echo $task['TitreT']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <button type="submit">Assigner la tâche à l'utilisateur</button>
                                            </div>
                                        </form>
                                    <?php endif; endif;
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <?php echo isset($message) ? $message : "Aucun projet disponible."; ?>
                        </li>
                    <?php endif; ?>
                </table>
            </div>

        <?php if (isset($is_scrum_master) && $is_scrum_master):
            $sql = "SELECT IdU, NomU, PrenomU FROM ftat.utilisateurs";
            $stmt = $bdd->query($sql);
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $ids_membres = array_map(function($user) { return $user['IdU']; }, $membres);
            ?>
            <div class="form-add-membre-projet">
                <!-- Ajouter un membre au projet -->
                <form action="./process/add_user_process.php" method="POST">
                    <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">

                    <select name="user_id" id="utilisateur" required>
                        <option value="">-- Sélectionner un utilisateur --</option>
                        <?php foreach ($users as $user): ?>
                            <?php if (!in_array($user['IdU'], $ids_membres)):?>
                                <option value="<?php echo $user['IdU']; ?>">
                                    <?php echo $user['PrenomU'] . ' ' . $user['NomU']; ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>

                    <select name="id_role" id="role" required>
                        <option value="">-- Sélectionner un role --</option>
                        <?php foreach (getAllRoles() as $role): ?>
                            <option value="<?php echo $role['IdR'] ?>"><?php echo $role['DescR'] ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Ajouter l'utilisateur au projet</button>
                </form>
            </div>
        <?php endif ?>
    </div>

    <div class="projet-task">
        <div class="projet-element">
            <h2>Product Backlog</h2>
        </div>

        <div class="projet-list-taches">
            <table border="1" cellpadding="5">
                <!-- Entete du tableau taches -->
                <tr>
                    <th>Tâches</th>
                    <th>Priorité</th>
                    <th>Coût</th>
                    <th>Utilisateur assigné</th>
                    <th>Statut</th>
                    <th>Sprint</th>
                </tr>

                <!-- Contenu du tableau taches -->
                <?php if (isset($taches) && $taches): ?>
                    <?php foreach ($taches as $tache):?>
                        <tr>
                            <td>
                                <?php
                                echo $tache['TitreT'];
                                ?>
                            </td>
                            <td>
                                <?php
                                echo getPriorityFromTask($tache['IdT']);
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $tache['CoutT'];
                                ?>
                            </td>
                            <td>
                                <?php
                                $user = getUserFromTask($tache['IdT']);
                                if ($user) echo $user['PrenomU'] . ' ' . $user['NomU'];
                                else echo "Pas d'utilisateur assigné"
                                ?>
                            </td>
                            <td>
                                <?php
                                $state = getStateFromTask($tache['IdT']);
                                if ($state) {
                                    echo "<p>" . $state . "</p>";
                                } else {
                                    echo "État non sélectionné";
                                }
                                if (isset($user)):
                                    if ($_SESSION['user_id'] == $user['IdU']):?>
                                        <?php $all_states = getEveryState(); ?>
                                        <form action="./process/update_task_process.php" method="POST">
                                            <div class="form-group">
                                                <label for="task_state">Mettre a jour le statut :</label>
                                                <select name="task_state" id="task_state" required>
                                                    <option value="">-- Sélectionner un statut --</option>
                                                    <?php foreach($all_states as $state): ?>
                                                        <option value="<?php echo $state['IdEtat'] ?>"> <?php echo $state['Etat'] ?> </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">
                                            <input type="hidden" name="id_task" value="<?php echo $tache['IdT'] ?>">

                                            <button type="submit">Mettre a jour la tâche</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                $sprint = getSprintFromTask($tache['IdT']);
                                echo $sprint ? "Sprint #" . $sprint['IdS'] : "Pas de sprint"
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; endif;?>
            </table>
        </div>

        <?php if (isset($is_scrum_master, $is_product_owner) && $is_scrum_master || $is_product_owner): ?>
        <div class="form-add-tache-projet">
            <!-- Ajouter une tâche au projet -->
            <form action="./process/add_task_process.php" method="POST">
                <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">

                <input type="text" id="task_title" name="task_title" placeholder="Titre de la tâche" required>

                <textarea id="task_description" name="task_description" placeholder="Description de la tâche :" required></textarea>

                <select name="task_priority" id="task_priority" required>
                    <option value="">-- Sélectionner une priorité --</option>
                    <?php
                    $sql = "SELECT IdPriorite, Priorite FROM ftat.prioritestaches";
                    $stmt = $bdd->query($sql);
                    $priorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($priorites as $priorite): ?>
                        <option value="<?php echo $priorite['IdPriorite']; ?>">
                            <?php echo $priorite['Priorite']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Ajouter la tâche</button>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <div class="projet-sprints">
        <div class="projet-element">
            <h2>Sprints</h2>
        </div>

        <div class="projet-list-sprints">
            <table border="1" cellpadding="5">
                <!-- Entete du tableau sprints -->
                <tr>
                    <th>Sprint</th>
                    <th>Date de début</th>
                    <th>Date de fin</th>
                    <th>Rétrospective</th>
                    <th>Revue de sprint</th>
                    <th>Equipe</th>
                    <th>Vélocité</th>
                </tr>

                <!-- Contenu du tableau sprints -->
                <?php if (isset($sprints)): ?>
                    <?php foreach ($sprints as $sprint): ?>
                        <?php
                        $isActive = false;
                        $isNotFinished = false;
                        if (isset($sprints_actifs) && $sprints_actifs) {
                            foreach ($sprints_actifs as $sprint_actif) {
                                if (isset($sprint_actif['IdS']) && $sprint_actif['IdS'] == $sprint['IdS']) {
                                    $isActive = true;
                                    break;
                                }
                            }
                        }
                        $date_actuelle = new DateTime();
                        $date_fin_sprint = new DateTime($sprint['DateFinS']);
                        if ((!$sprint['RetrospectiveS'] || !$sprint['RevueDeSprint']) && $date_actuelle > $date_fin_sprint){
                            $isNotFinished = true;

                        }
                        $backgroundColor = '';
                        if ($isActive) {
                            $backgroundColor = 'lightgreen';
                        } elseif ($isNotFinished) {
                            $backgroundColor = 'red';
                        }

                        $IdS = $sprint['IdS'];
                        ?>
                        <tr style="background-color: <?php echo $backgroundColor; ?>;">
                            <td>
                                <?php
                                echo "Sprint #" . $sprint['IdS'];
                                if ($isActive) {
                                    echo " (Actif)";
                                }
                                ?>
                            </td>
                            <td><?php echo $sprint['DateDebS']; ?></td>
                            <td><?php echo $sprint['DateFinS']; ?></td>
                            <td>
                                <?php
                                if (!$sprint['RetrospectiveS']) {
                                    echo "Veuillez entrer une rétrospective.";
                                    if((isset($is_scrum_master) && $is_scrum_master) && ($isNotFinished || $isActive)) {
                                        echo "<form method='POST' action='./pages/add_retrospective.php'><input type='hidden' name='sprint' value='$IdS'><input type='hidden' name='project' value='$id_projet'><button type='submit'>Ajouter</button></form>";
                                    }
                                } else {
                                    echo $sprint['RetrospectiveS'];
                                    if ($isActive && (isset($is_scrum_master) && $is_scrum_master)) {
                                        echo "<form method='POST' action='./pages/add_retrospective.php'><input type='hidden' name='sprint' value='$IdS'><input type='hidden' name='project' value='$id_projet'><button type='submit'>Modifier</button></form>";
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php
                                if (!$sprint['RevueDeSprint']) {
                                    echo "Veuillez entrer une revue de sprint.";
                                    if((isset($is_scrum_master) && $is_scrum_master) && ($isNotFinished || $isActive)) {
                                        echo "<form method='POST' action='./pages/add_revue.php'><input type='hidden' name='sprint' value='$IdS'><input type='hidden' name='project' value='$id_projet'><button type='submit'>Ajouter</button></form>";
                                    }
                                } else {
                                    echo $sprint['RevueDeSprint'];
                                    if ($isActive && (isset($is_scrum_master) && $is_scrum_master)) {
                                        echo "<form method='POST' action='./pages/add_revue.php'><input type='hidden' name='sprint' value='$IdS'><input type='hidden' name='project' value='$id_projet'><button type='submit'>Modifier</button></form>";
                                    }
                                }
                                ?>

                            </td>
                            <td><?php echo getTeamByID($sprint['IdEq'])['NomEqPrj']; ?></td>

                            <?php calculVelocite($sprint['IdS'])?>
                            <td><?php echo $sprint['VelociteEqPrj']; ?></td>


                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Aucun sprint disponible pour ce projet.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <?php if (isset($is_scrum_master) && $is_scrum_master):?>
        <div class="form-add-sprint-projet">
            <!-- Ajouter un script -->
                <form id="sprintForm" action="./process/add_sprint_process.php" method="POST">

                    <input type="hidden" id="id_projet" name="id_projet" value="<?php echo $id_projet ?>">

                    <label for="startDate">Début:</label>
                    <input type="date" id="startDate" name="startDate" required>

                    <label for="endDate">Fin:</label>
                    <input type="date" id="endDate" name="endDate" required>

                    <select name="id_equipe" id="id_equipe" required>
                        <option value="">-- Choisir une équipe -- </option>
                        <?php foreach ($equipes as $equipe):?>
                            <option value="<?php echo $equipe['IdEq']?>">
                                <?php echo $equipe['NomEqPrj'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit">Créer le Sprint</button>
                </form>
        </div>
            <?php
            if (isset($_SESSION['error'])) {
                if ($_SESSION['error'] == 'dateSprint') {
                    unset($_SESSION['error']);
                    echo "<p style='color: red;'>Erreur : Deux sprints de la même équipes ne peuvent pas être actifs en même temps.</p>";
                }
            }
            ?>
        <?php endif; ?>
    </div>

    <div class="projet-bac">
        <div class="projet-element">
            <h2>Bac à sable</h2>
        </div>

        <div class="projet-list-bac">
            <table border="1" cellpadding="5">
                <tr>
                    <th>Idée</th>
                    <th>Description</th>
                    <th>Créateur</th>
                    <th>Equipe</th>
                </tr>

                <?php if (isset($bacs)): ?>
                    <?php foreach ($bacs as $bac): ?>
                        <tr>
                            <td><?php echo "Idée #" . $bac['Id_Idee_bas']; ?></td>
                            <td><?php echo  $bac['desc_Idee_bas']; ?></td>
                            <td><?php
                                $user = getUserFromId($bac['IdU']);
                                echo $user['PrenomU'] . " " . $user['NomU'];
                            ?>
                            </td>
                            <td><?php echo getEquipeFromId($bac['IdEq'])['NomEqPrj'];?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucune idée dans le bac à sable pour ce projet.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <?php if (isset($is_scrum_master) && $is_scrum_master):?>
        <div class="form-add-bac-projet">
            <form id="ideeForm" action="./process/add_idee_process.php" method="POST">
                <input hidden type="text" name="id_projet" value="<?php echo $id_projet ?>">
                <input hidden type="text" name="bac_IdU" value="<?php echo $_SESSION['user_id'] ?>">
                <input hidden type="text" name="bac_IdEq" value="<?php echo getEquipeFromUser($_SESSION['user_id'])['IdEq'] ?>">

                <textarea id="bac_desc" name="bac_desc" placeholder="Description de votre idée" required></textarea>

                <button type="submit">Ajouter l'idée</button>
            </form>
        </div>
        <?php endif; ?>

        <?php if (isset($is_scrum_master)): ?>
            <?php if ($is_scrum_master): ?>
                <button><a href="./pages/planning_poker_scrum.php">Allez au planning poker!</a></button>
            <?php else: ?>
                <button><a href="./pages/planning_poker.php">Allez au planning poker !</a></button>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>

<script>
    var now = new Date();
    var year = now.getFullYear();
    var month = ('0' + (now.getMonth() + 1)).slice(-2);
    var day = ('0' + now.getDate()).slice(-2);
    var datetime_deb = year + '-' + month + '-' + day;

    var dateDebInput = document.getElementById("startDate");
    var dateFinInput = document.getElementById("endDate");

    dateDebInput.value = datetime_deb;
    dateDebInput.min = datetime_deb;

    dateFinInput.value = datetime_deb;
    dateFinInput.min = datetime_deb;

    dateDebInput.addEventListener('input', function() {
        dateFinInput.value = this.value;
        dateFinInput.min = this.value;
    });
</script>