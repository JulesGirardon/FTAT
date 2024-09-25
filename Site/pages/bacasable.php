<?php
include "../includes/connexionBDD.php";

$userId = $_SESSION['user_id'];

$req = "SELECT p.IdP, p.NomP, ru.IdR
          FROM projets p
          JOIN rolesutilisateurprojet ru ON p.IdP = ru.IdP
          WHERE ru.IdU = :userId";

if (isset($bdd)){
    $stmt = $bdd->prepare($req);
    $stmt->execute(['userId' => $userId]);
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $projectIds = array_column($projects, 'IdP');
    if (empty($projectIds)) {
        echo "You are not involved in any projects.";
        exit;
    }
    $projectIdsPlaceholder = implode(',', array_fill(0, count($projectIds), '?'));

    $queryIdeas = "SELECT i.*, e.NomEqPrj, u.NomU, u.PrenomU
               FROM idees_bac_a_sable i
               JOIN equipesprj e ON i.IdEq = e.IdEq
               JOIN utilisateurs u ON i.IdU = u.IdU
               WHERE i.IdEq IN ($projectIdsPlaceholder)";
    $stmtIdeas = $bdd->prepare($queryIdeas);
    $stmtIdeas->execute($projectIds);
    $ideas = $stmtIdeas->fetchAll(PDO::FETCH_ASSOC);

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bac à Sable - Gestion des idées</title>
</head>
<body>
<h1>Bac à Sable - Vos Idées de Projet</h1>

<?php if (!empty($ideas)): ?>
    <table border="1">
        <thead>
        <tr>
            <th>Nom de l'équipe</th>
            <th>Description de l'idée</th>
            <th>Auteur</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($ideas as $idea): ?>
            <tr>
                <td><?= htmlspecialchars($idea['NomEqPrj']) ?></td>
                <td><?= htmlspecialchars($idea['desc_Idee_bas']) ?></td>
                <td><?= htmlspecialchars($idea['PrenomU'] . " " . $idea['NomU']) ?></td>
                <td>
                    <?php
                    // Check if the user is the Product Owner of this project
                    $isProductOwner = false;
                    foreach ($projects as $project) {
                        if ($project['IdP'] == $idea['IdEq'] && $project['IdR'] == 1) { // Assuming 1 is the Product Owner role
                            $isProductOwner = true;
                            break;
                        }
                    }
                    if ($isProductOwner): ?>
                        <form method="post" action="delete_idea.php">
                            <input type="hidden" name="idea_id" value="<?= $idea['Id_Idee_bas'] ?>">
                            <button type="submit" name="delete">Supprimer</button>
                        </form>
                    <?php else: ?>
                        Aucune action possible
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune idée disponible pour vos projets.</p>
<?php endif; ?>
</body>
</html>
