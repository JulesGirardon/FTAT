<?php
include "../includes/connexionBDD.php";

if (isset($_POST['project_name'], $_POST['scrum_master'])) {
    $project_name = $_POST['project_name'];
    $scrum_master = (int)$_POST['scrum_master'];

    if (isset($bdd)) {
        try {
            $bdd->beginTransaction();

            $sql = "INSERT INTO equipesprj(NomEqPrj) VALUES (:project_name) ";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(":project_name", $project_name);
            $stmt->execute();
            $IdEq = $bdd->lastInsertId();

            $sql_IdR_SM = "SELECT IdR FROM roles WHERE DescR='Scrum Master'";
            $stmt = $bdd->prepare($sql_IdR_SM);
            $stmt->execute();

            $IdR_SM = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($IdR_SM) {
                $sql_addRolesUtilisateurProjet = "INSERT INTO rolesutilisateurprojet(IdU, IdR, IdEq) VALUES (:IdU, :IdR, :IdEq)";
                $stmt = $bdd->prepare($sql_addRolesUtilisateurProjet);
                $stmt->bindParam(":IdU", $scrum_master);
                $stmt->bindParam(":IdR", $IdR_SM['IdR']);
                $stmt->bindParam(":IdEq", $IdEq);
                $stmt->execute();
            }
            $bdd->commit();
        } catch (PDOException $e) {
            // Rollback transaction on error
            $bdd->rollBack();
            error_log("Database error: " . $e->getMessage());
            die('An error occurred while processing your request.');
        }

        header('Location: '."../pages/create_project.php");

    }
}