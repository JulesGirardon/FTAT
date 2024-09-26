<?php
session_start();
include '../includes/connexionBDD.php';
include "../includes/function.php";

$userId = $_SESSION['user_id'];
$ideaId = $_POST['idea_id'];

$query = "SELECT i.Id_Idee_bas
          FROM idees_bac_a_sable i
          JOIN projets p ON i.IdEq = p.IdEq
          JOIN rolesutilisateurprojet ru ON p.IdP = ru.IdP
          WHERE i.Id_Idee_bas = :ideaId AND ru.IdU = :userId AND ru.IdR = (ftat.getIdRole('Product Owner'))";
if(isset($bdd)){
    $stmt = $bdd->prepare($query);
    $stmt->execute(['ideaId' => $ideaId, 'userId' => $userId]);

    if ($stmt->rowCount() > 0) {

        $deleteQuery = "DELETE FROM idees_bac_a_sable WHERE Id_Idee_bas = :ideaId";
        $deleteStmt = $bdd->prepare($deleteQuery);
        $deleteStmt->execute(['ideaId' => $ideaId]);

        header("Location: bac_a_sable.php?message=IdeaDeleted");
        exit;
    } else {
        echo "You do not have permission to delete this idea.";
        exit;
    }
}

?>