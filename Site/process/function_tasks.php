<?php
include_once "connexionBDD.php";
function displayAllTasks($currPrj)
{
    global $bdd;
    try {
        $stmt = $bdd->prepare("CALL getAllTasks(:project_id)");
        $stmt->bindParam(':project_id', $currPrj);
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
        $stmt = $bdd->prepare("SELECT insertCoutMembreTache(:idU, :idT, :commentaire, :coutMt)");
        $stmt->bindParam(':idU', $idU);
        $stmt->bindParam(':idT', $idT);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':coutMt', $coutMt);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit;
    }
}
