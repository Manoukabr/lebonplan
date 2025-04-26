<?php
declare(strict_types=1);

// Point d'entrée principal de l'application

// ==================================================
// 1. Configuration de l'environnement
// ==================================================
const APP_ENV_DEV = 'development';
const APP_ENV_PROD = 'production';

$environment = getenv('APP_ENV') ?: APP_ENV_DEV;

// Configuration des erreurs
if ($environment === APP_ENV_DEV) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
    ini_set('error_log', __DIR__.'/logs/php_errors.log');
}

// ==================================================
// 2. Fonctions utilitaires
// ==================================================
function loadController(string $controllerName): void {
    $controllerFile = __DIR__.'/controllers/'.$controllerName.'.php';
    if (!file_exists($controllerFile)) {
        throw new RuntimeException("Contrôleur $controllerName introuvable");
    }
    require_once $controllerFile;
    if (!class_exists($controllerName)) {
        throw new RuntimeException("Classe $controllerName non trouvée");
    }
}

function sanitizeInput(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// ==================================================
// 3. Gestion du routing
// ==================================================
try {
    $action = sanitizeInput($_GET['action'] ?? 'home');
    $method = sanitizeInput($_GET['method'] ?? 'index');

    if ($action === 'wishlist') {
        $controllerClass = 'WishlistController';
        // Méthode par défaut pour wishlist
        $method = ($method === 'index') ? 'show' : $method;
    } elseif ($action === 'postuler') {
        $controllerClass = 'PostulerController';
        $method = 'postuler';
    } else {
        $controllerClass = ucfirst($action).'Controller';
    }

    loadController($controllerClass);

    $controller = new $controllerClass();

    if (!method_exists($controller, $method)) {
        throw new RuntimeException("Méthode $method non disponible dans le contrôleur $controllerClass");
    }

    $controller->$method();

} catch (Throwable $e) {
    http_response_code(500);
    if ($environment === APP_ENV_DEV) {
        echo "<h1>Erreur d'application</h1>";
        echo "<p><strong>Message :</strong> ".htmlspecialchars($e->getMessage())."</p>";
        echo "<pre>".htmlspecialchars($e->getTraceAsString())."</pre>";
    } else {
        error_log("Erreur : ".$e->getMessage());
        require __DIR__.'/views/errors/500.html';
    }
    exit;
}
