<?php
class FactureController {
    private $db;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userPerms = $_SESSION['user_permissions']['facture'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
    }
    
    public function index() {
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès refusé';
            header('Location: ?page=dashboard');
            exit;
        }
        
        $factures = $this->db->query("SELECT f.*, v.numero_vente, v.nom_client, u.prenom as vendeur_prenom, u.nom as vendeur_nom 
                                      FROM factures f 
                                      LEFT JOIN ventes v ON f.vente_id = v.id 
                                      JOIN utilisateurs u ON f.vendeur_id = u.id 
                                      ORDER BY f.date_facture DESC")->fetchAll();
        $title = 'Gestion des Factures';
        require 'views/factures/index.php';
    }
    
    public function details() {
        if (!$this->userPerms['view']) {
            header('Location: ?page=dashboard');
            exit;
        }
        
        $id = $_GET['id'] ?? 0;
        $stmt = $this->db->prepare("SELECT f.*, v.numero_vente, v.date_vente, v.mode_paiement, v.nom_client, v.telephone_client,
                                    v.devise, v.sous_total, v.remise_totale,
                                    u.prenom as vendeur_prenom, u.nom as vendeur_nom
                                    FROM factures f 
                                    LEFT JOIN ventes v ON f.vente_id = v.id 
                                    JOIN utilisateurs u ON f.vendeur_id = u.id
                                    WHERE f.id = ?");
        $stmt->execute([$id]);
        $facture = $stmt->fetch();
        
        $stmtDetails = $this->db->prepare("SELECT dv.*, m.nom_generique, m.dosage 
                                           FROM details_vente dv 
                                           JOIN medicaments m ON dv.medicament_id = m.id 
                                           WHERE dv.vente_id = ?");
        $stmtDetails->execute([$facture['vente_id']]);
        $details = $stmtDetails->fetchAll();
        
        $title = 'Facture #' . $facture['numero_facture'];
        require 'views/factures/details.php';
    }
}
?>