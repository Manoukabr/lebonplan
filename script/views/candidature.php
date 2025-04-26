<?php
// Configuration de la base de données
require_once __DIR__.'/../models/Database.php';

try {
    // Connexion à la base de données
    $pdo = Database::getInstance();

    // Récupération de l'ID de l'offre
    $offreId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

    if ($offreId > 0) {
        // Récupération des informations de l'offre
        $stmt = $pdo->prepare("SELECT * FROM offres WHERE id = :id");
        $stmt->bindValue(':id', $offreId, PDO::PARAM_INT);
        $stmt->execute();
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$offre) {
            die("Offre non trouvée");
        }
    } else {
        die("ID d'offre invalide");
    }
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature pour <?= htmlspecialchars($offre['titre']) ?></title>
    <link rel="stylesheet" href="/SCRIPT/public/partials/css/et.css">
</head>

<body>
    <?php require __DIR__ . '/../public/partials/header.php'; ?>

    <main>
        <div class="container">
            <h1>Candidature pour le stage : <?= htmlspecialchars($offre['titre']) ?></h1>

            <div class="offre-details">
                <p><strong>Entreprise :</strong> <?= htmlspecialchars($offre['entreprise']) ?></p>
                <p><strong>Description :</strong> <?= nl2br(htmlspecialchars($offre['description'])) ?></p>
                <p><strong>Localisation :</strong> <?= htmlspecialchars($offre['lieu']) ?></p>
                <p><strong>Date de publication :</strong> <?= date('d/m/Y', strtotime($offre['date_publication'])) ?></p>
                <p><strong>Type de contrat :</strong> <?= htmlspecialchars($offre['type_contrat']) ?></p>
                <p><strong>Domaine :</strong> <?= htmlspecialchars($offre['domaine']) ?></p>
            </div>

            <h2>Formulaire de candidature</h2>
            <form action="traiter_candidature.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="offre_id" value="<?= $offre['id'] ?>">

                <label for="nom">Nom complet :</label>
                <input type="text" id="nom" name="nom" required>

                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>

                <label for="cv">Télécharger votre CV :</label>
                <input type="file" id="cv" name="cv" accept=".pdf, .docx, .doc" required>

                <label for="message">Lettre de motivation :</label>
                <textarea id="message" name="message" rows="5" required></textarea>

                <button type="submit" class="btn-postuler">Envoyer ma candidature</button>
            </form>
        </div>
    </main>

    <?php require __DIR__ . '/../public/partials/footer.php'; ?>
</body>
</html>
