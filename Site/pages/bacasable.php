<?php
include "../includes/connexionBDD.php";
include "../includes/function.php";

session_start();

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

    $Idees = "SELECT i.*, p.NomP, u.NomU, u.PrenomU
               FROM idees_bac_a_sable i
               JOIN projets p ON p.IdP = i.IdP
               JOIN utilisateurs u ON i.IdU = u.IdU
               WHERE i.IdP IN ($projectIdsPlaceholder)";
    $stmtIdeas = $bdd->prepare($Idees);
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
    <table>
        <thead>
        <tr>
            <th>Nom de l'équipe</th>
            <th>Description de l'idée</th>
            <th>Auteur</th>

        </tr>
        </thead>
        <tbody>
        <?php foreach ($ideas as $idea): ?>
            <tr>
                <td><?= htmlspecialchars($idea['NomP']) ?></td>
                <td><?= htmlspecialchars($idea['desc_Idee_bas']) ?></td>
                <td><?= htmlspecialchars($idea['PrenomU'] . " " . $idea['NomU']) ?></td>
                <td>
                    <?php
                    $isProductOwner = false;
                    foreach ($projects as $project) {
                        if ($project['IdP'] == $idea['IdP'] && $project['IdR'] == getIdRole("Product Owner")) {
                            $isProductOwner = true;
                            break;
                        }
                    }
                    if ($isProductOwner): ?>
                        <th>Actions</th>
                        <form method="post" action="../process/delete_idee.php">
                            <input type="hidden" name="idea_id" value="<?= $idea['Id_Idee_bas'] ?>">
                            <button type="submit" name="delete">Supprimer</button>
                        </form>
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
