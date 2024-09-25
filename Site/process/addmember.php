<?php
// RELIER A UN BOUTTON QUE SEULE LES SCRUM MASTER PEUVENT VOIR

include '../includes/connexionBDD.php';

echo $_POST['user'],$_POST['role'],$_POST['equipe'];

if(isset($_POST['user'],$_POST['role'],$_POST['equipe']))  //Recuperation des infos
{
    
    if (isset($bdd)) 
    {
        try 
        {
            $IdU = $_POST['user'];
            $IdR = getIdRole($_POST['role']);
            $IdEq = $_POST['equipe'];


            //Verification si le nouveau membre prends le role scrum master
            if ($IdR == getIdRole("Scrum Master")) // REVOIR SON INDDICE 
            {
                $sql = 'UPDATE rolesutilisateurprojet
                        JOIN projets ON projets.IdP = rolesutilisateurprojet.IdP
                        SET rolesutilisateurprojet.IdR = 0
                        WHERE projets.IdEq = :IdEq';

                $changeRole = $pdo->prepare($sql);
                $changeRole->bindParam(":IdEq", $IdEq);
                $changeRole->execute();
            }

            //Ajoute à l'equipe le nouveau membre
            $sql = 'INSERT INTO rolesutilisateurprojet (rolesutilisateurprojet.IdU, rolesutilisateurprojet.IdR, rolesutilisateurprojet.IdEq)
                    VALUES (:IdU,:IdR,:IdEq)';
            $addUser = $pdo->prepare($sql);

            $addUser->bindParam(":IdU",$IdU);
            $addUser->bindParam(":IdR", $IdR);
            $addUser->bindParam(":IdEq", $IdEq);
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