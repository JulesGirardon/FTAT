<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
</head>

<body>
    <header>
    </header>
    <main>
        <?php
        $_SESSION["currPrj"]=1;
        include_once "../process/function_tasks.php";
        $results = displayAllTasks($_SESSION["currPrj"]);
        if ($results && count($results) > 0) {
            echo "<table border='1'>";
            echo "<tr>
                    <th>IdT</th>
                    <th>TitreT</th>
                    <th>UserStoryT</th>
                    <th>IdEq</th>
                    <th>CoutT</th>
                    <th>IdPriorite</th>
                  </tr>";
            foreach ($results as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['IdT']) . "</td>";
                echo "<td>" . htmlspecialchars($row['TitreT']) . "</td>";
                echo "<td>" . htmlspecialchars($row['UserStoryT']) . "</td>";
                echo "<td>" . htmlspecialchars($row['IdEq']) . "</td>";
                echo "<td>" . htmlspecialchars($row['CoutT']) . "</td>";
                echo "<td>" . htmlspecialchars($row['IdPriorite']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "Aucune tâche trouvée.";
        }
        ?>
    </main>
</body>

</html>