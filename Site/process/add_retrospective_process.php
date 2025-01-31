<?php

include "../includes/connexionBDD.php";

if (isset($_POST['id_sprint'], $_POST['s_retro'], $_POST['id_projet'], $bdd)) {
    try {
        $bdd->beginTransaction();

        $id_sprint = $_POST['id_sprint'];
        $s_retro = $_POST['s_retro'];

        $sql = "UPDATE ftat.sprints AS s SET s.RetrospectiveS = :retro WHERE s.IdS = :id_sprint";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_sprint', $id_sprint);
        $stmt->bindParam(':retro', $s_retro);
        $stmt->execute();

        $bdd->commit();

    } catch (PDOException $e) {
        header('Location: ../fail.php');
        exit();
    }

    header('Location: ../index.php?id=' . $_POST['id_projet']);
}