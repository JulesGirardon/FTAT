<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "Veuillez vous connecter pour ajouter une idée.";
    exit;
}

// Inclure le fichier de connexion à la base de données
include_once "../includes/connexionBDD.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $description = $_POST['desc_Idee_bas'];
    $user_id = $_SESSION['user_id'];
    $equipe_id = $_POST['IdEq']; // Assurez-vous que cet ID est récupéré d'une source valide

    // Préparer l'insertion de l'idée dans la base de données
    $sql = "INSERT INTO idees_bac_a_sable (desc_Idee_bas, IdU, IdEq) VALUES (:description, :user_id, :equipe_id)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(['description' => $description, 'user_id' => $user_id, 'equipe_id' => $equipe_id]);

    echo "L'idée a été ajoutée avec succès.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Idée</title>
</head>
<body>

<div>
    <h2>Ajouter une Idée au Bac à Sable</h2>
    <form method="POST" action="">
        <div class="Desc">
            <label for="desc_Idee_bas" class="form-label">Description de l'idée</label>
            <textarea class="form-control" id="desc_Idee_bas" name="desc_Idee_bas" required></textarea>
        </div>
        <div class="numEquipe">
            <label for="IdEq" class="form-label">ID de l'Équipe</label>
            <input type="number" class="form-control" id="IdEq" name="IdEq" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter l'idée</button>
    </form>
</div>

</body>
</html>

<?php
// Fermer la connexion à la base de données
$conn = null;
?>

