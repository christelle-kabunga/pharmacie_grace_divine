<?php
class User {
    private $db;
    
    private $permissions = [
        'admin' => [
            'dashboard' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'vente' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'stock' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'medicament' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'categorie' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'facture' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'fournisseur' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'rapport' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'alert' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'personnel' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
            'parametre' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true],
        ],
        'vendeur' => [
            'dashboard' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'vente' => ['view' => true, 'create' => true, 'edit' => false, 'delete' => false],
            'stock' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'medicament' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'categorie' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'facture' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'fournisseur' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
            'rapport' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
            'alert' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'personnel' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
            'parametre' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
        ],
        'comptable' => [
            'dashboard' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'vente' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'stock' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'medicament' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'categorie' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'facture' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false],
            'fournisseur' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'rapport' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false],
            'alert' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false],
            'personnel' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
            'parametre' => ['view' => false, 'create' => false, 'edit' => false, 'delete' => false],
        ],
    ];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
    
    public function updateLastLogin($id) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET derniere_connexion = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getAll() {
        $stmt = $this->db->query("SELECT id, matricule, nom, prenom, email, telephone, poste, role, statut, date_embauche, salaire, derniere_connexion FROM utilisateurs ORDER BY nom, prenom");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO utilisateurs 
            (matricule, nom, prenom, email, telephone, adresse, date_naissance, date_embauche, 
             poste, salaire, username, password_hash, role, statut) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return $stmt->execute([
            $data['matricule'], $data['nom'], $data['prenom'], $data['email'], 
            $data['telephone'], $data['adresse'], $data['date_naissance'], 
            $data['date_embauche'], $data['poste'], $data['salaire'],
            $data['username'], $passwordHash, $data['role'], $data['statut'] ?? 'actif'
        ]);
    }
    
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET 
            nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ?,
            poste = ?, salaire = ?, role = ?, statut = ? WHERE id = ?");
        return $stmt->execute([
            $data['nom'], $data['prenom'], $data['email'], $data['telephone'],
            $data['adresse'], $data['poste'], $data['salaire'], $data['role'], 
            $data['statut'], $id
        ]);
    }
    
    public function updateStatut($id, $statut) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET statut = ? WHERE id = ?");
        return $stmt->execute([$statut, $id]);
    }
    
    public function delete($id) {
        return $this->updateStatut($id, 'inactif');
    }

    public function updatePassword($id, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE utilisateurs SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }
    
    public function getPermissions($role) {
        return $this->permissions[$role] ?? $this->permissions['vendeur'];
    }
    
    public function canAccess($role, $module, $action = 'view') {
        $perms = $this->getPermissions($role);
        if (!isset($perms[$module])) return false;
        return $perms[$module][$action] ?? false;
    }
    
    public function getAvailableRoles() {
        return ['admin', 'vendeur', 'comptable'];
    }
}
?>