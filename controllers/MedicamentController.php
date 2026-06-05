<?php
class MedicamentController {
    private $db;
    private $medicamentModel;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->medicamentModel = new Medicament();
        $this->userPerms = $_SESSION['user_permissions']['medicament'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
    }
    
    public function index() {
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès refusé';
            header('Location: ?page=dashboard');
            exit;
        }
        
        $medicaments = $this->medicamentModel->getAll();
        $title = 'Gestion des Médicaments';
        require 'views/medicaments/index.php';
    }
    
    public function nouveau() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=medicament');
            exit;
        }
        
        $categories = $this->db->query("SELECT * FROM categories_medicament ORDER BY nom")->fetchAll();
        $fournisseurs = $this->db->query("SELECT * FROM fournisseurs ORDER BY nom")->fetchAll();
        $title = 'Nouveau Médicament';
        require 'views/medicaments/nouveau.php';
    }
    
    public function enregistrer() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=medicament');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->medicamentModel->create($_POST);
            $_SESSION['success'] = 'Médicament ajouté';
        }
        header('Location: ?page=medicament');
        exit;
    }
    
    public function edit() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=medicament');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $medicament = $this->medicamentModel->getById($id);
        $categories = $this->db->query("SELECT * FROM categories_medicament ORDER BY nom")->fetchAll();
        $fournisseurs = $this->db->query("SELECT * FROM fournisseurs ORDER BY nom")->fetchAll();
        $title = 'Modifier Médicament';
        require 'views/medicaments/edit.php';
    }
    
    public function update() {
        if (!$this->userPerms['edit']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=medicament');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->medicamentModel->update($id, $_POST);
            $_SESSION['success'] = 'Médicament mis à jour';
        }
        header('Location: ?page=medicament');
        exit;
    }
}
?>