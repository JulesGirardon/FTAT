<?php
session_start();

include "../process/function_tasks.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $idT = $_POST['IdT'];
    $commentContent = $_POST['commentContent'];
    $difficulty = $_POST['difficulty'];

    //insertCout($userId, $idT, $commentContent, $difficulty);

    $nextPage = $idT +1;
    
    $baseUrl = '../pages/';
    $redirectUrl = $baseUrl . 'planning_poker.php?page=' . $nextPage;

    header("Location: " . $redirectUrl);
    exit();
}
?>
