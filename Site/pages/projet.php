<?php
    session_start();

    include "../includes/connexionBDD.php";
    include "../includes/function.php";
    if (isset($_GET['id'])) {
        $id_projet = (int)$_GET['id']; 
    } else {
        echo "Aucun projet sélectionné.";
    }

    //projet 

    $sql = "SELECT * FROM projets WHERE IdP = :id_projet";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':id_projet', $id_projet);
    $stmt->execute();

    $projet = $stmt->fetch(PDO::FETCH_ASSOC);
    //Role de l'utilisateur consultat la page
    $role = getRoleFromUserInProject($_SESSION['user_id'],$projet['IdP']);
    $is_scrum_master = $role['DescR'] == "Scrum Master" || $role['DescR'] == "Product Owner";
    //membres du projet
    $membres = getMembresFromProjet($id_projet);
    //taches du projet
    $taches = getTachesFromProjet($id_projet);
    //sprints du projet 
    $sprints = getSprintsFromProject($id_projet);
    //equipes du projet
    $equipes = getTeamsOfProject($id_projet);
    //sprint actif du projet
    $sprints_actifs = [];  //Tableau de tableau de tableau
    foreach ($equipes as $equipe){
        array_push($sprints_actifs,getActivesSprintOfTeam($equipe['IdEq']));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include "../includes/aside.php"; ?>

    <?php echo "<h1>" . $projet['NomP'] . "</h1>" ?>

    <h2>Membres</h2>
    <table border="1" cellpadding="5">
        <!-- Entete du tableau membres -->
        <tr>
            <th>Utilisateur</th>
            <th>Spécialité</th>
            <th>Rôle</th>
            <th>Tâches</th>
            <th>Modifier utilisateur</th>
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
                            if (isset($tasks) && !empty($tasks)){
                                foreach ($tasks as $task){
                                    echo "<p>" . $task['TitreT'] . "</p>";
                                }
                            } else {
                                echo "Aucune tâche assignée ";
                                if ($is_scrum_master): 
                        ?>
                                    <?php $tasks = getNoAssignedTaskInProject($projet['IdP']); ?>
                                    <?php if ($tasks): ?>
                                        <form action="../process/assign_task_process.php" method="POST">
                                            <p></p><label>Assigner une tache : </label>
                                            <select name="id_task" id="id_task" required>
                                                <option value="">-- Sélectionner une tâche --</option>
                                                <?php foreach($tasks as $task):?>
                                                    <option value="<?php echo $task['IdT']; ?>"><?php echo $task['TitreT']; ?></option>
                                                    <?php endforeach; ?>
                                            </select>
                                            <input type="hidden" name="id_sprint" value="<?php echo getActivesSprintOfTeam(getEquipesFromUser($membre['IdU']))['IdS']; ?>">
                                            <input type="hidden" name="id_user" value="<?php echo $membre['IdU']; ?>">
                                            <button type="submit">Assigner la tâche à l'utilisateur</button>
                                        </form>
                                    <?php endif; endif;
                                }
                                ?>
                    </td>
                    <td>
                        <?php
                        
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
    
    <!-- Ajouter un membre au projet -->
    <?php if ($is_scrum_master):
        $sql = "SELECT IdU, NomU, PrenomU FROM utilisateurs";
        $stmt = $bdd->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ids_membres = array_map(function($user) { return $user['IdU']; }, $membres);
    ?>
        <h3>
            Ajouter un membre au projet
        </h3>
        <form action="../process/add_user_process.php" method="POST">
            <label for="utilisateur">Choisir un utilisateur :</label>
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
                <option value="2">Product owner</option>
                <option value="3">Membre</option>
            </select>
            <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">

            <button type="submit">Ajouter l'utilisateur au projet</button>
        </form>
    <?php endif ?>                

    <h2>Tâches</h2>
    <table border="1" cellpadding="5">
        <!-- Entete du tableau taches -->
        <tr>
            <th>Tâches</th>
            <th>Priorité</th>
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
                                <form action="../process/update_task_process.php" method="POST">
                                    <label for="task_state">Mettre a jour le statut :</label>
                                    <select name="task_state" id="task_state" required>
                                        <option value="">-- Sélectionner un statut --</option>
                                        <?php foreach($all_states as $state): ?>
                                            <option value="<?php echo $state['IdEtat'] ?>"> <?php echo $state['Etat'] ?> </option>
                                        <?php endforeach; ?>
                                    </select>

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
    
    <!-- Ajouter une tâche au projet -->

    <?php if ($is_scrum_master): ?>
    <h3>Ajouter une tâche</h3>
    <form action="../process/add_task_process.php" method="POST">
        <label for="task_title">Titre de la tâche :</label>
        <input type="text" id="task_title" name="task_title" required>

        <label for="task_description">Description de la tâche :</label>
        <textarea id="task_description" name="task_description" required></textarea>

        <label for="task_cost">Coût de la tâche :</label>
        <input type="number" min="0" max="999" id="task_cost" name="task_cost" required></textarea>

        <label for="task_priority">Priorité :</label>
        <select name="task_priority" id="task_priority" required>
            <option value="">-- Sélectionner une priorité --</option>
            <?php
            $sql = "SELECT IdPriorite, Priorite FROM prioritestaches"; 
            $stmt = $bdd->query($sql);
            $priorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($priorites as $priorite): ?>
                <option value="<?php echo $priorite['IdPriorite']; ?>">
                    <?php echo $priorite['Priorite']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">

        <button type="submit">Ajouter la tâche</button>
    </form>
<?php endif; ?>
    

    <h2>Sprints</h2>

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
    <?php if (isset($sprints) && $sprints): ?>
    <?php foreach ($sprints as $sprint): ?>
        <?php
            $isActive = false;
            $isNotFinished = false;
            if (isset($sprints_actifs) && $sprints_actifs) {
                foreach ($sprints_actifs as $sprint_array) {
                    if (is_array($sprint_array) && isset($sprint_array[0])) {
                        $sprint_actif = $sprint_array[0]; 
                        
                        if (isset($sprint_actif['IdS']) && $sprint_actif['IdS'] == $sprint['IdS']) {
                            $isActive = true;
                            break; 
                        }
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
                    } else {
                        echo $sprint['RetrospectiveS'] ? $sprint['RetrospectiveS'] : "Pas de rétrospective"; 
                    }
                ?>
            </td>
            <td>
                <?php 
                    if (!$sprint['RevueDeSprint']) {
                        echo "Veuillez entrer une revue de sprint.";
                    } else {
                        echo $sprint['RevueDeSprint'] ? $sprint['RevueDeSprint'] : "Pas de revue"; 
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

    <!-- Ajouter un script -->
    <?php if ($is_scrum_master):?> 
        <h3>
            Ajouter un script
        </h3>

        <form id="sprintForm" action="../process/add_sprint_process.php" method="POST">
            
            <label for="startDate">Date de début:</label>
            <input type="date" id="startDate" name="startDate" required>
    
            <label for="endDate">Date de fin:</label>
            <input type="date" id="endDate" name="endDate" required>


            <select name="id_equipe" required>
                <option value="">-- Choisir une équipe -- </option>
                <?php foreach ($equipes as $equipe):?>
                    <option value="<?php echo $equipe['IdEq']?>">
                        <?php echo $equipe['NomEqPrj'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <input type="hidden" id="id_projet" name="id_projet" value="<?php echo $id_projet ?>">
            <button type="submit">Créer le Sprint</button>
        </form>

        <?php
            if (isset($_SESSION['error'])) {
                if ($_SESSION['error'] == 'dateSprint') {
                    unset($_SESSION['error']);
                    echo "<p style='color: red;'>Erreur : Deux sprints de la même équipes ne peuvent pas être actifs en même temps.</p>";
                }
            }
        ?>

        <script>
            document.getElementById('sprintForm').addEventListener('submit', function(event) {
                var startDate = document.getElementById('startDate').value;
                var endDate = document.getElementById('endDate').value;

                if (new Date(endDate) <= new Date(startDate)) {
                    event.preventDefault();
                    alert("La date de fin doit être supérieure à la date de début.");
                }
            });
        </script>
        
    <?php endif; ?>
</body>
</html>