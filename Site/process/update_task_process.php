<?php
include "../includes/connexionBDD.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_state = $_POST['task_state'];
    $id_task = $_POST['id_task'];

    $id_projet = $_POST['id_projet'];

    if(isset($bdd)) {
        try {
            $sql = "UPDATE ftat.sprintbacklog SET IdEtat = :task_state WHERE IdT = :id_task;";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':task_state', $task_state);
            $stmt->bindParam(':id_task', $id_task);

            $stmt->execute();
            header("Location: ../index.php?id=" . $id_projet);
            exit();
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Un autre sprint est déjà actif') !== false) {
                $_SESSION['error'] = "dateSprint";
                header("Location: ../index.php?id=" . $id_projet);
                exit();
            } else {
                echo $e->getMessage();
                header("Location: ../index.php?id=" . $id_projet);
                exit();
            }
        }
    }
}