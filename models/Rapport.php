<?php
class Rapport {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getVentesParPeriode($debut, $fin) {
        $stmt = $this->db->prepare("SELECT DATE(date_vente) as date, COUNT(*) as nb, SUM(total_final) as total 
                                    FROM ventes 
                                    WHERE DATE(date_vente) BETWEEN ? AND ? AND statut != 'annulee'
                                    GROUP BY DATE(date_vente)");
        $stmt->execute([$debut, $fin]);
        return $stmt->fetchAll();
    }
    
    public function getVentesParMois($annee) {
        $stmt = $this->db->prepare("SELECT MONTH(date_vente) as mois, COUNT(*) as nb, SUM(total_final) as total 
                                    FROM ventes 
                                    WHERE YEAR(date_vente) = ? AND statut != 'annulee'
                                    GROUP BY MONTH(date_vente)");
        $stmt->execute([$annee]);
        return $stmt->fetchAll();
    }
    
    public function getBenefices($debut, $fin) {
        $stmt = $this->db->prepare("SELECT SUM((dv.prix_unitaire - m.prix_achat) * dv.quantite) as benefice 
                                    FROM details_vente dv 
                                    JOIN medicaments m ON dv.medicament_id = m.id 
                                    JOIN ventes v ON dv.vente_id = v.id 
                                    WHERE DATE(v.date_vente) BETWEEN ? AND ? AND v.statut != 'annulee'");
        $stmt->execute([$debut, $fin]);
        return $stmt->fetchColumn() ?? 0;
    }
}
?>