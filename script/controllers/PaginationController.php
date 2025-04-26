<?php
class PaginationController {
    private $itemsPerPage = 5;
    private $entreprises = [
        ['nom' => 'Entreprise 1', 'description' => 'Spécialisée en technologie'],
        ['nom' => 'Entreprise 2', 'description' => 'Services financiers'],
        ['nom' => 'Entreprise 3', 'description' => 'Agroalimentaire'],
        ['nom' => 'Entreprise 4', 'description' => 'Construction'],
        ['nom' => 'Entreprise 5', 'description' => 'Mode et textile'],
        ['nom' => 'Entreprise 6', 'description' => 'Logistique'],
        ['nom' => 'Entreprise 7', 'description' => 'Santé et bien-être'],
        ['nom' => 'Entreprise 8', 'description' => 'Éducation'],
        ['nom' => 'Entreprise 9', 'description' => 'Énergie verte'],
        ['nom' => 'Entreprise 10', 'description' => 'Tourisme']
    ];

    public function index() {
        // 1. Valider le numéro de page
        $currentPage = $this->validatePage($_GET['page'] ?? 1);

        // 2. Préparer les données pour la vue
        $data = [
            'entreprises' => $this->getPaginatedItems($currentPage),
            'currentPage' => $currentPage,
            'totalPages' => ceil(count($this->entreprises) / $this->itemsPerPage),
            'baseUrl' => '?action=pagination&page='
        ];

        // 3. Charger la vue
        require __DIR__ . '/../views/pagination.php';
    }

    private function validatePage($page): int {
        $page = (int)$page;
        return ($page < 1) ? 1 : $page;
    }

    private function getPaginatedItems(int $page): array {
        $offset = ($page - 1) * $this->itemsPerPage;
        return array_slice($this->entreprises, $offset, $this->itemsPerPage);
    }
}