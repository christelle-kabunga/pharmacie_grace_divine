<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-calendar-year me-2"></i><?= $title ?></h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="rapport">
                    <input type="hidden" name="action" value="annuel">
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
                    <div class="card-header">Ventes par mois</div>
                    <div class="card-body">
                        <canvas id="annuelChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Par catégorie</div>
                    <div class="card-body">
                        <canvas id="categorieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Détail mensuel</div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Mois</th>
                            <th class="text-center">Nb Ventes</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalAnnuel = 0; $moisNoms = ['','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']; foreach ($donnees as $d): $totalAnnuel += $d['total']; ?>
                        <tr>
                            <td><?= $moisNoms[$d['mois']] ?></td>
                            <td class="text-center"><?= $d['nb_ventes'] ?></td>
                            <td class="text-end fw-bold"><?= number_format($d['total'], 2) ?> CDF</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th>TOTAL ANNUEL</th>
                            <th class="text-center"><?= array_sum(array_column($donnees, 'nb_ventes')) ?></th>
                            <th class="text-end"><?= number_format($totalAnnuel, 2) ?> CDF</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('annuelChart').getContext('2d');
const moisLabels = ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'];
const dataMois = <?= json_encode(array_map(fn($d) => $d['mois'], $donnees)) ?>;
const dataTotals = <?= json_encode(array_map(fn($d) => $d['total'], $donnees)) ?>;
const fullData = new Array(12).fill(0);
dataMois.forEach((m, i) => fullData[m-1] = dataTotals[i]);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: moisLabels,
        datasets: [{
            label: 'Ventes (CDF)',
            data: fullData,
            backgroundColor: 'rgba(118, 75, 162, 0.7)',
            borderColor: '#764ba2',
            borderWidth: 1,
            borderRadius: 6
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

const ctxCat = document.getElementById('categorieChart').getContext('2d');
new Chart(ctxCat, {
    type: 'doughnut',
    data: {
        labels: <?= json_encode(array_map(fn($c) => $c['nom'], $categories)) ?>,
        datasets: [{
            data: <?= json_encode(array_map(fn($c) => $c['total'], $categories)) ?>,
            backgroundColor: ['#667eea','#764ba2','#f093fb','#f5576c','#4facfe','#00f2fe','#43e97b','#fa709a']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'right', labels: { boxWidth: 12 } } }
    }
});
</script>

<?php require 'views/templates/footer.php'; ?>