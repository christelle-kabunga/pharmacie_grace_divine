<?php
class Facture {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT f.*, v.numero_vente, c.nom, c.prenom 
                                  FROM factures f 
                                  LEFT JOIN ventes v ON f.vente_id = v.id 
                                  LEFT JOIN clients c ON f.client_id = c.id 
                                  ORDER BY f.date_facture DESC");
        return $stmt->fetchAll();
    }
}
?>