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

// ============================================
// PERMISSIONS PAR RÔLE (3 utilisateurs)
// ============================================
$permissions = [
    'admin' => [
        'dashboard' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'vente' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'stock' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'medicament' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'categorie' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'facture' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'fournisseur' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'rapport' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'alert' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'personnel' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        'parametre' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
    ],
    'vendeur' => [
        'dashboard' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'vente' => ['view' => true, 'create' => true, 'edit' => false, 'delete' => false],
        'stock' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'medicament' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'categorie' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'facture' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'fournisseur' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
        'rapport' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
        'alert' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'personnel' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
        'parametre' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
    ],
    'comptable' => [
        'dashboard' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'vente' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'stock' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'medicament' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'categorie' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'facture' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false],
        'fournisseur' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'rapport' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false],
        'alert' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
        'personnel' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
        'parametre' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
    ],
];

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

// Vérification permissions
if (isset($_SESSION['user_id'])) {
    $userRole = $_SESSION['user_role'] ?? 'vendeur';
    
    if (!isset($permissions[$userRole][$mainPage])) {
        $_SESSION['error'] = 'Module non accessible pour votre rôle';
        header('Location: ?page=dashboard');
        exit;
    }
    
    if (!$permissions[$userRole][$mainPage]['view']) {
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
    
    if ($actionType !== 'view' && !$permissions[$userRole][$mainPage][$actionType]) {
        $_SESSION['error'] = 'Action non autorisée';
        header('Location: ?page=' . $mainPage);
        exit;
    }
    
    $_SESSION['user_permissions'] = $permissions[$userRole];
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

$controller->$action();
?>