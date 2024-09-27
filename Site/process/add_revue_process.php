<?php

include "../includes/connexionBDD.php";
if (isset($_POST['id_sprint'], $_POST['s_revue'], $bdd)) {

    try {
        $bdd->beginTransaction();

        $id_sprint = $_POST['id_sprint'];
        $s_revue = $_POST['s_revue'];

        $sql = "UPDATE ftat.sprints AS s SET s.RevueDeSprint = :revue WHERE s.IdS = :id_sprint";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_sprint', $id_sprint);
        $stmt->bindParam(':revue', $s_revue);
        $stmt->execute();

        $bdd->commit();

    } catch (PDOException $e) {
        echo 'Erreur : ' . $e->getMessage();
    }

    header('Location: ../pages/add_revue.php');
}