<?php
include "../includes/connexionBDD.php";
include "../includes/function.php";

if (isset($bdd) && isset($_POST['task_title'], $_POST['task_userstory'], $_POST['task_project'], $_POST['task_idpriority'])) {
    try {
        $bdd->beginTransaction();

        $task_title = $_POST['task_title'];
        $task_userstory = $_POST['task_userstory'];
        $task_project = $_POST['task_project'];
        $task_idpriority = $_POST['task_idpriority'];

        $sql = "INSERT INTO ftat.taches(TitreT, UserStoryT, IdP, CoutT, IdPriorite) VALUES (:TitreT, :UserStoryT, :IdP, '?', :IdPriorite)";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':TitreT', $task_title);
        $stmt->bindParam(':UserStoryT', $task_userstory);
        $stmt->bindParam(':IdP', $task_project);
        $stmt->bindParam(':IdPriorite', $task_idpriority);
        $stmt->execute();

        $bdd->commit();
    } catch (PDOException $e) {
        $bdd->rollBack();
        echo $e->getMessage();
    }
    header('Location: ../pages/create_task.php');
}