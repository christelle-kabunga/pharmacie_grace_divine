<?php
class Fournisseur {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        return $this->db->query("SELECT * FROM fournisseurs ORDER BY nom")->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM fournisseurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getActifs() {
        return $this->db->query("SELECT * FROM fournisseurs ORDER BY nom")->fetchAll();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO fournisseurs (nom, telephone, adresse, pays) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $data['nom'], $data['telephone'] ?? null, $data['adresse'] ?? null, $data['pays'] ?? null
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE fournisseurs SET nom = ?, telephone = ?, adresse = ?, pays = ? WHERE id = ?");
        return $stmt->execute([
            $data['nom'], $data['telephone'] ?? null, $data['adresse'] ?? null, $data['pays'] ?? null, $id
        ]);
    }
    
    public function toggleStatut($id, $statut) {
        return false;
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM fournisseurs WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function count() {
        return $this->db->query("SELECT COUNT(*) FROM fournisseurs")->fetchColumn();
    }
    
    public function search($keyword) {
        $stmt = $this->db->prepare("SELECT * FROM fournisseurs 
            WHERE nom LIKE ? OR telephone LIKE ? OR adresse LIKE ? OR pays LIKE ?
            ORDER BY nom");
        $like = "%{$keyword}%";
        $stmt->execute([$like, $like, $like, $like]);
        return $stmt->fetchAll();
    }
}
?>