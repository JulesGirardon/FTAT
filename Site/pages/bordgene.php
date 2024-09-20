<?php
// Démarrer la session pour vérifier les connexions
session_start();

// Inclure le fichier de connexion à la base de données
include_once "../include/connexionBDD.php";

$sql_projets = "
    SELECT e.NomEqPrj, COUNT(t.IdT) AS NombreTaches, SUM(CASE WHEN et.Etat = 'Terminé et TestUnitaire réalisé' THEN 1 ELSE 0 END) AS TachesTerminees
    FROM equipesprj e
    LEFT JOIN taches t ON e.IdEq = t.IdEq
    LEFT JOIN sprintbacklog sb ON t.IdT = sb.IdT
    LEFT JOIN etatstaches et ON sb.IdEtat = et.IdEtat
    GROUP BY e.IdEq, e.NomEqPrj
";

$stmt_projets = $conn->prepare($sql_projets);
$stmt_projets->execute();
$projets = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord général</title>
    <link href="" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Tableau de bord général</h2>

        <!-- Section pour afficher la synthèse des projets -->
        <h3>Synthèse des Projets</h3>
        <?php if (count($projets) > 0): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom du Projet</th>
                        <th>Nombre de Tâches</th>
                        <th>Tâches Terminées</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projets as $projet): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($projet['NomEqPrj']); ?></td>
                            <td><?php echo htmlspecialchars($projet['NombreTaches']); ?></td>
                            <td><?php echo htmlspecialchars($projet['TachesTerminees']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">Aucun projet trouvé.</div>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn = null;
?>
