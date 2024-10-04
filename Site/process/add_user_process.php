<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user= $_POST['user_id'];
    $id_projet = $_POST['id_projet'];
    $id_role = $_POST['id_role'];

    if (isset($bdd)) {
        try {
            $sql = "INSERT INTO ftat.rolesutilisateurprojet VALUES (:id_user, :id_projet, :id_role)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->bindParam(':id_role', $id_role);
            $stmt->execute();
            header("location: ../index.php?id=" . $id_projet);
            exit();
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
            header("location : ../index.php?id=" . $id_projet . "?error=error");
            exit();
        }
    }
}

