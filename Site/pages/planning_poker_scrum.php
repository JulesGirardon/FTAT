<?php
include_once "../includes/function.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['NextPage'])) {
    $_SESSION['NextPage'] = 0;
}
if (!isset($_SESSION['SessionPage'])) {
    $_SESSION['SessionPage'] = 0;
}


$results = displayAllVotes($_SESSION["currPrj"]);

$difficulties = displayAllDifficulties();

$currentTask = $results[$_SESSION["NextPage"]];

function getMostVotedChoice($row)
{
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

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
        <?php
            if ($_SESSION["NextPage"] >= count($results)) {
                $_SESSION['NextPage'] = 0;
                $_SESSION['SessionPage'] = 0;
                header("Location:../index.php?=" . $_SESSION['currPrj']);
                exit();
            }
            ?>
    <div class="projet">
        <table border="1">
            <thead>
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
                    <th>Vote du Scrum Master</th>
                </tr>
            </thead>
            <form action="../process/pokerplanning_process_scrum.php" method="POST">
                <tbody>
                    <tr>
                        <!-- Affichage des données de la tâche actuelle -->
                        <td><?php echo $currentTask['IdT']; ?></td>
                        <td><?php echo $currentTask['Occurrence_']; ?></td>
                        <td><?php echo $currentTask['Occurrence_1']; ?></td>
                        <td><?php echo $currentTask['Occurrence_3']; ?></td>
                        <td><?php echo $currentTask['Occurrence_5']; ?></td>
                        <td><?php echo $currentTask['Occurrence_10']; ?></td>
                        <td><?php echo $currentTask['Occurrence_15']; ?></td>
                        <td><?php echo $currentTask['Occurrence_25']; ?></td>
                        <td><?php echo $currentTask['Occurrence_999']; ?></td>
                        <td><?php echo getMostVotedChoice($currentTask); ?></td>
                        <td>
                            <select name="difficulty" id="id_task" required>
                                <option value="">-- Sélectionner une difficulté --</option>
                                <?php foreach ($difficulties as $difficulty): ?>
                                    <option value="<?php echo $difficulty['value']; ?>"><?php echo $difficulty['value']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                </tbody>
                <?php $comments = displayAllComments($currentTask['IdT']); ?>
                <input type="hidden" name="IdT" value="<?php echo $currentTask['IdT']; ?>">
                <input type="submit" class="next-button" value="Suivant">
            </form>
        </table>
        <div class="projet-planning-poker-commentaire">
            <h2>Commentaires</h2>
            <?php $comments = displayAllComments($currentTask['IdT']);

            foreach ($comments as $comment) {
                $text_com = $comment['Commentaire'];
                echo "<br><p>$text_com</p><br>";
            } ?>
        </div>
    </div>

    <script src="../scripts/scripts.js"></script>
</body>

</html>