<?php
include_once 'function.php';

  if ($_SESSION['is_logged_in']){
    $user_id = $_SESSION['user_id'];

    $projets = getProjectsWithScrumMaster();
    if (!$projets) {
      $message = "Aucun projet trouvé pour cet utilisateur.";
    }
  }
?>

<link href="../styles/style.css" rel="stylesheet">
<aside>
    <ul id="projects-list">
        <li><a href="/Ftat/Site/index.php">Retour à la vue d'accueil</a></li>
        <?php if (isset($projets) && $projets): ?>
            <?php foreach ($projets as $projet): ?>
                <li class="project-item">
                    <div class="project-header">
                        <a href="/Ftat/Site/pages/projet.php?id=<?php echo $projet['IdP']; ?>">
                            <?php echo $projet['NomP']; ?>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>
                <?php echo isset($message) ? $message : "Aucun projet disponible."; ?>
            </li>
        <?php endif; ?>
    </ul>
</aside>

<script>
    // Sélectionner tous les boutons de bascule et ajouter un event listener
    document.querySelectorAll('.toggle-project-details').forEach(button => {
        button.addEventListener('click', () => {
            const projectDetails = button.parentElement.nextElementSibling;

            // Bascule la visibilité de la liste de détails
            if (projectDetails.style.display === 'block') {
                projectDetails.style.display = 'none';
                button.textContent = '⯆';  // Flèche vers le bas
            } else {
                projectDetails.style.display = 'block';
                button.textContent = '⯅';  // Flèche vers le haut
            }
        });
    });
</script>