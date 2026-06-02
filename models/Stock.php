<?php
class Stock {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllWithStock() {
        $stmt = $this->db->query("SELECT m.*, cm.nom as categorie, f.nom as fournisseur 
                                  FROM medicaments m 
                                  LEFT JOIN categories_medicament cm ON m.categorie_id = cm.id 
                                  LEFT JOIN fournisseurs f ON m.fournisseur_id = f.id 
                                  ORDER BY m.nom_generique");
        return $stmt->fetchAll();
    }
    
    public function getInventaireComplet() {
        $stmt = $this->db->query("SELECT m.*, 
                                  (m.quantite_stock * m.prix_achat) as valeur_stock,
                                  DATEDIFF(m.date_expiration, CURDATE()) as jours_restant
                                  FROM medicaments m 
                                  ORDER BY m.nom_generique");
        return $stmt->fetchAll();
    }
    
    public function getMouvements($medicamentId = null) {
        $sql = "SELECT ms.*, m.nom_generique, u.prenom as utilisateur_prenom 
                FROM mouvements_stock ms 
                JOIN medicaments m ON ms.medicament_id = m.id 
                LEFT JOIN utilisateurs u ON ms.utilisateur_id = u.id";
        if ($medicamentId) {
            $sql .= " WHERE ms.medicament_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$medicamentId]);
        } else {
            $sql .= " ORDER BY ms.date_mouvement DESC LIMIT 500";
            $stmt = $this->db->query($sql);
        }
        return $stmt->fetchAll();
    }
}
?>