<?php
class Vente {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT v.*, u.prenom as vendeur_prenom, u.nom as vendeur_nom 
                                  FROM ventes v 
                                  JOIN utilisateurs u ON v.vendeur_id = u.id 
                                  ORDER BY v.date_vente DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT v.*, u.prenom as vendeur_prenom, u.nom as vendeur_nom
                                    FROM ventes v 
                                    JOIN utilisateurs u ON v.vendeur_id = u.id 
                                    WHERE v.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getDetails($venteId) {
        $stmt = $this->db->prepare("SELECT dv.*, m.nom_generique, m.dosage 
                                    FROM details_vente dv 
                                    JOIN medicaments m ON dv.medicament_id = m.id 
                                    WHERE dv.vente_id = ?");
        $stmt->execute([$venteId]);
        return $stmt->fetchAll();
    }
    
    public function annuler($id) {
        $this->db->beginTransaction();
        
        try {
            $details = $this->getDetails($id);
            foreach ($details as $d) {
                $this->db->prepare("UPDATE medicaments SET quantite_stock = quantite_stock + ? WHERE id = ?")
                    ->execute([$d['quantite'], $d['medicament_id']]);
            }
            
            $this->db->prepare("UPDATE ventes SET statut = 'annulee' WHERE id = ?")->execute([$id]);
            $this->db->prepare("UPDATE factures SET statut = 'annulee' WHERE vente_id = ?")->execute([$id]);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>