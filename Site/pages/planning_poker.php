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
    $_SESSION["IdUser"] = 2; // MODIFIER AVEC LA SESSION USER ACTUEL
    if (!isset($_SESSION['NextPage'])) {
        $_SESSION['NextPage'] = 0;
    }
    if (!isset($_SESSION['SessionPage'])) {
        $_SESSION['SessionPage'] = 0;
    }
    include_once "../process/function_tasks.php";
    $results = displayAllTasks($_SESSION["currPrj"]);
    $difficulties = displayAllDifficulties();
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
            <form action="../process/pokerplanning_process.php" method="POST">
                <tbody id="task-body">
                    <?php if ($results && count($results) > 0):
                        $taskId = $results[$_SESSION["NextPage"]]['IdT'];
                        $taskTitle = htmlspecialchars($results[$_SESSION["NextPage"]]['TitreT']);
                        $taskStory = htmlspecialchars($results[$_SESSION["NextPage"]]['UserStoryT']);
                        $difficultyDisplay = isset($difficulty) ? htmlspecialchars($difficulty) : '?'; ?>

                        <tr class="task-row" data-task-id="<?php echo $taskId; ?>">
                            <td><?php echo $taskId; ?></td>
                            <td><?php echo $taskTitle; ?></td>
                            <td><?php echo $taskStory; ?></td>
                            <td> <select name="difficulty" id="id_task" required>
                                    <option value="">-- Sélectionner une difficulté --</option>
                                    <?php foreach ($difficulties as $difficulty): ?>
                                        <option value="<?php echo $difficulty['value']; ?>">
                                            <?php echo $difficulty['value']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
        </table>
        <span id="char-count">Poster un commentaire (255 caractères restants)</span>
        <br>
        <input type="hidden" name="userId" value="<?php echo isset($_SESSION['IdUser']) ? htmlspecialchars($_SESSION['IdUser']) : ''; ?>">
        <input type="hidden" name="IdT" value="<?php echo $taskId; ?>">
        <textarea id="comment" name="commentContent" maxlength="255" oninput="adjustTextAreaSize(this), ajustRemainChar(this)"></textarea>
        <input type="submit" class="next-button" value="Suivant">
        </form>
    <?php endif; ?>

    <a href="./planning_poker_resume.php">Planning formulaire resume </a>
    <a href="./planning_poker_scrum.php">Planning formulaire scrum </a>


    <script src="../scripts/scripts.js"></script>
</body>

</html>