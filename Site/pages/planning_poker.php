<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php
    if (!isset($_SESSION)) {
        session_start();
    }
    $_SESSION["currPrj"] = 1;
    $_SESSION["IdUser"] = 1;
    include_once "../process/function_tasks.php";
    $itemsPerPage = 1;
    $results = displayAllTasks($_SESSION["currPrj"]);
    $totalPages = ceil(count($results) / $itemsPerPage);
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

    if ($currentPage < 1 || $currentPage > $totalPages) {
        $currentPage = 1;
        header("Location: ?page=$currentPage");
    }

    $difficulty = "?";
    $startIndex = ($currentPage - 1) * $itemsPerPage;
    $limitedResults = array_slice($results, $startIndex, $itemsPerPage);

    if ($limitedResults && count($limitedResults) > 0) {
        $currentTask = end($limitedResults);
        unset($limitedResults[array_key_last($limitedResults)]);
        $limitedResults[] = $currentTask;
    ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>User Story</th>
                    <th>Difficulté</th>
                </tr>
            </thead>
            <tbody id="task-body">
                <?php foreach ($limitedResults as $row): ?>
                    <tr class="task-row" data-task-id="<?php echo htmlspecialchars($row['IdT']); ?>">
                        <td><?php echo htmlspecialchars($row['TitreT']); ?></td>
                        <td><?php echo htmlspecialchars($row['UserStoryT']); ?></td>
                        <td><span id="difficulty-display"><?php echo htmlspecialchars($difficulty); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "Aucune tâche trouvée.";
    }
    ?>

    <br>
    <div class="button-container">
        <button onclick="setDifficulty(this)" data-difficulty="?" class="button-choix">?</button>
        <button onclick="setDifficulty(this)" data-difficulty="1" class="button-choix">1</button>
        <button onclick="setDifficulty(this)" data-difficulty="3" class="button-choix">3</button>
        <button onclick="setDifficulty(this)" data-difficulty="5" class="button-choix">5</button>
        <button onclick="setDifficulty(this)" data-difficulty="10" class="button-choix">10</button>
        <button onclick="setDifficulty(this)" data-difficulty="15" class="button-choix">15</button>
        <button onclick="setDifficulty(this)" data-difficulty="25" class="button-choix">25</button>
        <button onclick="setDifficulty(this)" data-difficulty="999" class="button-choix">999</button>
    </div>

    <span id="char-count">Poster un commentaire (255 caractères restants)</span>
    <br>
    <form action="../process/pokerplanning_process.php" method="POST">
        <input type="hidden" name="userId" value="<?php echo isset($_SESSION['IdUser']) ? htmlspecialchars($_SESSION['IdUser']) : ''; ?>">
        <input type="hidden" name="IdT" value="<?php echo isset($currentTask["IdT"]) ? htmlspecialchars($currentTask["IdT"]) : '?'; ?>">
        <textarea id="comment" name="commentContent" maxlength="255" oninput="adjustTextAreaSize(this), ajustRemainChar(this)"></textarea>
        <input type="hidden" name="difficulty" value="<?php echo isset($difficulty) ? htmlspecialchars($difficulty) : '?'; ?>">
        <input type="submit" class="next-button" value="Suivant">
    </form>

    <a href="./planning_poker_resume.php">Planning formulaire resume </a>

    <script src="../scripts/scripts.js"></script>
</body>

</html>