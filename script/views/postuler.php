<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Postuler à l'offre - <?= htmlspecialchars($offre['titre'] ?? 'Titre non disponible') ?></title>
    <link rel="stylesheet" href="/SCRIPT/public/partials/css/donc.css">
</head>

<body>
    <?php require __DIR__ . '/../public/partials/header.php'; ?>

    <main>
        <h3 class="application-title">Postuler à une offre</h3>
        <h6 class="application-instructions">Vous pouvez téléphoner directement à une offre de stage qui a été déposée par l'entreprise. Soyez le plus précis possible dans vos réponses!</h6>
        <h4 class="offer-title"><?= htmlspecialchars($offre['titre'] ?? 'Titre non disponible') ?></h4>
        <h6 class="offer-details">
            <?= htmlspecialchars($offre['entreprise'] ?? 'Entreprise non précisée') ?> |
            <?= htmlspecialchars($offre['lieu'] ?? 'Lieu non précisé') ?> |
            Publié le <?= date('d/m/Y', strtotime($offre['date_publication'] ?? 'now')) ?> |
            Ref <?= $offre['reference'] ?? 'N/A' ?>
        </h6>

        <h5 class="offer-summary-title">Résumé de l'offre</h5>
        <div class="offer-meta">
            <div class="offer-duration"><?= htmlspecialchars($offre['duree'] ?? 'Durée non précisée') ?></div>
            <div class="offer-level"><?= htmlspecialchars($offre['niveau'] ?? 'Niveau inconnu') ?></div>
            <div class="offer-domain"><?= htmlspecialchars($offre['domaine'] ?? 'Non précisé') ?></div>
            <div class="offer-experience"><?= htmlspecialchars($offre['experience'] ?? 'Pas d’expérience requise') ?></div>
        </div>

        <form class="application-form" action="" method="post" enctype="multipart/form-data">
            <h4 class="form-title">Envoyez votre candidature dès maintenant !</h4>

            <div class="form-group">
                <label for="civilite">CIVILITÉ</label>
                <div class="form-input">
                    <select name="civilite" id="civilite">
                        <option value="Masculin">Masculin</option>
                        <option value="Féminin">Féminin</option>
                    </select>
                </div>
            </div>

            <br>
            <div class="form-group">
                <label for="nom">NOM</label><br>
                <input type="text" id="nom" name="nom" required>
            </div>

            <br>
            <div class="form-group">
                <label for="prenom">PRÉNOM</label><br>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <br>
            <div class="form-group">
                <label for="email">EMAIL</label><br>
                <input type="email" id="email" name="email" required>
            </div>

            <br>
            <div class="form-group">
                <label class="form-label">À PROPOS DE VOUS</label><br>
                <input type="checkbox" name="permis_B" id="permis_B"> <label for="permis_B">Permis B</label><br>
                <input type="checkbox" name="vehicule" id="vehicule"> <label for="vehicule">Véhiculé</label><br>
                <input type="checkbox" name="certification" id="certification"> <label for="certification">Certification (Microsoft, Cisco...)</label>
            </div>

            <br>
            <div class="form-group">
                <label>JE SUIS MAJEUR</label><br>
                <input type="radio" name="majeur" value="oui" id="majeur_oui"> <label for="majeur_oui">Oui</label>
                <input type="radio" name="majeur" value="non" id="majeur_non"> <label for="majeur_non">Non</label>
            </div>

            <br>
            <div class="form-group">
                <label for="message">VOTRE MESSAGE AU RECRUTEUR</label><br>
                <textarea name="message" id="message" rows="5"></textarea>
            </div>

            <br>
            <div class="form-group">
                <label for="cv">CV</label>
                <input type="file" id="cv" name="cv" accept=".pdf, .doc, .jpeg, .png" required>
                <span class="file-hint">Poids max 2Mo - Formats : pdf, doc, jpeg, png</span>
            </div>

            <br>
            <input type="submit" class="submit-btn" value="ENVOYER">
            <input type="reset" class="reset-btn" value="RÉINITIALISER">
        </form>
    </main>

    <?php require __DIR__ . '/../public/partials/footer.php'; ?>
    <script src="/SCRIPT/public/partials/js/document.js"></script>
</body>
</html>
