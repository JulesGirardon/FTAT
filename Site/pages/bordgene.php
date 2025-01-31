<?php
// Inclure le fichier de connexion à la base de données
include_once "../includes/connexionBDD.php";

// Démarrer la session pour vérifier les connexions
session_start();

if(isset($bdd)){
    $stmt_projets = $bdd->prepare("SELECT p.NomP, COUNT(t.IdT) AS NombreTaches, SUM(CASE WHEN et.Etat = 'Terminé et TestUnitaire réalisé' THEN 1 ELSE 0 END) AS TachesTerminees
    FROM projets AS p
    LEFT JOIN taches t ON p.IdP = t.IdP
    LEFT JOIN sprintbacklog sb ON t.IdT = sb.IdT
    LEFT JOIN etatstaches et ON sb.IdEtat = et.IdEtat
    GROUP BY p.IdP, p.NomP");
    $stmt_projets->execute();
    $projets = $stmt_projets->fetchAll(PDO::FETCH_ASSOC);
}

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
                    <td><?php echo htmlspecialchars($projet['NomP']); ?></td>
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