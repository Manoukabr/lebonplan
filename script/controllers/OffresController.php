<?php

class OffresController {
    private $pdo;
    private $itemsPerPage = 10;

    public function __construct() {
        require_once __DIR__.'/../models/Database.php';
        $this->pdo = Database::getInstance();
    }

    public function index() {
        try {
            $currentPage = $this->getCurrentPage();
            $offset = ($currentPage - 1) * $this->itemsPerPage;
            $offres = $this->getPaginatedOffres($offset);
            $totalOffres = $this->getTotalOffres();
            $totalPages = ceil($totalOffres / $this->itemsPerPage);

            $data = [
                'title' => 'Offres de Stage',
                'offres' => $offres,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'totalOffres' => $totalOffres
            ];

            require __DIR__.'/../views/offres/index.php';

        } catch (PDOException $e) {
            $this->handleError("Erreur de base de données : ".$e->getMessage());
        }
    }

    public function postuler() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id)) {
            echo "ID d'offre invalide.";
            return;
        }

        $offre = $this->getOffreById($id);

        if (!$offre) {
            echo "Offre introuvable.";
            return;
        }

        // Traitement du formulaire de postulation
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $civilite = $_POST['civilite'] ?? '';
            $nom = $_POST['nom'] ?? '';
            $prenom = $_POST['prenom'] ?? '';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';
            $majeur = $_POST['majeur'] ?? '';
            $permis_B = isset($_POST['permis_B']) ? 'oui' : 'non';
            $vehicule = isset($_POST['vehicule']) ? 'oui' : 'non';
            $certification = isset($_POST['certification']) ? 'oui' : 'non';

            // Gestion du fichier CV
            if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
                $cvName = $_FILES['cv']['name'];
                $cvTmp = $_FILES['cv']['tmp_name'];
                $cvSize = $_FILES['cv']['size'];

                // Vérifier la taille du fichier (max 2Mo)
                if ($cvSize <= 2 * 1024 * 1024) {
                    move_uploaded_file($cvTmp, __DIR__ . "/../uploads/" . $cvName);
                    $cvOk = true;
                } else {
                    $cvOk = false;
                    $error = "Le fichier est trop lourd.";
                }
            }

            // Par exemple, envoyer un email ou ajouter en BDD (À faire)
            $success = true; 
            $data = [
                'success' => $success,
                'offre' => $offre
            ];

            // On recharge la vue postuler avec un message de succès
            require __DIR__ . '/../views/offres/postuler.php';
        } else {
            // Affichage du formulaire de postulation
            $data = [
                'offre' => $offre
            ];
            require __DIR__ . '/../views/offres/postuler.php';
        }
    }

    public function ajouterWishlist() {
        $offreId = $_POST['offre_id'] ?? null;

        if (!$offreId || !is_numeric($offreId)) {
            echo "ID d'offre invalide.";
            return;
        }

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            echo "Vous devez être connecté pour ajouter à la wishlist.";
            return;
        }

        // Ajouter l'offre à la wishlist
        $userId = $_SESSION['user_id']; 
        try {
            $stmt = $this->pdo->prepare("INSERT INTO wishlist (user_id, offre_id) VALUES (:user_id, :offre_id)");
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':offre_id', $offreId, PDO::PARAM_INT);
            $stmt->execute();

            echo "L'offre a été ajoutée à votre wishlist.";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout à la wishlist : " . $e->getMessage();
        }
    }

    protected function getOffreById(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT o.*, c.nom AS categorie FROM offres o LEFT JOIN categories c ON o.categorie_id = c.id WHERE o.id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $offre = $stmt->fetch(PDO::FETCH_ASSOC);
        return $offre ?: null;
    }

    protected function getCurrentPage(): int {
        return isset($_GET['page']) && is_numeric($_GET['page']) 
            ? max(1, (int)$_GET['page']) 
            : 1;
    }

    protected function getPaginatedOffres(int $offset): array {
        $stmt = $this->pdo->prepare("
            SELECT o.*, c.nom AS categorie 
            FROM offres o
            LEFT JOIN categories c ON o.categorie_id = c.id
            ORDER BY o.date_publication DESC
            LIMIT :limit OFFSET :offset
        ");
        $stmt->bindValue(':limit', $this->itemsPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getTotalOffres(): int {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM offres");
        return (int)$stmt->fetchColumn();
    }

    protected function handleError(string $message) {
        error_log($message);
        $data = [
            'title' => 'Erreur',
            'errorMessage' => 'Une erreur est survenue'
        ];
        require __DIR__.'/../views/offres.php';
        exit;
    }
}
