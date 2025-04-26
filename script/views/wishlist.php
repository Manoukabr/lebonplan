<?php
// Pas de session_start ici, la session est déjà démarrée dans le contrôleur
// Le contrôleur t’a fourni un tableau $wishlist contenant les IDs d’offres

?><!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ma Wishlist</title>
  <link rel="stylesheet" href="/script/public/partials/css/wish.css">
</head>
<body>
  <?php require __DIR__ . '/../public/partials/header.php'; ?>

  <main class="container">
    <h1>Ma Wishlist</h1>

    <?php if (empty($wishlistItems)): ?>
      <p>Aucune offre dans votre wishlist.</p>
    <?php else: ?>
      <ul>
        <?php foreach ($wishlistItems as $offreId): ?>
          <li>
            Offre n°<?= htmlspecialchars($offreId) ?>
            <!-- Tu peux remplacer par un SELECT pour récupérer plus de détails si nécessaire, comme le titre de l'offre -->
            <form action="/script/wishlist/remove" method="POST" style="display:inline;">
              <input type="hidden" name="offre_id" value="<?= htmlspecialchars($offreId) ?>">
              <button type="submit">Retirer</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <p><a href="/script/offres">← Retour aux offres</a></p>
  </main>

  <?php require __DIR__ . '/../public/partials/footer.php'; ?>
</body>
</html>
