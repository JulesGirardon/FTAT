<?php
include_once "./connexionBDD.php";
function displayAllTasks()
{
    try {
        
        $stmt = $pdo->prepare("CALL getAllTasks()");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $pdo = null;
        return $results;
    } catch (PDOException $e) {
        echo "Erreur de base de données : " . $e->getMessage();
        exit;
    }
}
?>