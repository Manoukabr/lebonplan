<?php
// Configuration de la base de données
require_once __DIR__.'/../models/Database.php';

try {
    // Connexion à la base de données
    $pdo = Database::getInstance();

    // Nombre d'éléments par page
    $limit = 10; // Afficher 10 entreprises par page

    // Récupération du numéro de page
    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    // Calculer l'offset
    $offset = ($page - 1) * $limit;

    // Requête pour récupérer les entreprises
    $stmt = $pdo->prepare("SELECT * FROM entreprises LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $entreprises = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Comptage total des entreprises
    $totalEntreprises = $pdo->query("SELECT COUNT(*) FROM entreprises")->fetchColumn();
    $totalPages = ceil($totalEntreprises / $limit);

} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagination des Entreprises</title>
    <link rel="stylesheet" href="/SCRIPT/public/partials/css/alors.css">
</head>

<body>
    <?php
        // Chemin relatif correct (remonte d'un niveau, puis va dans public/partials/)
        require __DIR__ . '/../public/partials/header.php';
    ?>

    <main>
        <div class="container">
            <h1 class="page-title">Liste des Entreprises</h1>

            <?php foreach ($entreprises as $entreprise): ?>
                <div class="entreprise">
                    <h3><?php echo htmlspecialchars($entreprise['nom']); ?></h3>
                    <p><?php echo htmlspecialchars($entreprise['description']); ?></p>
                </div>
            <?php endforeach; ?>

            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="/script/index.php?action=pagination&page=1">&laquo; Première</a>
                    <a href="/script/index.php?action=pagination&page=<?= $page - 1 ?>">Précédent</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="/script/index.php?action=pagination&page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="/script/index.php?action=pagination&page=<?= $page + 1 ?>">Suivant</a>
                    <a href="/script/index.php?action=pagination&page=<?= $totalPages ?>">Dernière &raquo;</a>
                <?php endif; ?>
            </div>

        </div>
    </main> 

    <?php
        require __DIR__ . '/../public/partials/footer.php';
    ?>
</body>
</html>
