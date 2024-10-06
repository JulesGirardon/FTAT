<?php
include "includes/connexionBDD.php";
include "includes/function.php";
session_start();

if (!isset($_SESSION['is_logged_in'])) {
    $_SESSION['is_logged_in'] = false;
} else {
    if ($_SESSION['is_logged_in'] && isset($bdd)) {
        $sql = "SELECT u.Statut FROM ftat.utilisateurs AS u WHERE u.IdU = :userID";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':userID', $_SESSION['user_id']);
        $stmt->execute();
        $_SESSION['statut'] = $stmt->fetchColumn();
    } else {
        header('Location: ./pages/login.php');
    }
}

if ($_SESSION['statut'] == 'Admin') {
    $projets = getProjectsWithScrumMaster();
    if (!$projets) {
        $message = "Aucun projet trouvé pour cet utilisateur.";
    }
} else if ($_SESSION['statut'] == 'User') {
    $projets = getProjectsWithRolesForUser($_SESSION['user_id']);
    if (!$projets) {
        $message = "Aucun projet trouvé pour cet utilisateur.";
    }
}

// Récupération des paramètres d'URL
$page = isset($_GET['page']) ? $_GET['page'] : null;
$projectId = isset($_GET['id']) ? $_GET['id'] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTAT</title>
    <link rel="stylesheet" href="styles/style.css">
    <script>
        function closeIndividualPage() {
            window.location.href = '?';
        }
    </script>
</head>

<body>
<div class="index">
    <header>
        <h1>FTAT</h1>
        <div>
            <?php
            // Lien pour ajouter un utilisateur ou créer un projet si l'utilisateur est Admin
            echo $_SESSION['statut'] == 'Admin' ? '
                    <a href="pages/signin.php">Inscrire un utilisateur</a>
                    <a href="pages/create_project.php">Créer un projet</a>' : "";

            if (isset($projectId)) {
                // Si un projet est sélectionné, ajouter l'ID dans le lien "Ma page individuelle"
                if (isset($_GET['page'])) {
                    echo '<a href="?id=' . $projectId . '">Retour au projet</a>';
                } else {
                    echo '<a href="?page=individual&id=' . $projectId . '">Ma page individuelle</a>';
                }

            }
            ?>
            <a href="pages/logout.php"> Se déconnecter</a>
        </div>
    </header>

    <aside>
        <ul id="projects-list">
            <hr>
            <h3><a href="?">Liste des projets</a></h3>
            <hr>
            <?php if (isset($projets) && $projets): ?>
                <?php foreach ($projets as $projet): ?>
                    <li class="project-item">
                        <div class="project-header">
                            <a href="?id=<?php echo $projet['IdP']; ?>">
                                <?php
                                if ($_SESSION['statut'] == 'Admin') {
                                    echo $projet['NomP'] . " (" . $projet['PrenomU'] . " " . $projet['NomU'] . ")";
                                } else if ($_SESSION['statut'] == 'User') {
                                    echo $projet['NomP'] . " (" . $projet['DescR'] . ")";
                                }
                                ?>
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <li>
                    <?php echo isset($message) ? $message : "Aucun projet disponible."; ?>
                </li>
            <?php endif; ?>
        </ul>
    </aside>

    <?php
    // Si l'URL contient "page=individual" et un "id", inclure la page individual.php
    if ($page == 'individual' && isset($projectId)) {
        include "pages/individual.php";
    }
    // Sinon inclure projet.php si seulement l'ID est présent
    elseif (isset($projectId)) {
        include "pages/projet.php";
    }
    // Si aucun paramètre, afficher un message
    else {
        echo "<h2>Veuillez sélectionner un projet ou accéder à votre page individuelle.</h2>";
    }
    ?>
</div>
</body>
</html>
