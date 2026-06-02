<?php
class StockController {
    private $db;
    private $stockModel;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->stockModel = new Stock();
        $this->userPerms = $_SESSION['user_permissions']['stock'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
    }
    
    public function index() {
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès refusé au stock';
            header('Location: ?page=dashboard');
            exit;
        }
        
        $medicaments = $this->stockModel->getAllWithStock();
        $title = 'Gestion du Stock';
        require 'views/stock/index.php';
    }
    
    public function entree() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=stock');
            exit;
        }
        
        $medicaments = $this->db->query("SELECT id, nom_generique FROM medicaments ORDER BY nom_generique")->fetchAll();
        $fournisseurs = $this->db->query("SELECT id, nom FROM fournisseurs WHERE statut = 'actif'")->fetchAll();
        $title = 'Entrée de Stock';
        require 'views/stock/entree.php';
    }
    
    public function enregistrerEntree() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=stock');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        try {
            $this->db->beginTransaction();
            
            $numeroEntree = 'ENT-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            $stmt = $this->db->prepare("INSERT INTO entrees_stock 
                (numero_entree, fournisseur_id, medicament_id, quantite, prix_achat_unitaire, 
                 prix_vente_unitaire, date_fabrication, date_expiration, numero_lot, responsable_id, notes) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->execute([
                $numeroEntree,
                $_POST['fournisseur_id'] ?: null,
                $_POST['medicament_id'],
                $_POST['quantite'],
                $_POST['prix_achat'],
                $_POST['prix_vente'],
                $_POST['date_fabrication'] ?: null,
                $_POST['date_expiration'],
                $_POST['numero_lot'] ?: null,
                $_SESSION['user_id'],
                $_POST['notes'] ?? null
            ]);
            
            $stmtUpdate = $this->db->prepare("UPDATE medicaments SET 
                quantite_stock = quantite_stock + ?,
                prix_achat = ?,
                prix_vente = ?,
                date_expiration = ?,
                numero_lot = ?,
                statut = 'actif'
                WHERE id = ?");
            
            $stmtUpdate->execute([
                $_POST['quantite'],
                $_POST['prix_achat'],
                $_POST['prix_vente'],
                $_POST['date_expiration'],
                $_POST['numero_lot'] ?: null,
                $_POST['medicament_id']
            ]);
            
            $this->db->commit();
            $_SESSION['success'] = "Entrée enregistrée: $numeroEntree";
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
        }
        
        header('Location: ?page=stock');
        exit;
    }
    
    public function inventaire() {
        if (!$this->userPerms['view']) {
            header('Location: ?page=dashboard');
            exit;
        }
        
        $inventaire = $this->stockModel->getInventaireComplet();
        $title = 'Inventaire';
        require 'views/stock/inventaire.php';
    }
    
    public function mouvements() {
        if (!$this->userPerms['view']) {
            header('Location: ?page=dashboard');
            exit;
        }
        
        $mouvements = $this->stockModel->getMouvements();
        $title = 'Mouvements';
        require 'views/stock/mouvements.php';
    }
    
    public function search() {
        if (!$this->userPerms['view']) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Accès refusé']);
            exit;
        }
        
        $q = $_GET['q'] ?? '';
        $stmt = $this->db->prepare("SELECT id, nom_generique, prix_vente, quantite_stock 
                                    FROM medicaments 
                                    WHERE (nom_generique LIKE ? OR code_barre LIKE ?)
                                    AND statut = 'actif' AND quantite_stock > 0
                                    LIMIT 10");
        $search = "%$q%";
        $stmt->execute([$search, $search]);
        header('Content-Type: application/json');
        echo json_encode($stmt->fetchAll());
        exit;
    }
}
?>