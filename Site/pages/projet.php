<?php
include_once "../include/connexionBDD.php";

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$idEq = $_POST['equipe']

try {

    // Récupérer le projet
    $base = $bdd->prepare("SELECT p.NomEqPrj FROM equipesprj p
                           JOIN rolesutilisateurprojet rup ON p.IdEq = rup.IdEq
                           WHERE rup.IdU = :userId AND rup.IdEq = :idEq");
    
    $base->bindParam(":userId", $userId);
    $base->bindParam(":idEq", $idEq);
    $base->execute();


//TAFFFER JUSQU'A ICI ....




    // Récupérer les tâches de l'utilisateur
    $stmt = $bdd->prepare("SELECT t.IdT, t.TitreT, t.UserStoryT, t.CoutT, pt.Priorite FROM taches t
                           JOIN sprintbacklog sb ON t.IdT = sb.IdT
                           JOIN prioritestaches pt ON t.IdPriorite = pt.idPriorite
                           WHERE sb.IdU = :userId");
    $stmt->execute(['userId' => $userId]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Projet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1></h1>

    <h2>Projets</h2>
    <ul>
        <?php foreach ($projects as $project): ?>
            <li><?php echo htmlspecialchars($project['NomEqPrj']); ?></li>
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