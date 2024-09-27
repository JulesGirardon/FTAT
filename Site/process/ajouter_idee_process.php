<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour ajouter une idée.";
    exit;
}

include "../includes/connexionBDD.php";
if(isset($bdd)){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $description = $_POST['desc_Idee_bas'];
        $user_id = $_SESSION['user_id'];
        $project_id = $_POST['IdP'];

        $sql = "INSERT INTO ftat.idees_bac_a_sable (desc_Idee_bas, IdU, IdP) VALUES (:description, :user_id, :project_id)";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":project_id", $project_id);
        $stmt->execute();

        echo "L'idée a été ajoutée avec succès.";
    }
}

?>




