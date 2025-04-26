<?php
// controllers/HomeController.php

class HomeController {
    public function index() {
        // 1. Préparer les données pour la vue
        $data = [
            'title' => 'Accueil',
            'welcome_message' => 'Bienvenue sur notre annuaire d\'entreprises'
        ];

        // 2. Charger la vue
        require __DIR__ . '/../views/home.php';
    }
}
