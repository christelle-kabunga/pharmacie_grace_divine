<?php
class DashboardController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function index() {
        $stats = [
            'ventes_jour' => $this->getVentesJour(),
            'ventes_mois' => $this->getVentesMois(),
            'stock_faible' => $this->getStockFaible(),
            'medicaments_perimes' => $this->getMedicamentsPerimes(),
            'total_ventes_count' => $this->getTotalVentesCount(),
            'total_medicaments' => $this->getTotalMedicaments()
        ];
        
        $ventesSemaine = $this->getVentesSemaine();
        $topMedicaments = $this->getTopMedicaments();
        $dernieresVentes = $this->getDernieresVentes();
        $alertes = $this->getAlertesRecentes();
        
        $title = 'Tableau de Bord';
        require 'views/dashboard/index.php';
    }
    
    private function getVentesJour() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_final), 0) as total FROM ventes WHERE DATE(date_vente) = CURDATE()");
        return $stmt->fetch()['total'] ?? 0;
    }
    
    private function getVentesMois() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_final), 0) as total FROM ventes WHERE MONTH(date_vente) = MONTH(CURDATE()) AND YEAR(date_vente) = YEAR(CURDATE())");
        return $stmt->fetch()['total'] ?? 0;
    }
    
    private function getStockFaible() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM medicaments WHERE quantite_stock <= quantite_minimale AND statut = 'actif'");
        return $stmt->fetch()['total'] ?? 0;
    }
    
    private function getMedicamentsPerimes() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM medicaments WHERE date_expiration < CURDATE()");
        return $stmt->fetch()['total'] ?? 0;
    }
    
    private function getTotalVentesCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM ventes");
        return $stmt->fetch()['total'] ?? 0;
    }
    
    private function getTotalMedicaments() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM medicaments WHERE statut = 'actif'");
        return $stmt->fetch()['total'] ?? 0;
    }
    
    private function getVentesSemaine() {
        $stmt = $this->db->query("SELECT DATE(date_vente) as date, SUM(total_final) as total FROM ventes WHERE date_vente >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(date_vente) ORDER BY date");
        return $stmt->fetchAll() ?? [];
    }
    
    private function getTopMedicaments() {
        $stmt = $this->db->query("SELECT m.nom_generique, SUM(dv.quantite) as total_vendu FROM details_vente dv JOIN medicaments m ON dv.medicament_id = m.id JOIN ventes v ON dv.vente_id = v.id WHERE v.date_vente >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY dv.medicament_id ORDER BY total_vendu DESC LIMIT 5");
        return $stmt->fetchAll() ?? [];
    }
    
    private function getDernieresVentes() {
        $stmt = $this->db->query("SELECT v.*, u.prenom as vendeur_prenom, u.nom as vendeur_nom FROM ventes v JOIN utilisateurs u ON v.vendeur_id = u.id ORDER BY v.date_vente DESC LIMIT 10");
        return $stmt->fetchAll() ?? [];
    }
    
    private function getAlertesRecentes() {
        $stmt = $this->db->query("SELECT a.*, m.nom_generique FROM alertes a LEFT JOIN medicaments m ON a.medicament_id = m.id WHERE a.statut = 'nouvelle' ORDER BY a.date_creation DESC LIMIT 5");
        return $stmt->fetchAll() ?? [];
    }
}
?>