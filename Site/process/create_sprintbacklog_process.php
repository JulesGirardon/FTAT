<?php
session_start();

include "../includes/connexionBDD.php";
include "../includes/function.php";

if (isset($_POST['sb_taches'], $_POST['sb_sprint'], $_POST['sb_user'], $_POST['sb_etat'], $bdd)) {
    try {
        $bdd->beginTransaction();

        $sb_taches = $_POST['sb_taches'];
        $sb_sprint = $_POST['sb_sprint'];
        $sb_user = $_POST['sb_user'];
        $sb_etat = $_POST['sb_etat'];

        $sql = "INSERT INTO ftat.sprintbacklog(IdT, IdS, IdU, IdEtat) VALUES (:IdT, :IdS, :IdU, :IdEt)";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':IdT', $sb_taches);
        $stmt->bindParam(':IdS', $sb_sprint);
        $stmt->bindParam(':IdU', $sb_user);
        $stmt->bindParam(':IdEt', $sb_etat);
        $stmt->execute();

        $bdd->commit();
    } catch (Exception $e) {
        $bdd->rollBack();
        echo $e->getMessage();
    }
    header('Location: ../pages/create_sprintbacklog.php');
}