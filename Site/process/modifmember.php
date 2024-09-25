<?php
// RELIER A UN BOUTTON QUE SEULE LES SCRUM MASTER PEUVENT VOIR

include '../includes/connexionBDD.php';
session_start();

if (!isset($_SESSION['user_id'])) 
{
    header("Location: login.php");
    exit();
}

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

            $scrum = getIdRole("Scrum Master");
            $product = getIdRole("Product Owner");

            //Verification si le nouveau membre prends le role scrum master
            if ($IdR == $scrum || $IdR == $product )
            {
                $member = getIdRole("Member");
                $sql = 'UPDATE rolesutilisateurprojet
                        JOIN projets ON projets.IdP = rolesutilisateurprojet.IdP
                        SET rolesutilisateurprojet.IdR = :member
                        WHERE projets.IdEq = :IdEq AND rolesutilisateurprojet.IdR = :scrum 
                        OR projets.IdEq = :IdEq AND rolesutilisateurprojet.IdR = :product ';

                $changeRole = $pdo->prepare($sql);
                $changeRole->bindParam(":IdEq", $IdEq);
                $changeRole->bindParam(":member",$member)
                $changeRole->bindParam(":scrum",$scrum)
                $changeRole->bindParam(":product",$product)
                $changeRole->execute();
            }

            //Modif d'un membre de l'equipe
            $sql = 'UPDATE rolesutilisateurprojet
                    JOIN projets ON projets.IdP = rolesutilisateurprojet.IdP
                    SET rolesutilisateurprojet.IdR = :IdR
                    WHERE projets.IdEq = :IdEq AND rolesutilisateurprojet.IdU = :IdU';

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