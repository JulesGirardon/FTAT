<?php
include_once "connexionBDD.php";

function displayAllTasks($currPrj)
{
    global $bdd;

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

function insertCout($idU, $idT, $commentaire, $coutMt)
{
    global $bdd;
    try {
       $stmt = $bdd->prepare("SELECT insertCoutMembreTache($idU,$idT,$commentaire,$coutMt)");
       //$stmt = $bdd->prepare("SELECT insertCoutMembreTache(1,3,'',5)");
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit;
    }
}
