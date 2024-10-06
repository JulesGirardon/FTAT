<?php
// Démarrer la session
session_start();

include "../includes/connexionBDD.php";
    if(isset($bdd)){
        try {
            $bdd->beginTransaction();

            $description = $_POST['bac_desc'];
            $user_id = $_POST['bac_IdU'];
            $equipe_id = $_POST['bac_IdEq'];

            $sql = "INSERT INTO ftat.idees_bac_a_sable (desc_Idee_bas, IdU, IdEq) VALUES (:description, :user_id, :equipe_id)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":equipe_id", $equipe_id);
            $stmt->execute();

            $bdd->commit();

            header('Location: ../index.php?id=' . $_POST['id_projet']);

        } catch (PDOException $e) {
            $bdd->rollBack();
            header('Location: ../fail.php');
            exit();
        }
    }
?>




