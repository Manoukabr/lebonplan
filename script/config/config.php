<?php
class Database {
    private static $instance;

    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dbConfig = [
                'host' => 'localhost',
                'port' => '3306',  // Ajout explicite du port
                'dbname' => 'offres_db',
                'username' => 'root',
                'password' => 'root',  // À adapter selon votre configuration
                'options' => [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false
                ]
            ];

            try {
                self::$instance = new PDO(
                    "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset=utf8mb4",
                    $dbConfig['username'],
                    $dbConfig['password'],
                    $dbConfig['options']
                );

                // Test immédiat de la connexion
                self::$instance->query("SELECT 1")->fetch();
            } catch (PDOException $e) {
                error_log("[DB ERROR] " . $e->getMessage());
                throw new RuntimeException("Database connection failed. Please try again later.");
            }
        }
        return self::$instance;
    }
}