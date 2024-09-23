<?php
// RELIER A UNE PAGE QUE SEULE LES SCRUM MASTER PEUVENT VOIR

include '../includes/connexionBDD.php';

echo $_POST['user'],$_POST['role'],$_POST['equipe'];

if(isset($_POST['user'],$_POST['role'],$_POST['equipe']))  //Recuperation des infos
{
    
    if (isset($bdd)) 
    {
        try 
        {
            $IdU = $_POST['user'];
            $IdR = $_POST['role'];
            $IdEq = $_POST['equipe'];

            //Ajoute à l'equipe le nouveau membre
            $sql = 'INSERT INTO rolesutilisateurprojet (rolesutilisateurprojet.IdU, rolesutilisateurprojet.IdR, rolesutilisateurprojet.IdEq)
                    VALUES (:IdU,:IdR,:IdEq)';
            $addUser = $pdo->prepare($sql);

            $addUser->bindParam(":IdU",$IdU);
            $stmt->bindParam(":IdR", $IdR);
            $stmt->bindParam(":IdEq", $IdEq);
            $addUser->execute();

            if ($addUser->rowCount() > 0) {
                echo 'Insertion réussie !';
            } else {
                echo 'Erreur d\'insertion.';
            }
        }
        catch (PDOException $e)
        {
            // Rollback transaction on error
            $bdd->rollBack();
            error_log("Database error: " . $e->getMessage());
            die('An error occurred while processing your request.');
        }
    }
    header('Location: '."../pages/projet.php");
}
?>