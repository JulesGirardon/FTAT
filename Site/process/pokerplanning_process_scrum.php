<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../process/function_tasks.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idT = $_POST['IdT'];
    $difficulty = $_POST['difficulty'];

    // Insertion des données dans la base de données (fonction à implémenter)
    insertCoutScrum($idT, $difficulty);


    $_SESSION['NextPage'] = $_SESSION['NextPage'] + 1;
    $_SESSION['SessionPage'] = $_SESSION['NextPage'];

    // Redirection vers la page suivante
    $redirectUrl = '../pages/planning_poker_scrum.php';
    header("Location: " . $redirectUrl);
    exit();
}
