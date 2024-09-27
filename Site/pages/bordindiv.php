<?php
include_once "../includes/connexionBDD.php";

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

try {
    if(isset($bdd)){
        // Récupérer les projets de l'utilisateur
        $stmt = $bdd->prepare("SELECT rup.IdP, p.NomP FROM projets AS p
                           JOIN rolesutilisateurprojet AS rup ON rup.IdP = p.IdP
                           WHERE rup.IdU = :userId");
        $stmt->bindParam(':userId',$userId);
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer les tâches de l'utilisateur
        $stmt = $bdd->prepare("SELECT t.IdT, t.TitreT, t.UserStoryT, t.CoutT, pt.Priorite FROM taches t
                           JOIN sprintbacklog sb ON t.IdT = sb.IdT
                           JOIN prioritestaches pt ON t.IdPriorite = pt.idPriorite
                           WHERE sb.IdU = :userId");
        $stmt->bindParam(':userId',$userId);
        $stmt->execute();
        $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Individuel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Tableau de Bord Individuel</h1>

<h2>Projets</h2>
<ul>
    <?php foreach ($projects as $project): ?>
        <li><?php echo htmlspecialchars($project['NomP']); ?></li>
    <?php endforeach; ?>
</ul>

<h2>Tâches</h2>
<ul>
    <?php foreach ($tasks as $task): ?>
        <li>
            <strong><?php echo htmlspecialchars($task['TitreT']); ?></strong><br>
            User Story: <?php echo htmlspecialchars($task['UserStoryT']); ?><br>
            Coût: <?php echo htmlspecialchars($task['CoutT']); ?><br>
            Priorité: <?php echo htmlspecialchars($task['Priorite']); ?>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>