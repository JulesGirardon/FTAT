<<<<<<< Updated upstream
=======
<?php
session_start(); // Démarrage de la session

// On vérifie si l'utilisateur est connecté, sinon on redirige vers la page de connexion
if (!isset($_SESSION['username'])) {
    header('Location: ./pages/login.php'); // Redirige vers la page de connexion si non connecté
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Style du header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 20px; /* Réduction du padding pour un header moins large */
            background-color: #f8f8f8;
            border-bottom: 1px solid #ddd;
            max-width: 900px; /* Limite la largeur du header */
            margin: 0 auto; /* Centrer le header */
        }

        /* Style du logo */
        .header .logo img {
            width: 80px; /* Réduction de la taille du logo */
        }

        /* Style pour le container de droite */
        .header .user-info {
            display: flex;
            align-items: center;
        }

        /* Style de la cloche de notification */
        .header .notification {
            margin-right: 10px; /* Réduction de l'espacement à droite */
            position: relative;
        }

        .header .notification .bell-icon {
            font-size: 20px; /* Réduction de la taille de la cloche */
            cursor: pointer;
        }

        /* Pour indiquer une notification non lue (optionnel) */
        .header .notification .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
        }

        /* Style du nom d'utilisateur */
        .header .username {
            font-weight: bold;
            font-size: 14px; /* Taille de texte réduite */
        }
    </style>
</head>
<body>

<header class="header">
    <!-- Logo à gauche -->
    <div class="logo">
        <img src="logo.png" alt="Logo"> <!-- Remplacer "logo.png" par le chemin réel de l'image -->
    </div>

    <!-- Cloche de notification et nom d'utilisateur à droite -->
    <div class="user-info">
        <div class="notification">
            <span class="bell-icon">&#128276;</span> <!-- Icône de cloche (Unicode) -->
            <span class="badge">3</span> <!-- Badge de notification (facultatif) -->
        </div>
        <div class="username">
            <?php echo htmlspecialchars($_SESSION['username']); ?> <!-- Affichage sécurisé du nom de l'utilisateur -->
        </div>
    </div>
</header>

</body>
</html>
>>>>>>> Stashed changes
