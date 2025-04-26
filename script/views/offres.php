<?php
// Configuration de la base de données
require_once __DIR__.'/../models/Database.php';

try {
    // Connexion à la base de données
    $pdo = Database::getInstance();

    // Nombre d'offres par page
    $limit = 10;

    // Récupération du numéro de page
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);

    // Calcul de l'offset
    $offset = ($page - 1) * $limit;

    // Requête pour récupérer les offres
    $stmt = $pdo->prepare("SELECT * FROM offres ORDER BY date_publication DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comptage total
    $totalOffres = $pdo->query("SELECT COUNT(*) FROM offres")->fetchColumn();
    $totalPages = ceil($totalOffres / $limit);

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offres de Stage</title>
    <link rel="stylesheet" href="/SCRIPT/public/partials/css/et.css">
</head>

<body>
    <?php require __DIR__ . '/../public/partials/header.php'; ?>

    <main>
        <div class="container">
            <h1 class="page-title">Offres de Stage Disponibles</h1>

            <?php foreach ($offres as $offre): ?>
                <div class="entreprise">
                    <div class="entreprise-content">
                        <h3><?= htmlspecialchars($offre['titre']) ?></h3>
                        <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre['entreprise']) ?></p>
                        <p><strong>Description :</strong> <?= htmlspecialchars($offre['description']) ?></p>
                        <p><strong>Localisation :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                        <p><strong>Date de publication :</strong> <?= date('d/m/Y', strtotime($offre['date_publication'])) ?></p>
                    </div>
                    <div class="entreprise-btn">
                        <a href="/script/index.php?action=postuler&id=<?= $offre['id'] ?>" class="btn-postuler">Postuler</a>

                        <!-- Ajout à la wishlist avec méthode POST -->
                        <form action="/script/index.php?action=wishlist&method=add" method="POST" style="display:inline;">
                            <input type="hidden" name="offre_id" value="<?= (int)$offre['id'] ?>">
                            <button type="submit" class="btn-wishlist">Ajouter à la wishlist</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="/script/index.php?action=offres&page=1">&laquo; Première</a>
                    <a href="/script/index.php?action=offres&page=<?= $page - 1 ?>">Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/script/index.php?action=offres&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="/script/index.php?action=offres&page=<?= $page + 1 ?>">Suivant</a>
                    <a href="/script/index.php?action=offres&page=<?= $totalPages ?>">Dernière &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php require __DIR__ . '/../public/partials/footer.php'; ?>
</body>
</html>
