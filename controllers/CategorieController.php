<?php
class CategorieController {
    private $db;
    private $categorieModel;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->categorieModel = new Categorie();
        $this->userPerms = $_SESSION['user_permissions']['categorie'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
    }
    
    public function index() {
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès refusé';
            header('Location: ?page=dashboard');
            exit;
        }
        
        $categories = $this->categorieModel->getAll();
        $title = 'Gestion des Catégories';
        require 'views/categories/index.php';
    }
    
    public function nouveau() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=categorie');
            exit;
        }
        
        $title = 'Nouvelle Catégorie';
        require 'views/categories/nouveau.php';
    }
    
    public function enregistrer() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=categorie');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categorieModel->create($_POST);
            $_SESSION['success'] = 'Catégorie créée';
        }
        header('Location: ?page=categorie');
        exit;
    }
    
    public function edit() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=categorie');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $categorie = $this->categorieModel->getById($id);
        $title = 'Modifier Catégorie';
        require 'views/categories/edit.php';
    }
    
    public function update() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=categorie');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->categorieModel->update($id, $_POST);
            $_SESSION['success'] = 'Catégorie mise à jour';
        }
        header('Location: ?page=categorie');
        exit;
    }
    
    public function supprimer() {
        if (!$this->userPerms['delete']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=categorie');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $count = $this->categorieModel->countMedicaments($id);
        
        if ($count > 0) {
            $_SESSION['error'] = "Impossible de supprimer : $count médicament(s) utilisent cette catégorie";
        } else {
            $this->categorieModel->delete($id);
            $_SESSION['success'] = 'Catégorie supprimée';
        }
        header('Location: ?page=categorie');
        exit;
    }
}
?>