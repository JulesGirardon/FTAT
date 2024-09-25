<?php

include "../process/function_tasks.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $projectId = $_POST['projectId'];
    $commentContent = $_POST['commentContent'];
    $difficulty = $_POST['difficulty'];
    echo $userId, $projectId, $commentContent, $difficulty;
    insertCout($userId, $projectId, $commentContent, null);
}
