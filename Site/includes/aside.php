<?php

include_once 'function.php';

  if ($_SESSION['is_logged_in']){
    $user_id = $_SESSION['user_id'];

    $projets = getProjetsFromUser($user_id);
    if (!$projets) {
      $message = "Aucun projet trouvé pour cet utilisateur.";
    }
  }
?>

<aside>
  <ul>
    <?php if (isset($projets) && $projets): ?>
      <?php foreach ($projets as $projet): ?>
        <li>
          <a href="pages/projet.php?id=<?php echo $projet['IdP']; ?>">
            <?php echo $projet['NomP']; ?>
          </a>
        </li>
      <?php endforeach; ?>
    <?php else: ?>
      <li>
        <?php echo isset($message) ? $message : "Aucun projet disponible."; ?>
      </li>
    <?php endif; ?>
  </ul>
</aside>
