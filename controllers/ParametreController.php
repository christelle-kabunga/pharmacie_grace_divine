<?php
require_once __DIR__ . '/../models/Parametre.php';

class ParametreController {
    private $model;
    
    public function __construct() {
        $this->model = new Parametre();
    }
    
    public function index() {
        $parametres = $this->model->getAll();
        $generaux = $this->model->getParametresGeneraux();
        $alertes = $this->model->getParametresAlertes();
        $interface = $this->model->getParametresInterface();
        require_once __DIR__ . '/../views/parametres/index.php';
    }
    
    public function edit($id) {
        $parametre = $this->model->getById($id);
        if (!$parametre) {
            $_SESSION['error'] = "Paramètre introuvable.";
            header('Location: index.php?page=parametres');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'valeur' => htmlspecialchars(strip_tags($_POST['valeur'])),
                'description' => htmlspecialchars(strip_tags($_POST['description'] ?? ''))
            ];
            
            if ($this->model->update($id, $data)) {
                $_SESSION['success'] = "Paramètre mis à jour avec succès.";
                header('Location: index.php?page=parametres');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de la mise à jour.";
            }
        }
        require_once __DIR__ . '/../views/parametres/edit.php';
    }
    
    public function updateGeneraux() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateByCle('nom_pharmacie', htmlspecialchars(strip_tags($_POST['nom_pharmacie'])));
            $this->model->updateByCle('adresse_pharmacie', htmlspecialchars(strip_tags($_POST['adresse_pharmacie'])));
            $this->model->updateByCle('telephone_pharmacie', htmlspecialchars(strip_tags($_POST['telephone_pharmacie'])));
            $this->model->updateByCle('email_pharmacie', htmlspecialchars(strip_tags($_POST['email_pharmacie'])));
            $this->model->updateByCle('devise_defaut', htmlspecialchars(strip_tags($_POST['devise_defaut'])));
            
            $_SESSION['success'] = "Paramètres généraux mis à jour.";
            header('Location: index.php?page=parametres');
            exit;
        }
    }
    
    public function updateAlertes() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateByCle('seuil_alerte_stock', intval($_POST['seuil_alerte_stock']));
            $this->model->updateByCle('jours_alerte_expiration', intval($_POST['jours_alerte_expiration']));
            
            $_SESSION['success'] = "Paramètres d'alertes mis à jour.";
            header('Location: index.php?page=parametres');
            exit;
        }
    }
    
    public function updateInterface() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->updateByCle('theme_defaut', htmlspecialchars(strip_tags($_POST['theme_defaut'])));
            
            $_SESSION['success'] = "Paramètres d'interface mis à jour.";
            header('Location: index.php?page=parametres');
            exit;
        }
    }
}
?>