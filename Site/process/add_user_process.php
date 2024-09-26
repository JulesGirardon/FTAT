<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $id_projet = $_POST['id_projet'];

    try {
        $bdd->beginTransaction();
        $sql = "SELECT IdEq FROM projets WHERE IdP = :id_projet";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':id_projet', $id_projet);
        $stmt->execute();
        $id_eq = $stmt->fetchColumn();

        if ($id_eq) {
            $sql = "INSERT INTO membre_equipe (IdEq, IdU) VALUES (:id_eq, :user_id)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_eq', $id_eq);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $bdd->commit();
            echo "L'utilisateur a été ajouté avec succès.";
            header("location: ../pages/projet.php?id=" . $id_projet);
            exit();
        }
    } catch (Exception $e) {
        $bdd->rollBack();
        header("location : ../pages/projet.php?id=" . $id_projet . "?error=error");
    }
}

