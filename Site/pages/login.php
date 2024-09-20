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
    </div>

    <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div class="form-group">
            <button type="submit">Se connecter</button>
    </div>

</body>
</html>