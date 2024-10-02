<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header></header>

    <main>
        <?php
        $_SESSION["currPrj"] = 1;
        include_once "../process/function_tasks.php";
        $results = displayAllVotes($_SESSION["currPrj"]);
        $difficulties = displayAllDifficulties();
        $taskTitles = array();
        foreach ($results as $row) {
            $taskTitles[$row['IdT']] = $row['TitreT'];
        }

        if ($results && count($results) > 0): ?>
            <div class="table-container">
                <table border="1">
                    
                    <tbody>
                        <?php usort($results, function ($a, $b) {
                            return $a['IdT'] <=> $b['IdT'];
                        }); ?>
                        <?php $currentTask = null;
                        foreach ($results as $row): ?>
                            <?php if ($row['IdT'] != $currentTask): ?>
                                <tr class="task-header">
                                    <td colspan="5"> <?= $taskTitles[$row['IdT']] ?>:
                                        <select name="difficulty" id="id_task" required>
                                            <option value="">-- Sélectionner une difficulté --</option>
                                            <?php foreach ($difficulties as $difficulty): ?>
                                                <option value="<?php echo $difficulty['value']; ?>"><?php echo $difficulty['value']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <tr>
                                <td><?= $row['IdU'] ?></td>
                                <td>cout tâche <?= $row['CoutMT'] ?></td>
                                <td><?= $row['Commentaire'] ?></td>
                            </tr>

                            <?php $currentTask = $row['IdT']; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: echo "<p class='no-results'>Aucune tâche trouvée.</p>";
        endif; ?>

        <a href="./planning_poker.php" class="btn">Planning formulaire</a>
    </main>

    <script src="../scripts/scripts.js"></script>
</body>

</html>