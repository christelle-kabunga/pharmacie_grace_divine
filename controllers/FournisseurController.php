<?php
require_once __DIR__ . '/../models/Fournisseur.php';

class FournisseurController {
    private $model;
    
    public function __construct() {
        $this->model = new Fournisseur();
    }
    
    public function index() {
        $fournisseurs = $this->model->getAll();
        $total = $this->model->count();
        require_once __DIR__ . '/../views/fournisseurs/index.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => htmlspecialchars(strip_tags($_POST['nom'])),
                'telephone' => htmlspecialchars(strip_tags($_POST['telephone'] ?? '')),
                'pays' => htmlspecialchars(strip_tags($_POST['pays'] ?? '')),
                'statut' => htmlspecialchars(strip_tags($_POST['statut'] ?? 'actif'))
            ];
            
            if ($this->model->create($data)) {
                $_SESSION['success'] = "Fournisseur ajouté avec succès.";
                header('Location: index.php?page=fournisseurs');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de l'ajout.";
            }
        }
        require_once __DIR__ . '/../views/fournisseurs/create.php';
    }
    
    public function edit($id) {
        $fournisseur = $this->model->getById($id);
        if (!$fournisseur) {
            $_SESSION['error'] = "Fournisseur introuvable.";
            header('Location: index.php?page=fournisseurs');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => htmlspecialchars(strip_tags($_POST['nom'])),
                'telephone' => htmlspecialchars(strip_tags($_POST['telephone'] ?? '')),
                'pays' => htmlspecialchars(strip_tags($_POST['pays'] ?? '')),
                'statut' => htmlspecialchars(strip_tags($_POST['statut'] ?? 'actif'))
            ];
            
            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Fournisseur modifié avec succès.";
                header('Location: index.php?page=fournisseurs');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la modification.";
            }
        }
        require_once __DIR__ . '/../views/fournisseurs/edit.php';
    }
    
    public function delete($id) {
        if ($this->model->delete($id)) {
            $_SESSION['success'] = "Fournisseur supprimé.";
        } else {
            $_SESSION['error'] = "Erreur lors de la suppression.";
        }
        header('Location: index.php?page=fournisseurs');
        exit;
    }
    
    public function toggle($id) {
        $fournisseur = $this->model->getById($id);
        if ($fournisseur) {
            $newStatut = $fournisseur['statut'] == 'actif' ? 'inactif' : 'actif';
            $this->model->toggleStatut($id, $newStatut);
            $_SESSION['success'] = "Statut modifié.";
        }
        header('Location: index.php?page=fournisseurs');
        exit;
    }
    
    public function view($id) {
        $fournisseur = $this->model->getById($id);
        if (!$fournisseur) {
            $_SESSION['error'] = "Fournisseur introuvable.";
            header('Location: index.php?page=fournisseurs');
            exit;
        }
        require_once __DIR__ . '/../views/fournisseurs/view.php';
    }
    
    public function search() {
        $q = $_GET['q'] ?? '';
        $fournisseurs = $this->model->search($q);
        $total = $this->model->count();
        require_once __DIR__ . '/../views/fournisseurs/index.php';
    }
}
?>