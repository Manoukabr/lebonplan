<?php
require_once __DIR__ . '/../models/WishlistModel.php';
require_once __DIR__ . '/../models/OffresModel.php';
require_once __DIR__ . '/../models/Database.php';

class WishlistController {
    private $wishlistModel;
    private $offresModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialise la connexion PDO
        $pdo = Database::getInstance();
        
        // Initialise les modèles avec la connexion PDO
        $this->wishlistModel = new WishlistModel($pdo);
        $this->offresModel = new OffresModel($pdo);
    }

    // Affiche la wishlist avec les détails complets des offres
    public function show() {
        $wishlistItems = $this->wishlistModel->getWishlist();
        require __DIR__ . '/../views/wishlist.php';
    }

    // Ajoute un élément à la wishlist
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Méthode non autorisée');
        }

        $offre_id = filter_input(INPUT_POST, 'offre_id', FILTER_VALIDATE_INT);
        
        if (!$offre_id) {
            $this->redirectWithError('ID d\'offre invalide');
        }

        try {
            if ($this->wishlistModel->addToWishlist($offre_id)) {
                $_SESSION['flash_message'] = 'Offre ajoutée à votre wishlist';
            } else {
                $_SESSION['flash_error'] = 'Cette offre est déjà dans votre wishlist';
            }
            
            $this->redirectBack();
        } catch (Exception $e) {
            error_log('Erreur Wishlist: ' . $e->getMessage());
            $this->redirectWithError('Une erreur est survenue');
        }
    }

    // Supprime un élément de la wishlist
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirectWithError('Méthode non autorisée');
        }

        $offre_id = filter_input(INPUT_POST, 'offre_id', FILTER_VALIDATE_INT);
        
        if (!$offre_id) {
            $this->redirectWithError('ID d\'offre invalide');
        }

        try {
            if ($this->wishlistModel->removeFromWishlist($offre_id)) {
                $_SESSION['flash_message'] = 'Offre retirée de votre wishlist';
            } else {
                $_SESSION['flash_error'] = 'Cette offre n\'était pas dans votre wishlist';
            }
            
            $this->redirectBack();
        } catch (Exception $e) {
            error_log('Erreur suppression Wishlist: ' . $e->getMessage());
            $this->redirectWithError('Erreur lors de la suppression');
        }
    }

    // Méthodes utilitaires
    private function redirectBack() {
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? 'index.php?action=wishlist&method=show'));
        exit;
    }

    private function redirectWithError($message) {
        $_SESSION['flash_error'] = $message;
        $this->redirectBack();
    }
}