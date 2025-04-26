<?php
class PostulerController {
    private $pdo;

    public function __construct() {
        require_once __DIR__.'/../models/Database.php';
        $this->pdo = Database::getInstance();
    }

    public function postuler() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo "ID d'offre invalide.";
            return;
        }

        // Obtenir les informations de l'offre
        $offre = $this->getOffreById($id);

        if (!$offre) {
            echo "Offre introuvable.";
            return;
        }

        // Afficher la vue de la candidature
        require_once __DIR__ . '/../views/postuler.php';
    }

    protected function getOffreById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT o.* FROM offres o WHERE o.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
        return $offre ?: null;
    }
}
