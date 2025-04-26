<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Le titre de ma page</title>
        <link rel="stylesheet" href="/SCRIPT/public/partials/css/donc.css">

    </head>

    <body>
            <?php
            // Chemin relatif correct (remonte d'un niveau, puis va dans public/partials/)
            require __DIR__ . '/../public/partials/header.php';
            ?>
        <main>
            <h3 class="application-title">Postuler à une offre</h3>
            <h6 class="application-instructions">Vous pouvez téléphoner directement à une offre de stage qui a été déposée par l'entreprise. Soyez le plus précis possible dans vos réponses!</h6>
            <h4 class="offer-title">Stage - Administrateur Système et Réseau H/F</h4>
            <h6 class="offer-details">IBM | Pomichef - 44 | Publié le 23/01/2025 | Ref 23XY2-44</h6>
            <h5 class="offer-summary-title">Résumé de l'offre</h5>

            <!-- Métadonnées de l'offre -->
            <div class="offer-meta">
                <div class="offer-duration">3 mois</div>
                <div class="offer-level">Bac+2 Bac+3</div>
                <div class="offer-domain">Système Réseau Cloud</div>
                <div class="offer-experience">Exp. 1 an</div>
            </div>

            <!-- Formulaire de candidature -->
            <form class="application-form">
                <h4 class="form-title">Envoyez votre candidature dès maintenant !</h4>

                <div class="form-group">
                    <label for="civilite">CIVILITE</label>
                    <div class="form-input">
                        <select name="civilite" id="civilite">
                            <option value="Masculin">Masculin</option>
                            <option value="Feminin">Féminin</option>
                        </select>
                    </div>
                </div>

                <br>
                <div class="form-group">
                    <label for="nom">NOM</label><br>
                    <input type="text" id="nom" required>
                </div>

                <br>
                <div class="form-group">
                    <label for="prenom">PRENOM</label><br>
                    <input type="text" id="prenom" required>
                </div>

                <br>
                <div class="form-group">
                    <label for="email">EMAIL</label><br>
                    <input type="email" id="email" required>
                </div>
            
                <br>
                <div class="form-group">
                    <label class="form-label">A PROPOS DE VOUS</label>
                    <br>
                    <input type="checkbox" name="permis_B" id="permis_B"> <label class="checkbox-label" for="permis_B">Permis B</label><br>
                    <input type="checkbox" name="vehicule" id="vehicule"> <label class="checkbox-label" for="vehicule">Véhiculé</label><br>
                    <input type="checkbox" name="certification" id="certification"> <label class="checkbox-label" for="certification">Certification (Microsoft, Cisco...)</label>
                </div>

                <br>
                <div class="form-group">
                    <label class="form-label">JE SUIS MAJEUR</label>
                    <input type="radio" name="majeur" value="oui" id="majeur_oui"> <label for="majeur_oui">Oui</label>
                    <input type="radio" name="majeur" value="non" id="majeur_non"> <label for="majeur_non">Non</label>
                </div>

                <br>
                <div class="form-group">
                    <label for="message">VOTRE MESSAGE AU RECRUTEUR</label><br>
                    <textarea name="message" id="message"></textarea>
                </div>

                <br>
                <div class="form-group">
                    <label for="cv">CV</label>
                    <input type="file" id="cv" name="cv" accept=".pdf, .doc, .jpeg, .png" required>
                    <span class="file-hint">Poids max 2Mo - Formats: pdf, doc, jpeg, png</span>
                </div>
        
                <br>
                <input type="submit" class="submit-btn" value="ENVOYER">
                <input type="reset" class="reset-btn" value="RÉINITIALISER">
        
                <br>
            </form>
        </main>    
        <?php
                require __DIR__ . '/../public/partials/footer.php';
        ?>
        <script src="/SCRIPT/public/partials/js/document.js"></script>
    </body>
</html>