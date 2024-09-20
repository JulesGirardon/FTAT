<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Header</title>

</head>
<body>

<header class="header">
    <!-- Logo à gauche -->
    <div class="logo">
        <img src="logo.png" alt="Logo" width="100">
    </div>

    <div class="user-info">
        <div class="notification">
            <span class="bell-icon">&#128276;</span>
        </div>
        <div class="username">
            <?php echo htmlspecialchars($_SESSION['username']); ?>
        </div>
    </div>
</header>

</body>
</html>
