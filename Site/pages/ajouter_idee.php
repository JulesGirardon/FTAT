<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Idée</title>
</head>
<body>

<div>
    <h2>Ajouter une Idée au Bac à Sable</h2>
    <form method="POST" action="../process/ajouter_idee_process.php">
        <div class="Desc">
            <label for="desc_Idee_bas" class="form-label">Description de l'idée</label>
            <textarea class="form-control" id="desc_Idee_bas" name="desc_Idee_bas" required></textarea>
        </div>
        <div class="numEquipe">
            <label for="IdP" class="form-label">ID de Projet</label>
            <input type="number" class="form-control" id="IdP" name="IdP" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter l'idée</button>
    </form>
</div>

</body>
</html>
