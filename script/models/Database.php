<?php
class Database {
    private static $instance;

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    'mysql:host=localhost;dbname=offres_db;charset=utf8mb4',
                    'root',
                    'root', // Mot de passe vide pour XAMPP
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erreur de connexion: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}