<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_task = $_POST['id_task'];
    $id_sprint = $_POST['id_sprint'];
    $id_user = $_POST['id_user'];
    $id_etat = 1;


    try {
        $sql = "INSERT INTO sprintbacklog VALUES (:id_task, :id_sprint, :id_user, :id_etat)";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_task', $id_task);
        $stmt->bindParam(':id_sprint', $id_sprint);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->bindParam(':id_etat', $id_etat);
        $stmt->execute();

        header("location: ../pages/projet.php?id=" . $id_projet);
        exit();
    } catch (Exception $e) {
        echo $e->getMessage();
        header("location : ../pages/projet.php?id=" . $id_projet . "?error=error");
        exit();
    }
}