<?php
session_start();
include '../include/connexionBDD.php';

// Get the user ID and the idea ID to be deleted
$userId = $_SESSION['user_id'];
$ideaId = $_POST['idea_id'];

// Check if the user is the Product Owner for the project linked to this idea
$query = "SELECT i.Id_Idee_bas
          FROM idees_bac_a_sable i
          JOIN projets p ON i.IdEq = p.IdEq
          JOIN rolesutilisateurprojet ru ON p.IdP = ru.IdP
          WHERE i.Id_Idee_bas = :ideaId AND ru.IdU = :userId AND ru.IdR = 1"; // 1 = Product Owner role
if(isset($bdd)){
    $stmt = $bdd->prepare($query);
    $stmt->execute(['ideaId' => $ideaId, 'userId' => $userId]);

    if ($stmt->rowCount() > 0) {
        // The user is the Product Owner, allow deletion
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
