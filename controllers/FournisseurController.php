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
                'email' => htmlspecialchars(strip_tags($_POST['email'] ?? '')),
                'adresse' => htmlspecialchars(strip_tags($_POST['adresse'] ?? '')),
                'pays' => htmlspecialchars(strip_tags($_POST['pays'] ?? '')),
                'ville' => htmlspecialchars(strip_tags($_POST['ville'] ?? '')),
                'nif' => htmlspecialchars(strip_tags($_POST['nif'] ?? '')),
                'statut' => htmlspecialchars(strip_tags($_POST['statut'] ?? 'actif'))
            ];
            
            if ($this->model->create($data)) {
                $_SESSION['success'] = "Fournisseur ajouté avec succès.";
                header('Location: index.php?page=fournisseur');
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
            header('Location: index.php?page=fournisseur');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => htmlspecialchars(strip_tags($_POST['nom'])),
                'telephone' => htmlspecialchars(strip_tags($_POST['telephone'] ?? '')),
                'email' => htmlspecialchars(strip_tags($_POST['email'] ?? '')),
                'adresse' => htmlspecialchars(strip_tags($_POST['adresse'] ?? '')),
                'pays' => htmlspecialchars(strip_tags($_POST['pays'] ?? '')),
                'ville' => htmlspecialchars(strip_tags($_POST['ville'] ?? '')),
                'nif' => htmlspecialchars(strip_tags($_POST['nif'] ?? '')),
                'statut' => htmlspecialchars(strip_tags($_POST['statut'] ?? 'actif'))
            ];
            
            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Fournisseur modifié avec succès.";
                header('Location: index.php?page=fournisseur');
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
        header('Location: index.php?page=fournisseur');
        exit;
    }
    
    public function toggle($id) {
        $_SESSION['error'] = "Le statut des fournisseurs n'est plus géré dans cette version.";
        header('Location: index.php?page=fournisseur');
        exit;
    }

    public function toggleStatut($id) {
        $this->toggle($id);
    }
    
    public function view($id) {
        $fournisseur = $this->model->getById($id);
        if (!$fournisseur) {
            $_SESSION['error'] = "Fournisseur introuvable.";
            header('Location: index.php?page=fournisseur');
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