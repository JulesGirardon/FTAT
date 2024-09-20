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
                        <th>ID</th>
                        <th>Titre</th>
                        <th>User Story</th>
                        <th>ID Équipement</th>
                        <th>ID Priorité</th>
                        <th>Cout</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['IdT']); ?></td>
                            <td><?php echo htmlspecialchars($row['TitreT']); ?></td>
                            <td><?php echo htmlspecialchars($row['UserStoryT']); ?></td>
                            <td><?php echo htmlspecialchars($row['IdEq']); ?></td>
                            <td><?php echo htmlspecialchars($row['IdPriorite']); ?></td>
                            <td>
                                <button onclick="handleButtonClick(this)" class="dropbtn"><?php echo htmlspecialchars($row['CoutT']); ?></button>
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
        <?php
        } else {
            echo "Aucune tâche trouvée.";
        }
        ?>
        <a href="./planning_poker_resume.php">Planning formulaire resume </a>
    </main>

    <script>
    function handleButtonClick(button) {
        const taskId = button.closest('tr').querySelector('td:first-child').textContent;
        const dropdownId = `myDropdown-${taskId}`;
        const dropdown = document.getElementById(dropdownId);

        if (dropdown) {
            dropdown.classList.toggle("show");
        }
    }

    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn') && !event.target.closest('.dropdown-content')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }
</script>
</body>

</html>