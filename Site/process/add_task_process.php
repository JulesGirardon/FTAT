<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_projet = $_POST['id_projet'];

    $task_title = $_POST['task_title'];
    $task_description =  $_POST['task_description'];
    $task_priority = $_POST['task_priority'];

    if(isset($bdd)) {
        try {
            $sql = "INSERT INTO taches (TitreT, UserStoryT, IdP, CoutT, IdPriorite) VALUES (:titre, :story, :id_projet, '?', :id_prio)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':titre', $task_title);
            $stmt->bindParam(':story', $task_description);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->bindParam(':id_prio', $task_priority);
            $stmt->execute();

            header("location: ../index.php?id=" . $id_projet);
            exit();
        } catch (PDOException $e) {
            echo $e->getMessage();
            header("location : ../index.php?id=" . $id_projet . "?error=error");
            exit();
        }
    }
}