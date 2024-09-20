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

        $itemsPerPage = 1;
        $results = displayAllTasks($_SESSION["currPrj"]);

        if ($results && count($results) > 0) {
            $totalPages = ceil(count($results) / $itemsPerPage);
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

            if ($currentPage < 1 || $currentPage > $totalPages) {
                $currentPage = 1;
            }

            $startIndex = ($currentPage - 1) * $itemsPerPage;
            $limitedResults = array_slice($results, $startIndex, $itemsPerPage);

            if ($limitedResults && count($limitedResults) > 0) {
        ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>User Story</th>
                            <th>Cout</th>
                        </tr>
                    </thead>
                    <tbody id="task-body">
                        <?php foreach ($limitedResults as $index => $row): ?>
                            <tr class="task-row" data-task-id="<?php echo htmlspecialchars($row['IdT']); ?>">
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <nav aria-label="Pagination">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php echo $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

        <?php
            } else {
                echo "Aucune tâche trouvée.";
            }
        }
        ?>
        <button onclick="nextTask()" class="next-button">Suivant</button>

        <a href="./planning_poker_resume.php">Planning formulaire resume </a>
    </main>

    <script src="../scripts/scripts.js"></script>
</body>

</html>