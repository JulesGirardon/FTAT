<?php
    session_start();

    include "../includes/connexionBDD.php";
    include "../includes/function.php";
    if (isset($_GET['id'])) {
        $id_projet = (int)$_GET['id']; 
    } else {
        echo "Aucun projet sélectionné.";
    }

    $membres_projet = getMembresFromProjet($id_projet);
    $ids_membres = array_map(function($user) { return $user['IdU']; }, $membres_projet);

    $sql = "SELECT IdU, NomU, PrenomU FROM utilisateurs";
    $stmt = $bdd->query($sql);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un utilisateur</title>
</head>
<body>
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
</body>
</html>