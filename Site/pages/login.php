<<<<<<< Updated upstream
=======
<?php session_start() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
        <link rel="stylesheet" href="../styles/style.css">
    </head>
    <body>
        <main>
            <h1>Connexion</h1>
            <form action="../process/login_process.php" method="POST">
                <div class="form-group">
                    <input type="email" id="mail" name="mail" placeholder="E-mail" required>
                </div>

                <div class="form-group">
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
                    <input type="password" id="password" name="password" placeholder="Mot de passe" required>
                </div>

                <div class="form-group">
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
        </main>
    </body>
</html>
>>>>>>> Stashed changes
