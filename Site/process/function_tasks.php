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
?>
