<?php
include "includes/connexionBDD.php";
session_start();
if (!isset($_SESSION['is_logged_in'])) {
    $_SESSION['is_logged_in'] = false;
}
else{
    if ($_SESSION['is_logged_in']){
        $sql = "SELECT statut FROM utilisateurs WHERE IdU = :userID";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([':userID' => $_SESSION['user_id']]);
        $_SESSION['statut'] = $stmt->fetchColumn();
        echo $_SESSION['statut'];
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTAT</title>
</head>
<body>
<?php
if (isset($_SESSION['error'])){
    if ($_SESSION['error'] == 'not_admin'){
        unset($_SESSION['error']);
        echo "<p style='color: red;'>Vous devez être Administateur pour inscrire un utilisateur</p>";
    }
}
?>
<?php
if (isset($_SESSION['statut']) && $_SESSION['statut'] == "Admin"){
    echo '<p><a href="pages/signin.php"> Inscrire un utilisateur</p>';
    echo '<p><a href="pages/create_project.php"> Créer un projet</p>';

}
?>
<?php
if ($_SESSION['is_logged_in']){
    echo '<p><a href="pages/logout.php"> Se déconnecter</p>';
}
else{
    echo '<p><a href="pages/login.php"> Se connecter</p>';
}

include "includes/aside.php"
?>
</body>
</html>