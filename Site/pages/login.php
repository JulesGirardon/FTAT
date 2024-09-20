<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>
    <form action="../process/login_process.php" method="POST">
    <div class="form-group">
        <label for="mail">E-Mail:</label>
        <input type="email" id="mail" name="mail" required>
        <?php
        if (isset($_SESSION['error'])) {
            if ($_SESSION['error'] == 'email_incorrect') {
                unset($_SESSION['error']);
                echo "<p style='color: red;'>Aucun e-mail trouvé</p>";
            }
        }
        ?>
    </div>

    <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
        <?php
        if (isset($_SESSION['error'])) {
            if ($_SESSION['error'] == 'mdp_incorrect') {
                unset($_SESSION['error']);
                echo "<p style='color: red;'>Mot de passe incorrect</p>";
            }
        }
        ?>
    </div>

    <div class="form-group">
            <button type="submit">Se connecter</button>
    </div>

</body>
</html>