<?php
// Démarrer la session
session_start();

include "../includes/connexionBDD.php";
    if(isset($bdd)){
        try {
            $bdd->beginTransaction();

            $IdBAC = $_POST['idee_bac_del'];

            $sql = "DELETE FROM ftat.idees_bac_a_sable WHERE Id_Idee_bas = :IdBAS;";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(":IdBAS", $IdBAC);
            $stmt->execute();

            $bdd->commit();

            header('Location: ../index.php?id=' . $_POST['id_projet']);

        } catch (PDOException $e) {
            $bdd->rollBack();
            echo $e->getMessage();
            //header('Location: ../fail.php');
            exit();
        }
    }
?>




