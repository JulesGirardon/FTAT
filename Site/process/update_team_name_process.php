<?php
include "../includes/connexionBDD.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_projet = $_POST['id_projet'];
    $id_equipe = $_POST['id_equipe'];
    $old_team_name = $_POST['old_team_name'];
    $new_team_name = $_POST['new_team_name'];

    if (isset($bdd, $id_equipe, $old_team_name, $new_team_name)) {
        if ($old_team_name == $new_team_name){
            $_SESSION['error'] = "same_team_name";
            header("location: ../index.php?id=" . $id_projet);
            exit();
        } else{
            try {
                $sql = "UPDATE equipesprj SET NomEqPrj = :new_team_name WHERE IdEq = :id_equipe";
                $stmt = $bdd->prepare($sql);
                $stmt->bindParam(':id_equipe', $id_equipe);
                $stmt->bindParam(':new_team_name', $new_team_name);
                $stmt->execute();

                header("location: ../index.php?id=" . $id_projet);
                exit();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
    }
    }
}
