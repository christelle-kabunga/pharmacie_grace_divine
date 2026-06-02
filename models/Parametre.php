<?php
class Parametre {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM parametres_systeme ORDER BY id ASC")->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM parametres_systeme WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByCle($cle) {
        $stmt = $this->db->prepare("SELECT * FROM parametres_systeme WHERE cle = ? LIMIT 1");
        $stmt->execute([$cle]);
        return $stmt->fetch();
    }
    
    public function getValeur($cle) {
        $param = $this->getByCle($cle);
        return $param ? $param['valeur'] : null;
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE parametres_systeme SET valeur = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['valeur'], $data['description'], $id]);
    }
    
    public function updateByCle($cle, $valeur) {
        $stmt = $this->db->prepare("UPDATE parametres_systeme SET valeur = ? WHERE cle = ?");
        return $stmt->execute([$valeur, $cle]);
    }
    
    public function getParametresGeneraux() {
        return [
            'nom_pharmacie' => $this->getValeur('nom_pharmacie'),
            'adresse_pharmacie' => $this->getValeur('adresse_pharmacie'),
            'telephone_pharmacie' => $this->getValeur('telephone_pharmacie'),
            'email_pharmacie' => $this->getValeur('email_pharmacie'),
            'devise_defaut' => $this->getValeur('devise_defaut'),
        ];
    }
    
    public function getParametresAlertes() {
        return [
            'seuil_alerte_stock' => $this->getValeur('seuil_alerte_stock'),
            'jours_alerte_expiration' => $this->getValeur('jours_alerte_expiration'),
        ];
    }
    
    public function getParametresInterface() {
        return [
            'theme_defaut' => $this->getValeur('theme_defaut'),
        ];
    }
}
?>