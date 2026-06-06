<?php
class AlertController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        if ($this->tableExists('alertes')) {
            $alertes = $this->db->query("SELECT a.*, m.nom_commercial, m.quantite_stock, m.quantite_minimale, m.date_expiration 
                                     FROM alertes a 
                                     LEFT JOIN medicaments m ON a.medicament_id = m.id 
                                     ORDER BY a.statut = 'nouvelle' DESC, a.date_creation DESC")->fetchAll();
        } else {
            $alertes = $this->generateAlertes();
        }

        $title = 'Centre d\'Alertes';
        require 'views/alertes/index.php';
    }

    public function check() {
        if (!$this->tableExists('alertes')) {
            header('Content-Type: application/json');
            echo json_encode(['count' => 0]);
            exit;
        }

        $count = $this->db->query("SELECT COUNT(*) FROM alertes WHERE statut = 'nouvelle'")->fetchColumn();
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }

    public function resoudre() {
        $id = $_GET['id'] ?? 0;
        if ($this->tableExists('alertes')) {
            $this->db->prepare("UPDATE alertes SET statut = 'resolue', date_resolution = NOW() WHERE id = ?")->execute([$id]);
        }
        header('Location: ?page=alert');
        exit;
    }

    private function tableExists($table) {
        $stmt = $this->db->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$table]);
        return (bool) $stmt->fetchColumn();
    }

    private function generateAlertes() {
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
                'id' => $row['id'],
                'type_alerte' => 'rupture',
                'message' => "Stock faible : {$row['quantite_stock']} unités",
                'nom_commercial' => $row['nom_generique'],
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
                'id' => $row['id'],
                'type_alerte' => 'expiration',
                'message' => "Expiration proche le " . date('d/m/Y', strtotime($row['date_expiration'])),
                'nom_commercial' => $row['nom_generique'],
                'quantite_stock' => null,
                'quantite_minimale' => null,
                'date_expiration' => $row['date_expiration'],
                'statut' => 'nouvelle',
                'date_creation' => date('Y-m-d H:i:s'),
            ];
        }

        return array_slice($alertes, 0, 20);
    }
}
?>