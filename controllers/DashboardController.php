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
            'total_medicaments' => $this->getTotalMedicaments(),
        ];

        $ventesSemaine = $this->getVentesSemaine();
        $topMedicaments = $this->getTopMedicaments();
        $dernieresVentes = $this->getDernieresVentes();
        $alertes = $this->getAlertesRecentes();

        $title = 'Tableau de Bord';
        require 'views/dashboard/index.php';
    }

    private function getVentesJour() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_final), 0) as total FROM ventes WHERE DATE(created_at) = CURDATE()");
        return $stmt->fetch()['total'] ?? 0;
    }

    private function getVentesMois() {
        $stmt = $this->db->query("SELECT COALESCE(SUM(total_final), 0) as total FROM ventes WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
        return $stmt->fetch()['total'] ?? 0;
    }

    private function getStockFaible() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM medicaments WHERE quantite_stock <= quantite_minimale");
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
        $stmt = $this->db->query("SELECT DATE(created_at) as date, SUM(total_final) as total FROM ventes WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) GROUP BY DATE(created_at) ORDER BY date");
        return $stmt->fetchAll() ?? [];
    }

    private function getTopMedicaments() {
        $stmt = $this->db->query("SELECT m.nom_generique, SUM(dv.quantite) as total_vendu FROM details_vente dv JOIN medicaments m ON dv.medicament_id = m.id JOIN ventes v ON dv.vente_id = v.id WHERE v.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) GROUP BY dv.medicament_id ORDER BY total_vendu DESC LIMIT 5");
        return $stmt->fetchAll() ?? [];
    }

    private function getDernieresVentes() {
        $stmt = $this->db->query("SELECT v.*, u.prenom as vendeur_prenom, u.nom as vendeur_nom FROM ventes v JOIN utilisateurs u ON v.vendeur_id = u.id ORDER BY v.created_at DESC LIMIT 10");
        return $stmt->fetchAll() ?? [];
    }

    private function getAlertesRecentes() {
        $alertes = [];
        $params = [];

        $stmt = $this->db->prepare("SELECT cle, valeur FROM parametres_systeme WHERE cle IN ('seuil_alerte_stock', 'jours_alerte_expiration')");
        $stmt->execute();
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $param) {
            $params[$param['cle']] = $param['valeur'];
        }

        $threshold = intval($params['seuil_alerte_stock'] ?? 10);
        $days = intval($params['jours_alerte_expiration'] ?? 7);

        $stmtStock = $this->db->prepare("SELECT id, nom_generique, quantite_stock, quantite_minimale FROM medicaments WHERE quantite_stock <= :threshold ORDER BY quantite_stock ASC");
        $stmtStock->execute(['threshold' => $threshold]);
        foreach ($stmtStock->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $alertes[] = [
                'type_alerte' => 'rupture',
                'message' => "Stock faible : {$row['quantite_stock']} unités",
                'nom_generique' => $row['nom_generique'],
                'quantite_stock' => $row['quantite_stock'],
                'quantite_minimale' => $row['quantite_minimale'],
                'date_expiration' => null,
                'statut' => 'nouvelle',
                'date_creation' => date('Y-m-d H:i:s'),
            ];
        }

        $stmtExp = $this->db->prepare("SELECT id, nom_generique, date_expiration FROM medicaments WHERE date_expiration BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL :days DAY) ORDER BY date_expiration ASC");
        $stmtExp->execute(['days' => $days]);
        foreach ($stmtExp->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $alertes[] = [
                'type_alerte' => 'expiration',
                'message' => "Expiration proche le " . date('d/m/Y', strtotime($row['date_expiration'])),
                'nom_generique' => $row['nom_generique'],
                'quantite_stock' => null,
                'quantite_minimale' => null,
                'date_expiration' => $row['date_expiration'],
                'statut' => 'nouvelle',
                'date_creation' => date('Y-m-d H:i:s'),
            ];
        }

        return array_slice($alertes, 0, 5);
    }
}
?>