<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-globe me-2"></i>Rapport Global</h4>
            <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-4 col-xl">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Total Ventes</div>
                                <div class="stat-value text-primary"><?= number_format($stats['total_ventes'], 2) ?> CDF</div>
                            </div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary"><i class="bi bi-cash-stack"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Transactions</div>
                                <div class="stat-value text-success"><?= number_format($stats['total_ventes_count']) ?></div>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success"><i class="bi bi-receipt"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Valeur Stock</div>
                                <div class="stat-value text-info"><?= number_format($stats['stock_valeur'], 2) ?> CDF</div>
                            </div>
                            <div class="icon-box bg-info bg-opacity-10 text-info"><i class="bi bi-box-seam"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="stat-label">Médicaments</div>
                                <div class="stat-value text-warning"><?= $stats['total_medicaments'] ?></div>
                            </div>
                            <div class="icon-box bg-warning bg-opacity-10 text-warning"><i class="bi bi-capsule"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Tendance 12 mois</div>
            <div class="card-body">
                <canvas id="tendanceChart" height="80"></canvas>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Synthèse</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 border-end">
                        <div class="text-muted mb-2">Panier moyen</div>
                        <div class="fs-4 fw-bold text-primary"><?= $stats['total_ventes_count'] > 0 ? number_format($stats['total_ventes'] / $stats['total_ventes_count'], 2) : '0.00' ?> CDF</div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="text-muted mb-2">Rotation stock</div>
                        <div class="fs-4 fw-bold text-info"><?= $stats['stock_valeur'] > 0 ? number_format($stats['total_ventes'] / $stats['stock_valeur'], 2) : '0.00' ?></div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="text-muted mb-2">Marge estimée</div>
                        <div class="fs-4 fw-bold text-warning"><?= number_format($stats['total_ventes'] * 0.25, 2) ?> CDF</div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-muted mb-2">Médicaments actifs</div>
                        <div class="fs-4 fw-bold text-success"><?= $stats['total_medicaments'] ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('tendanceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_map(fn($t) => date('M Y', strtotime($t['periode'] . '-01')), $tendance)) ?>,
        datasets: [{
            label: 'CA (CDF)',
            data: <?= json_encode(array_map(fn($t) => $t['total'], $tendance)) ?>,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointBackgroundColor: '#667eea'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});
</script>

<?php require 'views/templates/footer.php'; ?>