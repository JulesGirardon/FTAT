<?php
include "../includes/connexionBDD.php";

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$idEq = $_POST['equipe'];

try {

    // Récupérer le projet
    $base = $bdd->prepare("SELECT equipesprj.NomEqPrj, rolesutilisateurprojet.IdR FROM equipesprj
						   JOIN projets ON projets.IdEq = equipesprj.IdEq
                           JOIN rolesutilisateurprojet ON projets.IdP = rolesutilisateurprojet.IdP
                           WHERE rolesutilisateurprojet.IdU = :userId AND equipesprj.IdEq = :idEq");
    
    $base->bindParam(":userId", $userId);
    $base->bindParam(":idEq", $idEq);
    $base->execute();
    $Projet = $base->fetch(PDO::FETCH_ASSOC);


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

    <h2><?php echo htmlspecialchars($Projet['NomEqPrj']); ?></h2>



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
    
    <h2>Les membres de l'equipe</h2>
    <form action="../process/addmember.php" method="POST">

<?php 

        if ($Projet["IdR"] == getIdRole("Scrum Master"))
        {


//ROLE
            echo '<select id="role" name="role">
                  <option>Member</option>
                  <option>Product Owner</option>
                  <option>Scrum Master</option>
                  </select>';



//MEMBRE
            echo '<select id="user" name="user">';
            if (isset($bdd))
            {
                $sql = "SELECT IdU, NomU, PrenomU FROM utilisateurs";
                $stmt = $bdd->prepare($sql);
                $stmt->execute();

                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                    echo "<option value='" . $row['IdU'] . "'>" . $row['PrenomU'] . " " . $row['NomU'] . "</option>";
                }
            }
            echo '</select>';

//EQUIPE EST DEJA CONNU
            echo "<input type='hidden' name='equipe' value=" . $idEq . ">";
            echo '<button type="submit">AddMember</button>';
        }
?>
    </form>

<?php
//AFFICHAGE AUTRES MEMBRES DE L'EQUIPE

    if (isset($bdd))
    {
        $sql = "SELECT NomU, PrenomU, Statut FROM membre_equipe
                JOIN utilisateurs ON utilisateurs.IdU = membre_equipe.IdU
                WHERE membre_equipe.IdEq = :idEq";
        $base = $bdd->prepare($sql);
        $base->bindParam(":idEq", $idEq);
        $base->execute();

        while($row = $base->fetch(PDO::FETCH_ASSOC))
        {
            echo "<p>" . $row['PrenomU'] . " " . $row['NomU'] . " " . $row['Statut'] . "</p>";
        }
    }
?>
</body>
</html>