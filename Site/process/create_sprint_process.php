<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

if (isset($_POST['date_deb'], $_POST['date_fin'], $_POST['select_project_to_add_sprint'])) {
    $date_deb = $_POST['date_deb'];
    $date_fin = $_POST['date_fin'];
    $select_project_to_add_sprint = $_POST['select_project_to_add_sprint'];

    if (isset($bdd)) {
        try {
            $bdd->beginTransaction();

            $sql_addSprint = "INSERT INTO ftat.sprints(DateDebS, DateFinS, IdP, VelociteEqPrj) VALUES (:DateDebS, :DateFinS, :IdP, 0)";
            $stmt = $bdd->prepare($sql_addSprint);
            $stmt->bindParam(':DateDebS', $date_deb);
            $stmt->bindParam(':DateFinS', $date_fin);
            $stmt->bindParam(':DateFinS', $date_fin);
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