<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include "../process/function_tasks.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idT = $_POST['IdT'];
    $difficulty = $_POST['difficulty'];
    insertCoutScrum($idT, $difficulty);
    $_SESSION['NextPage'] = $_SESSION['NextPage'] + 1;
    $_SESSION['SessionPage'] = $_SESSION['NextPage'];
    $redirectUrl = '../pages/planning_poker_scrum.php';
    header("Location: " . $redirectUrl);
    exit();
}
