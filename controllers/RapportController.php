<?php
class RapportController {
    private $db;
    private $userPerms;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->userPerms = $_SESSION['user_permissions']['rapport'] ?? ['view' => false, 'create' => false, 'edit' => false, 'delete' => false];
        
        if (!$this->userPerms['view']) {
            $_SESSION['error'] = 'Accès refusé aux rapports';
            header('Location: ?page=dashboard');
            exit;
        }
    }
    
    public function index() {
        $title = 'Rapports';
        require 'views/rapports/index.php';
    }
    
    public function journalier() {
        $date = $_GET['date'] ?? date('Y-m-d');
        $stmt = $this->db->prepare("SELECT v.*, u.prenom as vendeur_prenom, u.nom as vendeur_nom FROM ventes v JOIN utilisateurs u ON v.vendeur_id = u.id WHERE DATE(v.date_vente) = ?");
        $stmt->execute([$date]);
        $ventes = $stmt->fetchAll();
        $total = array_sum(array_column($ventes, 'total_final'));
        $title = 'Rapport Journalier - ' . date('d/m/Y', strtotime($date));
        require 'views/rapports/journalier.php';
    }
    
    public function hebdomadaire() {
        $debut = $_GET['debut'] ?? date('Y-m-d', strtotime('monday this week'));
        $fin = $_GET['fin'] ?? date('Y-m-d', strtotime('sunday this week'));
        $stmt = $this->db->prepare("SELECT DATE(date_vente) as date, COUNT(*) as nb_ventes, SUM(total_final) as total FROM ventes WHERE DATE(date_vente) BETWEEN ? AND ? GROUP BY DATE(date_vente)");
        $stmt->execute([$debut, $fin]);
        $donnees = $stmt->fetchAll();
        $title = 'Rapport Hebdomadaire';
        require 'views/rapports/hebdomadaire.php';
    }
    
    public function mensuel() {
        $mois = $_GET['mois'] ?? date('m');
        $annee = $_GET['annee'] ?? date('Y');
        $stmt = $this->db->prepare("SELECT DAY(date_vente) as jour, COUNT(*) as nb_ventes, SUM(total_final) as total FROM ventes WHERE MONTH(date_vente) = ? AND YEAR(date_vente) = ? GROUP BY DAY(date_vente)");
        $stmt->execute([$mois, $annee]);
        $donnees = $stmt->fetchAll();
        $stmtVendeurs = $this->db->prepare("SELECT u.prenom, u.nom, COUNT(*) as nb_ventes, SUM(v.total_final) as total FROM ventes v JOIN utilisateurs u ON v.vendeur_id = u.id WHERE MONTH(v.date_vente) = ? AND YEAR(v.date_vente) = ? AND v.statut != 'annulee' GROUP BY v.vendeur_id ORDER BY total DESC");
        $stmtVendeurs->execute([$mois, $annee]);
        $vendeurs = $stmtVendeurs->fetchAll();
        $title = 'Rapport Mensuel - ' . date('F Y', strtotime("$annee-$mois-01"));
        require 'views/rapports/mensuel.php';
    }
    
    public function annuel() {
        $annee = $_GET['annee'] ?? date('Y');
        $stmt = $this->db->prepare("SELECT MONTH(date_vente) as mois, COUNT(*) as nb_ventes, SUM(total_final) as total FROM ventes WHERE YEAR(date_vente) = ? GROUP BY MONTH(date_vente)");
        $stmt->execute([$annee]);
        $donnees = $stmt->fetchAll();
        $stmtCat = $this->db->prepare("SELECT cm.nom, SUM(dv.quantite) as qte_vendue, SUM(dv.total_ligne) as total FROM details_vente dv JOIN medicaments m ON dv.medicament_id = m.id JOIN categories_medicament cm ON m.categorie_id = cm.id JOIN ventes v ON dv.vente_id = v.id WHERE YEAR(v.date_vente) = ? GROUP BY cm.id");
        $stmtCat->execute([$annee]);
        $categories = $stmtCat->fetchAll();
        $title = 'Rapport Annuel - ' . $annee;
        require 'views/rapports/annuel.php';
    }
    
    public function global() {
        $stats = [
            'total_ventes' => $this->db->query("SELECT COALESCE(SUM(total_final), 0) FROM ventes")->fetchColumn(),
            'total_ventes_count' => $this->db->query("SELECT COUNT(*) FROM ventes")->fetchColumn(),
            'total_medicaments' => $this->db->query("SELECT COUNT(*) FROM medicaments")->fetchColumn(),
            'stock_valeur' => $this->db->query("SELECT COALESCE(SUM(quantite_stock * prix_achat), 0) FROM medicaments")->fetchColumn()
        ];
        $stmt = $this->db->query("SELECT DATE_FORMAT(date_vente, '%Y-%m') as periode, SUM(total_final) as total FROM ventes WHERE date_vente >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH) GROUP BY DATE_FORMAT(date_vente, '%Y-%m') ORDER BY periode");
        $tendance = $stmt->fetchAll();
        $title = 'Rapport Global';
        require 'views/rapports/global.php';
    }
    
    public function rupture() {
        $ruptures = $this->db->query("SELECT m.*, cm.nom as categorie, f.nom as fournisseur FROM medicaments m LEFT JOIN categories_medicament cm ON m.categorie_id = cm.id LEFT JOIN fournisseurs f ON m.fournisseur_id = f.id WHERE m.quantite_stock <= m.quantite_minimale ORDER BY m.quantite_stock ASC")->fetchAll();
        $title = 'Ruptures de Stock';
        require 'views/rapports/rupture.php';
    }
    
    public function expiration() {
        $jours = $_GET['jours'] ?? 90;
        $dateLimite = date('Y-m-d', strtotime("+$jours days"));
        $perimes = $this->db->prepare("SELECT m.*, DATEDIFF(m.date_expiration, CURDATE()) as jours_restant, cm.nom as categorie FROM medicaments m LEFT JOIN categories_medicament cm ON m.categorie_id = cm.id WHERE m.date_expiration <= ? AND m.quantite_stock > 0 ORDER BY m.date_expiration ASC");
        $perimes->execute([$dateLimite]);
        $title = 'Expirations';
        require 'views/rapports/expiration.php';
    }
}
?>