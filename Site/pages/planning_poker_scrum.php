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
        if ($results && count($results) > 0):
        ?>
            <table border="1">
                <tr>
                    <th>IdT</th>
                    <th>?</th>
                    <th>1</th>
                    <th>3</th>
                    <th>5</th>
                    <th>10</th>
                    <th>15</th>
                    <th>25</th>
                    <th>999</th>
                    <th>Choix le plus voté</th>
                </tr>
                <?php
                foreach ($results as $row):
                ?>
                    <tr>
                        <?php foreach ($row as $key => $value): ?>
                            <td><?php echo isset($value) ? $value : ''; ?></td>
                        <?php endforeach; ?>
                        <td><?php echo getMostVotedChoice($row); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: echo "<p class='no-results'>Aucune tâche trouvée.</p>";
        endif;
        ?>

        <a href="./planning_poker.php" class="btn">Planning formulaire</a>
    </main>
    <script src="../scripts/scripts.js"></script>
</body>

</html>
<?php
function getMostVotedChoice($row)
{
    $choices = ['?', '1', '3', '5', '10', '15', '25', '999'];
    $votes = [
        '?'   => $row['Occurrence_'],
        '1'   => $row['Occurrence_1'],
        '3'   => $row['Occurrence_3'],
        '5'   => $row['Occurrence_5'],
        '10'  => $row['Occurrence_10'],
        '15'  => $row['Occurrence_15'],
        '25'  => $row['Occurrence_25'],
        '999' => $row['Occurrence_999']
    ];
    $maxVotes = max($votes);
    $mostVotedChoices = array_keys($votes, $maxVotes);
    return implode(', ', $mostVotedChoices);
}
?>