
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - LOMAN's</title>
</head>

<body>
    <header>
        <?php include "header.php"; ?>
    </header>
    <main>
        <?php
        include_once "../process/function_tasks.php";
        $results = displayAllTasks();

        ?>
    </main>
</body>
</html>