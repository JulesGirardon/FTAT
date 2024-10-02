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
        //$results = displayAllTasks($_SESSION["currPrj"]);
        $results = displayAllVotes($_SESSION["currPrj"]);
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
                            <td><?php echo htmlspecialchars($row['CoutT']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php
        } else {
            echo "Aucune tâche trouvée.";
        }
        ?>
        <a href="./planning_poker.php">Planning formulaire</a>
    </main>
    <script src="../scripts/scripts.js"></script>

</body>

</html>