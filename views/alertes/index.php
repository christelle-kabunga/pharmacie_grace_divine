<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$alertes = $alertes ?? [];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-bell me-2"></i>Centre d'Alertes</h4>
            <div class="d-flex gap-2">
                <a href="?page=rapport&action=rupture" class="btn btn-outline-danger"><i class="bi bi-box-seam me-2"></i>Ruptures</a>
                <a href="?page=rapport&action=expiration" class="btn btn-outline-warning"><i class="bi bi-calendar-x me-2"></i>Expirations</a>
            </div>
        </div>

        <div class="row g-4">
            <?php foreach ($alertes as $a): 
                $type = $a['type_alerte'] ?? 'info';
                $icon = $type == 'rupture' ? 'exclamation-circle' : ($type == 'expiration' ? 'calendar-x' : 'info-circle');
                $color = $type == 'rupture' ? 'danger' : ($type == 'expiration' ? 'warning' : 'info');
                $statut = $a['statut'] ?? 'nouvelle';
                $dateCreation = $a['date_creation'] ?? date('Y-m-d H:i:s');
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-<?= $color ?> <?= $statut == 'nouvelle' ? 'alert-nouvelle' : '' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="icon-box bg-<?= $color ?> bg-opacity-10 text-<?= $color ?>">
                                    <i class="bi bi-<?= $icon ?>"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?= ucfirst($type) ?></h6>
                                    <p class="mb-1 small"><?= $a['message'] ?? 'Alerte système' ?></p>
                                    <?php if (!empty($a['nom_commercial'])): ?>
                                        <small class="text-muted"><strong><?= $a['nom_commercial'] ?></strong></small><br>
                                        <?php if (isset($a['quantite_stock'])): ?>
                                            <small class="text-danger">Stock: <?= $a['quantite_stock'] ?> / Min: <?= $a['quantite_minimale'] ?? '-' ?></small>
                                        <?php endif; ?>
                                        <?php if (!empty($a['date_expiration'])): ?>
                                            <small class="text-warning d-block">Exp: <?= date('d/m/Y', strtotime($a['date_expiration'])) ?></small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <span class="badge bg-<?= $statut == 'nouvelle' ? 'danger' : ($statut == 'resolue' ? 'success' : 'secondary') ?>">
                                <?= ucfirst($statut) ?>
                            </span>
                        </div>
                        <div class="mt-3 d-flex justify-content-between align-items-center">
                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($dateCreation)) ?></small>
                            <?php if ($statut == 'nouvelle' && !empty($a['id'])): ?>
                            <a href="?page=alert&action=resoudre&id=<?= $a['id'] ?>" class="btn btn-sm btn-success">
                                <i class="bi bi-check-lg me-1"></i>Résoudre
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <?php if (empty($alertes)): ?>
            <div class="col-12 text-center py-5">
                <i class="bi bi-check-circle fs-1 text-success"></i>
                <h5 class="mt-3">Aucune alerte active</h5>
                <p class="text-muted">Tout va bien !</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>