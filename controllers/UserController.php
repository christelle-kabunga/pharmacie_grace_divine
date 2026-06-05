<?php
class UserController {
    private $db;
    private $userModel;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userModel = new User();
        $this->userPerms = $_SESSION['user_permissions']['user'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
        
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès réservé aux administrateurs';
            header('Location: ?page=dashboard');
            exit;
        }
    }
    
    public function index() {
        $personnel = $this->userModel->getAll();
        $title = 'Gestion du Personnel';
        require 'views/user/index.php';
    }
    
    public function nouveau() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Seul l\'administrateur peut créer des utilisateurs';
            header('Location: ?page=user');
            exit;
        }
        
        $title = 'Nouvel Employé';
        require 'views/user/nouveau.php';
    }
    
    public function enregistrer() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=user');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $data['password'] = $data['password'] ?? 'password123';
            
            if ($this->userModel->create($data)) {
                $_SESSION['success'] = 'Utilisateur créé avec succès. Mot de passe: password123';
            } else {
                $_SESSION['error'] = 'Erreur lors de la création';
            }
        }
        header('Location: ?page=user');
        exit;
    }
    
    public function edit() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=user');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $user = $this->userModel->getById($id);
        $title = 'Modifier Employé';
        require 'views/user/edit.php';
    }
    
    public function update() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=user');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->userModel->update($id, $_POST)) {
                $_SESSION['success'] = 'Utilisateur mis à jour';
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour';
            }
        }
        header('Location: ?page=user');
        exit;
    }
    
    public function toggleStatut() {
        if (!$this->userPerms['delete']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=user');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $nouveauStatut = $_GET['statut'] ?? 'actif';
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Vous ne pouvez pas modifier votre propre statut';
            header('Location: ?page=user');
            exit;
        }
        
        if ($this->userModel->updateStatut($id, $nouveauStatut)) {
            $_SESSION['success'] = 'Statut mis à jour: ' . $nouveauStatut;
        } else {
            $_SESSION['error'] = 'Erreur';
        }
        header('Location: ?page=user');
        exit;
    }

    public function delete() {
        if (!$this->userPerms['delete']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=user');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Vous ne pouvez pas supprimer votre propre compte';
            header('Location: ?page=user');
            exit;
        }

        if ($this->userModel->delete($id)) {
            $_SESSION['success'] = 'Utilisateur désactivé';
        } else {
            $_SESSION['error'] = 'Erreur lors de la suppression';
        }
        header('Location: ?page=user');
        exit;
    }

    public function resetPassword() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=user');
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $new = 'password';
        if ($this->userModel->updatePassword($id, $new)) {
            $_SESSION['success'] = 'Mot de passe réinitialisé à : ' . $new;
        } else {
            $_SESSION['error'] = 'Erreur lors de la réinitialisation';
        }
        header('Location: ?page=user');
        exit;
    }
    
    public function profil() {
        $user = $this->userModel->getById($_SESSION['user_id']);
        $title = 'Mon Profil';
        require 'views/user/profil.php';
    }
}
?>
