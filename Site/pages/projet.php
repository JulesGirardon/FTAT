<?php
include "../includes/connexionBDD.php";

session_start();

// Vérifier si l'utilisateur est connecté
// if (!isset($_SESSION['user_id'])) 
// {
//     header("Location: login.php");
//     exit();
// }

$userId = 1;//$_SESSION['user_id'];
$idEq = 1;//$_POST['equipe'];

try {

    // Récupérer le projet
    $base = $bdd->prepare("SELECT equipesprj.NomEqPrj, rolesutilisateurprojet.IdR FROM equipesprj
						   JOIN projets ON projets.IdEq = equipesprj.IdEq
                           JOIN rolesutilisateurprojet ON projets.IdP = rolesutilisateurprojet.IdP
                           WHERE rolesutilisateurprojet.IdU = :userId AND equipesprj.IdEq = :idEq");
    
    $base->bindParam(":userId", $userId);
    $base->bindParam(":idEq", $idEq);
    $base->execute();
    $Team = $base->fetch(PDO::FETCH_ASSOC);


    // Récupérer les tâches de l'utilisateur
    $base = $bdd->prepare("SELECT t.IdT, t.TitreT, t.UserStoryT, t.CoutT, pt.Priorite FROM taches t
                           JOIN sprintbacklog sb ON t.IdT = sb.IdT
                           JOIN prioritestaches pt ON t.IdPriorite = pt.idPriorite
                           WHERE sb.IdU = :userId AND t.IdEq = :idEq");
    
    $base->bindParam(":userId", $userId);
    $base->bindParam(":idEq", $idEq);
    $base->execute();
    $YourTasks = $base->fetchALL(PDO::FETCH_ASSOC);


    //Recupère les tâches des autres membre de l'equipe
    $base = $bdd->prepare("SELECT t.IdT, t.TitreT, t.UserStoryT, t.CoutT, pt.Priorite FROM taches t
                            JOIN sprintbacklog sb ON t.IdT = sb.IdT
                            JOIN prioritestaches pt ON t.IdPriorite = pt.idPriorite
                            WHERE sb.IdU != :userId AND t.IdEq = :idEq");

    $base->bindParam(":userId", $userId);
    $base->bindParam(":idEq", $idEq);
    $base->execute();
    $TeamTasks = $base->fetchALL(PDO::FETCH_ASSOC);

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

    <h2><?php echo htmlspecialchars($Team['NomEqPrj']); ?></h2>



    <h2>Vos Tâches</h2>
    <ul>
        <?php foreach ($YourTasks as $YourTasks): ?>
            <li>
                <strong><?php echo htmlspecialchars($YourTasks['TitreT']); ?></strong><br>
                User Story: <?php echo htmlspecialchars($YourTasks['UserStoryT']); ?><br>
                Coût: <?php echo htmlspecialchars($YourTasks['CoutT']); ?><br>
                Priorité: <?php echo htmlspecialchars($YourTasks['Priorite']); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Tâches de votre equipe</h2>
    <ul>
        <?php foreach ($TeamTasks as $TeamTasks): ?>
            <li>
                <strong><?php echo htmlspecialchars($TeamTasks['TitreT']); ?></strong><br>
                User Story: <?php echo htmlspecialchars($TeamTasks['UserStoryT']); ?><br>
                Coût: <?php echo htmlspecialchars($TeamTasks['CoutT']); ?><br>
                Priorité: <?php echo htmlspecialchars($TeamTasks['Priorite']); ?>
            </li>
        <?php endforeach; ?>
    </ul>


</body>
</html>