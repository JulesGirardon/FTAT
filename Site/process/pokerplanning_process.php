<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../process/function_tasks.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $idT = $_POST['IdT'];
    $commentContent = $_POST['commentContent'];
    $difficulty = $_POST['difficulty'];
    $nextPage = $_SESSION['NextPage'] + 1;
    $_SESSION['NextPage'] = $nextPage;
    $_SESSION['SessionPage'] = $nextPage;
    $baseUrl = '../pages/';
    $redirectUrl = $baseUrl . 'planning_poker.php?page=' . $nextPage;
    insertCout($userId, $idT, $commentContent, $difficulty);
    header("Location: " . $redirectUrl);
}
