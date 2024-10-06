<?php
include "../includes/connexionBDD.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dateDeb = $_POST['startDate'];
    $dateFin = $_POST['endDate'];
    $id_equipe = $_POST['id_equipe'];

    $id_projet = $_POST['id_projet'];

    if (isset($bdd)) {
        try {
            $sql = "INSERT INTO ftat.sprints (DateDebS, DateFinS, IdEq) VALUES (:dateDeb, :dateFin, :id_equipe)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':dateDeb', $dateDeb);
            $stmt->bindParam(':dateFin', $dateFin);
            $stmt->bindParam(':id_equipe', $id_equipe);

            $stmt->execute();

            header("Location: ../index.php?id=" . $id_projet);
            exit();
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Un autre sprint est déjà actif') !== false) {
                $_SESSION['error'] = "dateSprint";
                header("Location: ../index.php?id=" . $id_projet);
                exit();
            } else {
                echo $e->getMessage();
                header("Location: ../index.php?id=" . $id_projet);
                exit();
            }
        }
    }
}