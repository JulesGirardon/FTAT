<?php
include_once "connexionBDD.php";

function displayAllTasks($currPrj)
{
    if (isset($bdd)) {

        try {
            $stmt = $bdd->prepare("CALL getAllTasks($currPrj)");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }
}

function insertCout($idU, $idT, $commentaire, $coutMt)
{
    if (isset($bdd)) {
        try {
            $stmt = $bdd->prepare("SELECT insertCoutMembreTache($idU,$idT,$commentaire,$coutMt)");
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur de base de données : " . $e->getMessage();
            exit;
        }
    }
}
?>