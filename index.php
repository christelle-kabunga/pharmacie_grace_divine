<?php
session_start();
date_default_timezone_set('Africa/Kinshasa');

require_once 'config/Database.php';

// Autoloader
spl_autoload_register(function ($class) {
    $paths = ['controllers/', 'models/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Permissions centralisées via le modèle `User` (récupérées depuis la table `utilisateurs` via le rôle)
// Le tableau spécifique par rôle est maintenant défini dans `models/User.php`.
$userModel = new User();

// ============================================
// ROUTING
// ============================================
$page = isset($_GET['page']) ? trim($_GET['page']) : 'dashboard';
$action = isset($_GET['action']) ? trim($_GET['action']) : 'index';

$page = trim($page, '/');
$pageParts = explode('/', $page);
$mainPage = $pageParts[0];

// Authentification
$publicPages = ['auth'];

if (!isset($_SESSION['user_id']) && !in_array($mainPage, $publicPages)) {
    header('Location: ?page=auth&action=login');
    exit;
}

// Vérification permissions (exclure les pages publiques comme 'auth')
if (isset($_SESSION['user_id'])) {
    // Ne pas appliquer les vérifications de permissions aux pages publiques
    if (!in_array($mainPage, $publicPages)) {
        $userRole = $_SESSION['user_role'] ?? 'vendeur';
        $perms = $userModel->getPermissions($userRole);

        if (!isset($perms[$mainPage])) {
            $_SESSION['error'] = 'Module non accessible pour votre rôle';
            header('Location: ?page=dashboard');
            exit;
        }

        if (!$perms[$mainPage]['view']) {
            $_SESSION['error'] = 'Accès refusé à ce module';
            header('Location: ?page=dashboard');
            exit;
        }

        $actionType = 'view';
        if (in_array($action, ['nouveau', 'enregistrer', 'create'])) {
            $actionType = 'create';
        } elseif (in_array($action, ['edit', 'update'])) {
            $actionType = 'edit';
        } elseif (in_array($action, ['delete', 'supprimer', 'annuler', 'toggleStatut'])) {
            $actionType = 'delete';
        }

        if ($actionType !== 'view' && !($perms[$mainPage][$actionType] ?? false)) {
            $_SESSION['error'] = 'Action non autorisée';
            header('Location: ?page=' . $mainPage);
            exit;
        }

        $_SESSION['user_permissions'] = $perms;
    }
}

// Dispatcher
$controllerName = ucfirst($mainPage) . 'Controller';

if (!file_exists("controllers/$controllerName.php")) {
    if ($mainPage === '') {
        header('Location: ?page=dashboard');
        exit;
    }
    die("ERREUR: Fichier controllers/$controllerName.php manquant");
}

if (!class_exists($controllerName)) {
    die("ERREUR: Classe $controllerName non définie");
}

$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    die("ERREUR: Méthode '$action' inexistante");
}

$method = new ReflectionMethod($controller, $action);
$args = [];
foreach ($method->getParameters() as $param) {
    $name = $param->getName();
    if (isset($_GET[$name])) {
        $args[] = $_GET[$name];
    } elseif ($param->isDefaultValueAvailable()) {
        $args[] = $param->getDefaultValue();
    } else {
        $args[] = null;
    }
}

$controller->$action(...$args);
?>