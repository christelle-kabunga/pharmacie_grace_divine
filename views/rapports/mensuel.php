<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-calendar-month me-2"></i><?= $title ?></h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="rapport">
                    <input type="hidden" name="action" value="mensuel">
                    <select name="mois" class="form-select">
                        <?php for($i=1; $i<=12; $i++): ?>
                            <option value="<?= $i ?>" <?= $mois == $i ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$i,1)) ?></option>
                        <?php endfor; ?>
                    </select>
                    <select name="annee" class="form-select">
                        <?php for($i=date('Y'); $i>=date('Y')-5; $i--): ?>
                            <option value="<?= $i ?>" <?= $annee == $i ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
                <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">Évolution</div>
                    <div class="card-body">
                        <canvas id="mensuelChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Top Vendeurs</div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach ($vendeurs as $v): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?= $v['prenom'] ?> <?= $v['nom'] ?></div>
                                    <small class="text-muted"><?= $v['nb_ventes'] ?> ventes</small>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?= number_format($v['total'], 0) ?> CDF</span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Détail par jour</div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Jour</th>
                            <th class="text-center">Nb Ventes</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalMois = 0; foreach ($donnees as $d): $totalMois += $d['total']; ?>
                        <tr>
                            <td><?= $d['jour'] ?></td>
                            <td class="text-center"><?= $d['nb_ventes'] ?></td>
                            <td class="text-end fw-bold"><?= number_format($d['total'], 2) ?> CDF</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>TOTAL</th>
                            <th class="text-center"><?= array_sum(array_column($donnees, 'nb_ventes')) ?></th>
                            <th class="text-end"><?= number_format($totalMois, 2) ?> CDF</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('mensuelChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_map(fn($d) => 'J' . $d['jour'], $donnees)) ?>,
        datasets: [{
            label: 'Ventes (CDF)',
            data: <?= json_encode(array_map(fn($d) => $d['total'], $donnees)) ?>,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.3,
            fill: true
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