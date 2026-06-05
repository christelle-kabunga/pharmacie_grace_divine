<?php
class Categorie {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        return $this->db->query("SELECT * FROM categories_medicament ORDER BY nom")->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM categories_medicament WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO categories_medicament (nom, description) VALUES (?, ?)");
        return $stmt->execute([$data['nom'], $data['description']]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE categories_medicament SET nom = ?, description = ? WHERE id = ?");
        return $stmt->execute([$data['nom'], $data['description'], $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories_medicament WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function countMedicaments($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM medicaments WHERE categorie_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn();
    }
}
?>