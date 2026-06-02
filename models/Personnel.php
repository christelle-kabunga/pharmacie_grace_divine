<?php
class Personnel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        return $this->db->query("SELECT id, matricule, nom, prenom, email, telephone, poste, role, statut, date_embauche, salaire 
                                 FROM utilisateurs ORDER BY nom, prenom")->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET 
            nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?,
            poste = ?, salaire = ?, statut = ? WHERE id = ?");
        return $stmt->execute([
            $data['nom'], $data['prenom'], $data['email'], $data['telephone'],
            $data['adresse'], $data['poste'], $data['salaire'], $data['statut'], $id
        ]);
    }
    
    public function delete($id) {
        // Désactiver plutôt que supprimer
        $stmt = $this->db->prepare("UPDATE utilisateurs SET statut = 'inactif' WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>