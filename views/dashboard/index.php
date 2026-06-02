<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-auto-close"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-auto-close"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-speedometer2 me-2"></i>Tableau de Bord</h4>
            <span class="text-muted"><?= date('l d F Y') ?></span>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Ventes Jour</div>
                                <div class="stat-value text-primary"><?= number_format($stats['ventes_jour'], 2) ?> CDF</div>
                            </div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-cart"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Ventes Mois</div>
                                <div class="stat-value text-success"><?= number_format($stats['ventes_mois'], 2) ?> CDF</div>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-graph-up"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Stock Faible</div>
                                <div class="stat-value text-warning"><?= $stats['stock_faible'] ?></div>
                            </div>
                            <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-exclamation-triangle"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Périmés</div>
                                <div class="stat-value text-danger"><?= $stats['medicaments_perimes'] ?></div>
                            </div>
                            <div class="icon-box bg-danger bg-opacity-10 text-danger"><i class="bi bi-calendar-x"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Transactions</div>
                                <div class="stat-value text-info"><?= $stats['total_ventes_count'] ?></div>
                            </div>
                            <div class="icon-box bg-info bg-opacity-10 text-info"><i class="bi bi-receipt"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-2">
                <div class="card stat-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Médicaments</div>
                                <div class="stat-value text-secondary"><?= $stats['total_medicaments'] ?></div>
                            </div>
                            <div class="icon-box bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-capsule"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">Ventes des 7 derniers jours</div>
                    <div class="card-body">
                        <canvas id="ventesChart" height="120"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">Alertes Récentes</div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($alertes as $alerte): ?>
                            <a href="?page=alert" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="text-<?= $alerte['type_alerte'] == 'rupture' ? 'danger' : 'warning' ?>"><?= ucfirst($alerte['type_alerte']) ?></small>
                                    <small class="text-muted"><?= date('d/m H:i', strtotime($alerte['date_creation'])) ?></small>
                                </div>
                                <p class="mb-1 small"><?= $alerte['message'] ?></p>
                                <?php if ($alerte['nom_generique']): ?>
                                    <small class="text-muted"><?= $alerte['nom_generique'] ?></small>
                                <?php endif; ?>
                            </a>
                            <?php endforeach; ?>
                            <?php if (empty($alertes)): ?>
                                <div class="text-center py-4 text-muted">
                                    <i class="bi bi-check-circle fs-1 text-success"></i>
                                    <p>Aucune alerte</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Top 5 Médicaments</div>
                    <div class="card-body">
                        <canvas id="topMedChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">Dernières Ventes</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Client</th>
                                        <th>Vendeur</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($dernieresVentes as $v): ?>
                                    <tr>
                                        <td><small class="text-muted"><?= $v['numero_vente'] ?></small></td>
                                        <td><?= $v['nom_client'] ?: 'Comptant' ?></td>
                                        <td><?= $v['vendeur_prenom'] ?></td>
                                        <td class="fw-bold"><?= number_format($v['total_final'], 2) ?> <?= $v['devise'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctxVentes = document.getElementById('ventesChart').getContext('2d');
new Chart(ctxVentes, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_map(fn($v) => date('d/m', strtotime($v['date'])), $ventesSemaine)) ?>,
        datasets: [{
            label: 'Ventes (CDF)',
            data: <?= json_encode(array_map(fn($v) => $v['total'], $ventesSemaine)) ?>,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

const ctxTop = document.getElementById('topMedChart').getContext('2d');
new Chart(ctxTop, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($m) => $m['nom_generique'], $topMedicaments)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($m) => $m['total_vendu'], $topMedicaments)) ?>,
            backgroundColor: ['#667eea', '#764ba2', '#f093fb', '#f5576c', '#4facfe']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'right' } } }
});
</script>

<?php require 'views/templates/footer.php'; ?>