<?php
session_start();

include "../process/function_tasks.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $idT = $_POST['IdT'];
    $commentContent = $_POST['commentContent'];
    $difficulty = $_POST['difficulty'];

    insertCout($userId, $idT, $commentContent, $difficulty);

    $itemsPerPage = isset($_SESSION["itemsPerPage"]) ? $_SESSION["itemsPerPage"] : 10;
    $totalTasks = count(displayAllTasks($_SESSION["currPrj"]));
    
    $totalPages = ceil($totalTasks / $itemsPerPage);

    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $nextPage = min($currentPage + 1, $totalPages);

    $baseUrl = '../pages/';
    $redirectUrl = $baseUrl . 'planning_poker.php?page=' . $nextPage;

    header("Location: " . $redirectUrl);
    exit();
}
?>
