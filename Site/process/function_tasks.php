<?php
include_once "connexionBDD.php";

/*function displayAllTasks($currPrj)
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
}*/
function displayAllTasks($currPrj)
{
    if (isset($bdd)) {
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
    } else {
        echo "La connexion à la base de données n'a pas pu être établie.";
    }
}

function insertCout($idU, $idT, $commentaire, $coutMt)
{
    if (isset($bdd)) {
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
    } else {
        echo "La connexion à la base de données n'a pas pu être établie.";
    }
}
