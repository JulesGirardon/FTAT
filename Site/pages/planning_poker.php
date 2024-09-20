<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
    </header>

    <main>
        <?php
        $_SESSION["currPrj"] = 1;
        include_once "../process/function_tasks.php";
        $results = displayAllTasks($_SESSION["currPrj"]);

        if ($results && count($results) > 0) {
        ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>User Story</th>
                        <th>Cout</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['TitreT']); ?></td>
                            <td><?php echo htmlspecialchars($row['UserStoryT']); ?></td>
                            <td>
                            <button onclick="handleButtonClick(this)" class="dropbtn" data-task-id="<?php echo htmlspecialchars($row['IdT']); ?>"><?php echo htmlspecialchars($row['CoutT']); ?></button>
                            <div id="myDropdown-<?php echo htmlspecialchars($row['IdT']); ?>" class="dropdown-content">
                                    <a href="#home">Clicked</a>
                                    <a href="#about">Clicked</a>
                                    <a href="#contact">Clicked</a>
                                </div>
                            </td>
                            <td><button onclick="nextTask()">Suivant</button></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php
        } else {
            echo "Aucune tâche trouvée.";
        }
        ?>
        <a href="./planning_poker_resume.php">Planning formulaire resume </a>
    </main>

    <script src="../scripts/scripts.js"></script>
</body>
</html>