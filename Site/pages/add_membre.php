<?php
    session_start();

    include "../includes/connexionBDD.php";
    include "../includes/function.php";
    if (isset($_GET['id'])) {
        $id_projet = (int)$_GET['id']; 
    } else {
        header("Location: ../index.php");
    }

    $membres_projet = getMembresFromProjet($id_projet);
    $allUsers = getAllUsersNotInProjet($id_projet);

    print_r($allUsers);

    if(isset($bdd)) {
        $sql = "SELECT IdU, NomU, PrenomU FROM ftat.utilisateurs";
        $stmt = $bdd->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
</head>
<body>
    <form action="../process/add_membre_process.php" method="POST">
        <label for="utilisateur">Choisir un utilisateur :</label>
        <select name="user_id" id="utilisateur" required>
            <option value="">-- Sélectionner un utilisateur --</option>
            <?php
                foreach ($allUsers as $user) {
                    echo '<option value="'.$user['IdU'].'">'.$user['NomU'].' '.$user['PrenomU'].'</option>';
                }
            ?>
        </select>
        <select name="id_role" id="role" required>
            <option value="">-- Sélectionner un rôle --</option>
            <?php
                foreach (getAllRoles() as $role) {
                    echo '<option value="' . $role['IdR'] . '">' . $role['DescR'] . '</option>';
                }
            ?>
        </select>
        <input type="hidden" name="id_projet" value="<?php echo $id_projet; ?>">

        <button type="submit">Ajouter l'utilisateur au projet</button>
</form>
</body>
</html>