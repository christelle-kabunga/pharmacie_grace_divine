<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-calendar-day me-2"></i><?= $title ?></h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="rapport">
                    <input type="hidden" name="action" value="journalier">
                    <input type="date" name="date" class="form-control" value="<?= $_GET['date'] ?? date('Y-m-d') ?>">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
                <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="stat-label text-white-50">Total</div>
                        <div class="stat-value"><?= number_format($total, 2) ?> CDF</div>
                        <div class="mt-2"><?= count($ventes) ?> transaction(s)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Détail</div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Heure</th>
                            <th>Client</th>
                            <th>Vendeur</th>
                            <th>Mode</th>
                            <th>Devise</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventes as $v): ?>
                        <tr>
                            <td><?= $v['numero_vente'] ?></td>
                            <td><?= date('H:i', strtotime($v['date_vente'])) ?></td>
                            <td><?= $v['nom_client'] ?: 'Comptant' ?></td>
                            <td><?= $v['vendeur_prenom'] ?> <?= $v['vendeur_nom'] ?></td>
                            <td><?= ucfirst($v['mode_paiement']) ?></td>
                            <td><?= $v['devise'] ?></td>
                            <td class="fw-bold"><?= number_format($v['total_final'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>