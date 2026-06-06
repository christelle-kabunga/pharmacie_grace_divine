<?php
class AuthController {
    private $db;
    private $userModel;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User();
    }
    
    public function login() {
        $error = '';
        
        // Si déjà connecté, rediriger
        if (isset($_SESSION['user_id'])) {
            header('Location: ?page=dashboard');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            $user = $this->userModel->authenticate($username, $password);
            
            if ($user) {
                // Vérifier si le compte est actif
                if ($user['statut'] !== 'actif') {
                    $error = 'Votre compte est désactivé. Contactez l\'administrateur.';
                } else {
                    // Stocker les infos de session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['prenom'] . ' ' . $user['nom'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    // Récupérer et stocker les permissions
                    $perms = $this->userModel->getPermissions($user['role']);
                    $_SESSION['user_permissions'] = $perms;
                    
                    $this->userModel->updateLastLogin($user['id']);
                    
                    header('Location: ?page=dashboard');
                    exit;
                }
            } else {
                $error = 'Identifiants incorrects';
            }
        }
        
        // Page de login sans header/sidebar
        require 'views/auth/login.php';
    }
    
    public function logout() {
        session_destroy();
        header('Location: ?page=auth&action=login');
        exit;
    }
}
?>