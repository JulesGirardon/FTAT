<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['NextPage'])) {
        $_SESSION['NextPage'] = 0;
    }
    if (!isset($_SESSION['SessionPage'])) {
        $_SESSION['SessionPage'] = 0;
    }
    include_once "../includes/function.php";
    $results = displayAllTasks($_SESSION["currPrj"]);
    $difficulties = displayAllDifficulties();
    if ($_SESSION["NextPage"] >= count($results)):
        $_SESSION['NextPage'] = 0;
        $_SESSION['SessionPage'] = 0;
        header("Location:../index.php?=" . $_SESSION['currPrj']);
        exit();
    else: ?>
        <div class="projet">
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
                            <td><select name="difficulty" id="id_task" required>
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
                    <h2 id="char-count">Poster un commentaire (255 caractères restants)</h2>
                    <br>
                    <input type="hidden" name="userId" value="<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : ''; ?>">
                    <input type="hidden" name="IdT" value="<?php echo $taskId; ?>">
                    <input type="hidden" name="id_projet" value="<?php echo $_SESSION['currPrj']; ?>">
                    <textarea id="comment" name="commentContent" maxlength="255" oninput="adjustTextAreaSize(this), ajustRemainChar(this)"></textarea>
                    <input type="submit" class="next-button" value="Suivant">
                </form>
            </table>
        </div>
    <?php endif; ?>
    <script src="../scripts/scripts.js"></script>
</body>

</html>