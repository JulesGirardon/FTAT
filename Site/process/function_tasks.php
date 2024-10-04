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

function displayAllVotes($currPrj)
{
    global $bdd;
    try {
        $stmt = $bdd->prepare("CALL getAllVotes(:project_id)");
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

function displayAllDifficulties()
{
    global $bdd;
    try {
        $stmt = $bdd->prepare("CALL displayAllDifficulties()");
        $stmt->execute();
        $difficulties = [];

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $difficulties[] = $row;
            }
        }
        $stmt->closeCursor();
        return $difficulties;
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit;
    }
}
function insertCoutScrum($idT, $cout)
{
    global $bdd;
    try {
        $stmt = $bdd->prepare("SELECT insertCoutTache(:idT, :cout, :bool)");
        $stmt->bindParam(':idT', $idT);
        $stmt->bindParam(':cout', $cout);
        $stmt->bindValue(':bool', 1);  
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit;
    }
}
