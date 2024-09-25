<?php
session_start();

/*
if (($_SESSION['is_logged_in'] && $_SESSION['statut'] !== "Admin") || !$_SESSION['is_logged_in']) {
    $_SESSION['error'] = 'not_admin';
    header("Location: ../index.php");
    exit();
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <form action="../process/signin_process.php" method="POST">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="firstname" name="firstname" required>
        </div>

        <div class="form-group">
            <label for="mail">E-Mail:</label>
            <input type="email" id="mail" name="mail" required>
            <?php
                if (isset($_SESSION['error'])) {
                    if ($_SESSION['error'] == 'email_use') {
                    unset($_SESSION['error']);
                    echo "<p style='color: red;'>E-mail déjà utilisé <a href='login.php'> Se connexter ?</p>";
                }
            }
            ?>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            <?php
                if (isset($_SESSION['error'])) {
                    if ($_SESSION['error'] == 'mdp_not_same') {
                    unset($_SESSION['error']);
                    echo "<p style='color: red;'>Les mots de passe ne correspondent pas.</p>";
                }
            }
            ?>
        </div>

        <div class="form-group">
            <label for="specialite">Choisir une spécialité :</label>
            <select id="specialite" name="specialite" required>
                <option value="Développeur">Développeur</option>
                <option value="Modeleur">Modeleur</option>
                <option value="Animateur">Animateur</option>
                <option value="UI">UI</option>
                <option value="IA">IA</option>
                <option value="WebComm">WebComm</option>
                <option value="Polyvalent">Polyvalent</option>
            </select>
        </div>

        <div class="form-group">
            <label for="statut">Choisir un statut :</label>
            <select id="statut" name="statut" required>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
        </div>

            
        <div class="form-group">
            <button type="submit">S'inscrire</button>
        </div>
    </form>
    <?php       
        if (isset($_SESSION['error'])) {
            if ($_SESSION['error'] == 'failed_to_create') {
                echo "<p style='color: red;'>Echec de la création de l'utilisateur</p>";
            }
        }
    ?>
</body>
</html>