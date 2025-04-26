<?php
require_once __DIR__ . '/OffresModel.php';

class WishlistModel {
    private $offresModel;
    private $pdo;

    public function __construct(PDO $pdo) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
        
        $this->pdo = $pdo;
        $this->offresModel = new OffresModel($pdo);
    }

    // AJOUTEZ CETTE MÉTHODE MANQUANTE
    public function addToWishlist(int $offreId): bool {
        // Vérifie si l'offre existe
        if (!$this->offresModel->getOffreById($offreId)) {
            return false;
        }
        
        // Évite les doublons
        if (!in_array($offreId, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $offreId;
            return true;
        }
        
        return false;
    }

    public function getWishlist() {
        $fullWishlist = [];
        foreach ($_SESSION['wishlist'] as $offreId) {
            if ($offre = $this->offresModel->getOffreById($offreId)) {
                $fullWishlist[] = $offre;
            }
        }
        return $fullWishlist;
    }

    // Ajoutez aussi cette méthode pour la suppression
    public function removeFromWishlist(int $offreId): bool {
        if (($key = array_search($offreId, $_SESSION['wishlist'])) !== false) {
            unset($_SESSION['wishlist'][$key]);
            return true;
        }
        return false;
    }
}