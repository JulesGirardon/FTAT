<?php
session_start();

include "../includes/connexionBDD.php";
include "./function.php";

if (isset($_POST['project_name'], $_POST['project_description'], $_POST['project_date_fin'], $_POST['scrum_master'])) {
    $project_name = $_POST['project_name'];
    $project_description = $_POST['project_description'];
    $project_date_fin = $_POST['project_date_fin'];
    $scrum_master = (int)$_POST['scrum_master'];

    if (isset($bdd)) {
        try {
            $bdd->beginTransaction();

            $sql_addTeam = "INSERT INTO ftat.equipesprj(NomEqPrj) VALUES (:project_name) ";
            $stmt = $bdd->prepare($sql_addTeam);
            $stmt->bindParam(":project_name", $project_name);
            $stmt->execute();
            $IdEq = $bdd->lastInsertId();

            $sql_addProject = "INSERT INTO ftat.projets(NomP, DescriptionP, DateDebutP, DateFinP, IdEq) VALUES (:NomP, :DescriptionP, CURDATE(), :DateFinP, :IdEq)";
            $stmt = $bdd->prepare($sql_addProject);
            $stmt->bindParam(":NomP", $project_name);
            $stmt->bindParam(":DescriptionP", $project_description);
            $stmt->bindParam(":DateFinP", $project_date_fin);
            $stmt->bindParam(":IdEq", $IdEq);
            $stmt->execute();

            $IdProject = $bdd->lastInsertId();
            $IdR_SM = getIdRole('Scrum Master');

            $sql_addRUP = "INSERT INTO ftat.rolesutilisateurprojet(IdU, IdP, IdR) VALUES (:IdU, :IdP, :IdR)";
            $stmt = $bdd->prepare($sql_addRUP);
            $stmt->bindParam(":IdU", $scrum_master);
            $stmt->bindParam(":IdP", $IdProject);
            $stmt->bindParam(":IdR", $IdR_SM);
            $stmt->execute();

            $bdd->commit();
        } catch (PDOException $e) {
            $bdd->rollBack();
            error_log("Database error: " . $e->getMessage());
            die('An error occurred while processing your request.' . $e->getMessage());
        }
        header('Location: '."../pages/create_project.php");

    }
}