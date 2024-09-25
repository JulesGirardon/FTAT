<?php

include "../process/function_tasks.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'];
    $projectId = $_POST['projectId'];
    $commentContent = $_POST['commentContent'];
    $difficulty = $_POST['difficulty'];

    if (!empty($userId) && !empty($projectId) && !empty($commentContent)) {
        echo "C'est pas vide";

        insertCout($userId, $projectId, $commentContent, null);
    }
}
