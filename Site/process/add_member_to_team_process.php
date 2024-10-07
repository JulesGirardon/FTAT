<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_POST['id_user'];
    $id_equipe = $_POST['id_equipe'];
    $id_projet = $_POST['id_projet'];

    if (isset($bdd, $id_user, $id_equipe, $id_projet)) {
        try {
            $sql = "INSERT INTO membre_equipe VALUES (:id_equipe, :id_user)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':id_equipe', $id_equipe);
            $stmt->execute();

            header("location: ../index.php?id=" . $id_projet);
            exit();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else{
        echo "erreur paramètre pas def";
        echo $id_user;
        echo $id_equipe;
        echo $id_projet;
    }
}
