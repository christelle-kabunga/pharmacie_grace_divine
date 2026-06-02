<?php
class AlertController {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function index() {
        $alertes = $this->db->query("SELECT a.*, m.nom_commercial, m.quantite_stock, m.quantite_minimale, m.date_expiration 
                                     FROM alertes a 
                                     LEFT JOIN medicaments m ON a.medicament_id = m.id 
                                     ORDER BY a.statut = 'nouvelle' DESC, a.date_creation DESC")->fetchAll();
        $title = 'Centre d\'Alertes';
        require 'views/alertes/index.php';
    }
    
    public function check() {
        // Vérifier nouvelles alertes (appel AJAX)
        $count = $this->db->query("SELECT COUNT(*) FROM alertes WHERE statut = 'nouvelle'")->fetchColumn();
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        exit;
    }
    
    public function resoudre() {
        $id = $_GET['id'] ?? 0;
        $this->db->prepare("UPDATE alertes SET statut = 'resolue', date_resolution = NOW() WHERE id = ?")->execute([$id]);
        header('Location: ?page=alert');
        exit;
    }
}
?>