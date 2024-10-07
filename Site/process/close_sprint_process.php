<?php
// Démarrer la session
session_start();

include "../includes/connexionBDD.php";
if(isset($bdd, $_POST['IdS'])){
    try {
        $bdd->beginTransaction();

        $IdS = $_POST['IdS'];

        $sql = "UPDATE ftat.sprints AS s SET s.DateFinS = CURDATE() WHERE s.IdS = :IdS";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':IdS', $IdS);
        $stmt->execute();

        $bdd->commit();

        header('Location: ../index.php?id=' . $_POST['id_projet']);
    } catch (PDOException $e) {
        $bdd->rollBack();
        echo $e->getMessage();
        header('Location: ../fail.php');
        exit();
    }
}