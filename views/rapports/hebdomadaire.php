<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-calendar-week me-2"></i><?= $title ?></h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="rapport">
                    <input type="hidden" name="action" value="hebdomadaire">
                    <input type="date" name="debut" class="form-control" value="<?= $debut ?>">
                    <input type="date" name="fin" class="form-control" value="<?= $fin ?>">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
                <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Détail journalier</div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Jour</th>
                            <th class="text-center">Nb Ventes</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($donnees as $d): 
                            $jourSemaine = ['Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'];
                            $numJour = date('w', strtotime($d['date']));
                        ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($d['date'])) ?></td>
                            <td><?= $jourSemaine[$numJour] ?></td>
                            <td class="text-center"><?= $d['nb_ventes'] ?></td>
                            <td class="text-end fw-bold"><?= number_format($d['total'], 2) ?> CDF</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>