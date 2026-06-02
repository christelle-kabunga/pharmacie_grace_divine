<?php
class Medicament {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT m.*, cm.nom as categorie_nom, f.nom as fournisseur_nom 
                                 FROM medicaments m 
                                 LEFT JOIN categories_medicament cm ON m.categorie_id = cm.id 
                                 LEFT JOIN fournisseurs f ON m.fournisseur_id = f.id 
                                 ORDER BY m.nom_generique");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM medicaments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO medicaments 
            (nom_generique, categorie_id, fournisseur_id, dosage, prix_achat, prix_vente, 
             quantite_minimale, quantite_maximale, emplacement, date_fabrication, 
             date_expiration, numero_lot, description, contre_indication, effets_secondaires) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $data['nom_generique'], $data['categorie_id'], $data['fournisseur_id'],
            $data['dosage'], $data['prix_achat'], $data['prix_vente'],
            $data['quantite_minimale'], $data['quantite_maximale'], $data['emplacement'],
            $data['date_fabrication'] ?: null, $data['date_expiration'], $data['numero_lot'],
            $data['description'], $data['contre_indication'], $data['effets_secondaires']
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE medicaments SET 
            nom_generique = ?, categorie_id = ?, fournisseur_id = ?, dosage = ?,
            prix_achat = ?, prix_vente = ?, quantite_minimale = ?, quantite_maximale = ?,
            emplacement = ?, date_fabrication = ?, date_expiration = ?, numero_lot = ?,
            description = ?, contre_indication = ?, effets_secondaires = ? WHERE id = ?");
        
        return $stmt->execute([
            $data['nom_generique'], $data['categorie_id'], $data['fournisseur_id'],
            $data['dosage'], $data['prix_achat'], $data['prix_vente'],
            $data['quantite_minimale'], $data['quantite_maximale'], $data['emplacement'],
            $data['date_fabrication'] ?: null, $data['date_expiration'], $data['numero_lot'],
            $data['description'], $data['contre_indication'], $data['effets_secondaires'], $id
        ]);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE medicaments SET statut = 'inactif' WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getLowStock() {
        $stmt = $this->db->query("SELECT * FROM medicaments WHERE quantite_stock <= quantite_minimale AND statut = 'actif'");
        return $stmt->fetchAll();
    }
    
    public function getExpired() {
        $stmt = $this->db->query("SELECT * FROM medicaments WHERE date_expiration < CURDATE()");
        return $stmt->fetchAll();
    }
}
?>