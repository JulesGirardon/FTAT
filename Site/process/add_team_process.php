<?php
include "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $team_name= $_POST['team_name'];
    $id_projet = $_POST['id_projet'];

    if (isset($bdd)) {
        try {
            $sql = "INSERT INTO ftat.equipesprj (NomEqPrj, IdP) VALUES (:team_name, :id_projet)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':team_name', $team_name);
            $stmt->bindParam(':id_projet', $id_projet);
            $stmt->execute();

            header("location: ../index.php?id=" . $id_projet);
            exit();
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    }
}
