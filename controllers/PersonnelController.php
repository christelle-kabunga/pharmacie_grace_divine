<?php
class PersonnelController {
    private $db;
    private $personnelModel;
    private $userModel;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->personnelModel = new Personnel();
        $this->userModel = new User();
        $this->userPerms = $_SESSION['user_permissions']['personnel'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
        
        // Vérifier que seul l'admin peut accéder
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès réservé aux administrateurs';
            header('Location: ?page=dashboard');
            exit;
        }
    }
    
    public function index() {
        $personnel = $this->personnelModel->getAll();
        $title = 'Gestion du Personnel';
        require 'views/personnel/index.php';
    }
    
    public function nouveau() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Seul l\'administrateur peut créer des utilisateurs';
            header('Location: ?page=personnel');
            exit;
        }
        
        $title = 'Nouvel Employé';
        require 'views/personnel/nouveau.php';
    }
    
    public function enregistrer() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=personnel');
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
        header('Location: ?page=personnel');
        exit;
    }
    
    public function edit() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=personnel');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $user = $this->personnelModel->getById($id);
        $title = 'Modifier Employé';
        require 'views/personnel/edit.php';
    }
    
    public function update() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=personnel');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->personnelModel->update($id, $_POST)) {
                $_SESSION['success'] = 'Utilisateur mis à jour';
            } else {
                $_SESSION['error'] = 'Erreur lors de la mise à jour';
            }
        }
        header('Location: ?page=personnel');
        exit;
    }
    
    public function toggleStatut() {
        if (!$this->userPerms['delete']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=personnel');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $nouveauStatut = $_GET['statut'] ?? 'actif';
        
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = 'Vous ne pouvez pas modifier votre propre statut';
            header('Location: ?page=personnel');
            exit;
        }
        
        if ($this->userModel->updateStatut($id, $nouveauStatut)) {
            $_SESSION['success'] = 'Statut mis à jour: ' . $nouveauStatut;
        } else {
            $_SESSION['error'] = 'Erreur';
        }
        header('Location: ?page=personnel');
        exit;
    }
    
    public function profil() {
        $user = $this->personnelModel->getById($_SESSION['user_id']);
        $title = 'Mon Profil';
        require 'views/personnel/profil.php';
    }
}
?>