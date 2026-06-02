<?php
class VenteController {
    private $db;
    private $venteModel;
    private $stockModel;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->venteModel = new Vente();
        $this->stockModel = new Stock();
        $this->userPerms = $_SESSION['user_permissions']['vente'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
    }
    
    public function index() {
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès refusé';
            header('Location: ?page=dashboard');
            exit;
        }
        
        $ventes = $this->venteModel->getAll();
        $title = 'Gestion des Ventes';
        require 'views/ventes/index.php';
    }
    
    public function nouveau() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=vente');
            exit;
        }
        
        $medicaments = $this->db->query("SELECT id, nom_generique, prix_vente, quantite_stock 
                                         FROM medicaments 
                                         WHERE statut = 'actif' AND quantite_stock > 0")->fetchAll();
        
        // Récupérer le taux du jour
        $taux = $this->db->query("SELECT * FROM taux_change WHERE actif = 1 ORDER BY date_taux DESC")->fetchAll();
        
        $title = 'Nouvelle Vente';
        require 'views/ventes/nouveau.php';
    }
    
    public function enregistrer() {
        if (!$this->userPerms['create']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=vente');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?page=vente');
            exit;
        }
        
        try {
            $this->db->beginTransaction();
            
            $numeroVente = 'VTE-' . date('Ymd') . '-' . strtoupper(uniqid());
            
            $stmt = $this->db->prepare("INSERT INTO ventes 
                (numero_vente, nom_client, telephone_client, vendeur_id, sous_total, remise_totale, 
                 total_final, montant_paye, monnaie_rendue, mode_paiement, devise, taux_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            $sousTotal = floatval($_POST['sous_total']);
            $remise = floatval($_POST['remise_totale'] ?? 0);
            $total = $sousTotal - $remise;
            $montantPaye = floatval($_POST['montant_paye'] ?? 0);
            $monnaie = $montantPaye - $total;
            
            $stmt->execute([
                $numeroVente,
                $_POST['nom_client'] ?: null,
                $_POST['telephone_client'] ?: null,
                $_SESSION['user_id'],
                $sousTotal,
                $remise,
                $total,
                $montantPaye,
                max(0, $monnaie),
                $_POST['mode_paiement'],
                $_POST['devise'] ?? 'CDF',
                $_POST['taux_id'] ?: null
            ]);
            
            $venteId = $this->db->lastInsertId();
            
            // Enregistrer détails
            $this->processVenteDetails($venteId, $numeroVente, $_POST['medicaments']);
            
            // Créer facture avec vendeur affecté
            $this->createFacture($venteId, $numeroVente, $total);
            
            $this->db->commit();
            
            $_SESSION['success'] = "Vente enregistrée: $numeroVente";
            header('Location: ?page=vente&action=details&id=' . $venteId);
            exit;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            $_SESSION['error'] = "Erreur: " . $e->getMessage();
            header('Location: ?page=vente&action=nouveau');
            exit;
        }
    }
    
    private function processVenteDetails($venteId, $numeroVente, $medicaments) {
        $stmtDetail = $this->db->prepare("INSERT INTO details_vente 
            (vente_id, medicament_id, quantite, prix_unitaire, remise_ligne, total_ligne) 
            VALUES (?, ?, ?, ?, ?, ?)");
        
        $stmtMouvement = $this->db->prepare("INSERT INTO mouvements_stock 
            (medicament_id, type_mouvement, quantite, stock_avant, stock_apres, reference, utilisateur_id) 
            VALUES (?, 'sortie', ?, ?, ?, ?, ?)");
        
        $stmtUpdateStock = $this->db->prepare("UPDATE medicaments SET quantite_stock = ? WHERE id = ?");
        
        foreach ($medicaments as $med) {
            $medId = $med['id'];
            $qte = intval($med['quantite']);
            $prix = floatval($med['prix']);
            $remiseLigne = floatval($med['remise'] ?? 0);
            $totalLigne = ($qte * $prix) - $remiseLigne;
            
            $stmtDetail->execute([$venteId, $medId, $qte, $prix, $remiseLigne, $totalLigne]);
            
            $stockActuel = $this->db->query("SELECT quantite_stock FROM medicaments WHERE id = $medId")->fetch()['quantite_stock'];
            $nouveauStock = $stockActuel - $qte;
            
            $stmtMouvement->execute([$medId, $qte, $stockActuel, $nouveauStock, $numeroVente, $_SESSION['user_id']]);
            $stmtUpdateStock->execute([$nouveauStock, $medId]);
            
            if ($nouveauStock <= 10) {
                $this->creerAlerte('rupture', $medId, "Stock faible: $nouveauStock unités");
            }
        }
    }
    
    private function createFacture($venteId, $numeroVente, $total) {
        $numeroFacture = 'FAC-' . date('Ymd') . '-' . strtoupper(uniqid());
        $stmt = $this->db->prepare("INSERT INTO factures 
            (numero_facture, vente_id, vendeur_id, montant_ht, montant_ttc, statut) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $numeroFacture,
            $venteId,
            $_SESSION['user_id'], // Vendeur affecté à sa facture
            $total,
            $total,
            'payee'
        ]);
    }
    
    public function details() {
        if (!$this->userPerms['view']) {
            header('Location: ?page=dashboard');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $vente = $this->venteModel->getById($id);
        $details = $this->venteModel->getDetails($id);
        $title = 'Détails Vente #' . $vente['numero_vente'];
        require 'views/ventes/details.php';
    }
    
    public function annuler() {
        if (!$this->userPerms['delete']) {
            $_SESSION['error'] = 'Permission refusée';
            header('Location: ?page=vente');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $this->venteModel->annuler($id);
        $_SESSION['success'] = "Vente annulée";
        header('Location: ?page=vente');
        exit;
    }
    
    private function creerAlerte($type, $medicamentId, $message) {
        $stmt = $this->db->prepare("INSERT INTO alertes (type_alerte, medicament_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$type, $medicamentId, $message]);
    }
}
?>