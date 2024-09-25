<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

if (isset($_POST['date_fin'], $_POST['retrospective'], $_POST['revue'], $_POST['select_project_to_add_sprint'])) {
    $date_fin = $_POST['date_fin'];
    $retrospective = $_POST['retrospective'];
    $revue = $_POST['revue'];
    $select_project_to_add_sprint = $_POST['select_project_to_add_sprint'];

    if (isset($bdd)) {
        try {
            $bdd->beginTransaction();

            $sql_addSprint = "INSERT INTO ftat.sprints(DateDebS, DateFinS, RetrospectiveS, RevueDeSprint, IdP, VelociteEqPrj) VALUES (CURDATE(), :DateFinS, :RetrospectiveS, :RevueDeSprint, :IdP, 0)";
            $stmt = $bdd->prepare($sql_addSprint);
            $stmt->bindParam(':DateFinS', $date_fin);
            $stmt->bindParam(':RetrospectiveS', $retrospective);
            $stmt->bindParam(':RevueDeSprint', $revue);
            $stmt->bindParam(':IdP', $select_project_to_add_sprint);
            $stmt->execute();

            $bdd->commit();
        } catch (Exception $e) {
            $bdd->rollBack();
            die($e->getMessage());
        }
        header('Location: '."../pages/create_sprint.php");
    }
}