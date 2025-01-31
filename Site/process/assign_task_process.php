<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_task = $_POST['id_task'];
    $id_sprint = $_POST['id_sprint'];
    $id_user = $_POST['id_user'];
    $id_projet = $_POST['id_projet'];
    $id_etat = 1;


    if(isset($bdd)) {
        try {
            $sql = "INSERT INTO ftat.sprintbacklog(IdT, IdS, IdU, IdEtat) VALUES (:id_task, :id_sprint, :id_user, :id_etat)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_task', $id_task);
            $stmt->bindParam(':id_sprint', $id_sprint);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_etat', $id_etat);
            $stmt->execute();

            header("location: ../index.php?id=" . $id_projet);
            exit();
        } catch (PDOException $e) {
            echo $e->getMessage();
            //header('Location: ../fail.php');
            //exit();
        }
    }
}