<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$userRole = $_SESSION['user_role'] ?? 'vendeur';
$userPerms = $_SESSION['user_permissions'] ?? [];

// Définition de tous les menus possibles
$allMenus = [
    ['page' => 'dashboard', 'icon' => 'bi-speedometer2', 'label' => 'Tableau de Bord'],
    ['page' => 'vente', 'icon' => 'bi-cart-plus', 'label' => 'Ventes'],
    ['page' => 'stock', 'icon' => 'bi-box-seam', 'label' => 'Stock'],
    ['page' => 'medicament', 'icon' => 'bi-capsule', 'label' => 'Médicaments'],
    ['page' => 'facture', 'icon' => 'bi-receipt', 'label' => 'Facturation'],
    ['page' => 'client', 'icon' => 'bi-people', 'label' => 'Clients'],
    ['page' => 'fournisseur', 'icon' => 'bi-truck', 'label' => 'Fournisseurs'],
    ['page' => 'rapport', 'icon' => 'bi-graph-up', 'label' => 'Rapports'],
    ['page' => 'alert', 'icon' => 'bi-bell', 'label' => 'Alertes'],
    ['page' => 'personnel', 'icon' => 'bi-person-badge', 'label' => 'Personnel'],
    ['page' => 'parametre', 'icon' => 'bi-gear', 'label' => 'Paramètres'],
];

// Filtrer les menus selon les permissions
$menuItems = [];
foreach ($allMenus as $menu) {
    if (isset($userPerms[$menu['page']]) && $userPerms[$menu['page']]['view']) {
        $menuItems[] = $menu;
    }
}

// Badge de rôle
$roleBadges = [
    'admin' => ['class' => 'bg-danger', 'label' => 'Administrateur'],
    'vendeur' => ['class' => 'bg-success', 'label' => 'Vendeur'],
    'comptable' => ['class' => 'bg-info', 'label' => 'Comptable']
];
$roleBadge = $roleBadges[$userRole] ?? ['class' => 'bg-secondary', 'label' => 'Utilisateur'];
?>

<<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="icon-box bg-primary text-white">
            <i class="bi bi-capsule-pill fs-4"></i>
        </div>
        <span class="text-primary">PharmaGest</span>
    </div>
    
    <div class="px-3 py-2">
        <span class="badge <?= $roleBadge['class'] ?>"><?= $roleBadge['label'] ?></span>
        <small class="text-muted d-block mt-1"><?= $_SESSION['user_name'] ?? '' ?></small>
    </div>
    
    <ul class="sidebar-menu">
        <?php foreach ($menuItems as $item): 
            $isActive = strpos($currentPage, $item['page']) !== false;
        ?>
        <li>
            <a href="?page=<?= $item['page'] ?>" class="<?= $isActive ? 'active' : '' ?>">
                <i class="bi <?= $item['icon'] ?>"></i>
                <span><?= $item['label'] ?></span>
            </a>
        </li>
        <?php endforeach; ?>
        
        <li class="mt-4 pt-4 border-top">
            <a href="?page=auth&action=logout" class="text-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span>Déconnexion</span>
            </a>
        </li>
    </ul>
</aside>