<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
    <link rel="stylesheet" href="styles.min.css">
</head>

<body>
    <?php
    session_start();
    $_SESSION["currPrj"] = 1; // MODIFIER AVEC LA SESSION PROJET ACTUEL
    $_SESSION["IdUser"] = 1; // MODIFIER AVEC LA SESSION USER ACTUEL

    if (!isset($_SESSION['NextPage'])) {
        $_SESSION['NextPage'] = 0;
    }
    if (!isset($_SESSION['SessionPage'])) {
        $_SESSION['SessionPage'] = 0;
    }
    include_once "../process/function_tasks.php";
    $results = displayAllTasks($_SESSION["currPrj"]);
    $difficulty = "?";
    if ($_SESSION["NextPage"] >= count($results)):
        header("Location:../index.php");
    else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>User Story</th>
                    <th>Difficulté</th>
                </tr>
            </thead>
            <tbody id="task-body">
                <?php if ($results && count($results) > 0):
                    $taskId = $results[$_SESSION["NextPage"]]['IdT'];
                    $taskTitle = htmlspecialchars($results[$_SESSION["NextPage"]]['TitreT']);
                    $taskStory = htmlspecialchars($results[$_SESSION["NextPage"]]['UserStoryT']);
                    $difficultyDisplay = htmlspecialchars($difficulty); ?>
                    <tr class="task-row" data-task-id="<?php echo $taskId; ?>">
                        <td><?php echo $taskId; ?></td>
                        <td><?php echo $taskTitle; ?></td>
                        <td><?php echo $taskStory; ?></td>
                        <td><span id="difficulty-display"><?php echo $difficultyDisplay; ?></span></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="button-container">
            <button onclick="setDifficulty(this)" data-difficulty="?">?</button>
            <button onclick="setDifficulty(this)" data-difficulty="1">1</button>
            <button onclick="setDifficulty(this)" data-difficulty="3">3</button>
            <button onclick="setDifficulty(this)" data-difficulty="5">5</button>
            <button onclick="setDifficulty(this)" data-difficulty="10">10</button>
            <button onclick="setDifficulty(this)" data-difficulty="15">15</button>
            <button onclick="setDifficulty(this)" data-difficulty="25">25</button>
            <button onclick="setDifficulty(this)" data-difficulty="999">999</button>
        </div>
        <span id="char-count">Poster un commentaire (255 caractères restants)</span>
        <br>
        <form action="../process/pokerplanning_process.php" method="POST">
            <input type="hidden" name="userId" value="<?php echo isset($_SESSION['IdUser']) ? htmlspecialchars($_SESSION['IdUser']) : ''; ?>">
            <input type="hidden" name="IdT" value="<?php echo $taskId; ?>">
            <textarea id="comment" name="commentContent" maxlength="255" oninput="adjustTextAreaSize(this), ajustRemainChar(this)"></textarea>
            <input type="hidden" name="difficulty" value="<?php echo isset($difficulty) ? htmlspecialchars($difficulty) : '?'; ?>">
            <input type="submit" class="next-button" value="Suivant">
        </form>
        <a href="./planning_poker_resume.php">Planning formulaire resume </a>
    <?php endif; ?>

    <script src="../scripts/scripts.js"></script>
</body>

</html>