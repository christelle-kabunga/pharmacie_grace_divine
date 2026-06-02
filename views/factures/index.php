<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-receipt me-2"></i>Gestion des Factures</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>N° Facture</th>
                            <th>N° Vente</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Vendeur</th>
                            <th>Montant TTC</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($factures as $f): ?>
                        <tr>
                            <td><span class="badge bg-primary"><?= $f['numero_facture'] ?></span></td>
                            <td><small class="text-muted"><?= $f['numero_vente'] ?></small></td>
                            <td><?= date('d/m/Y', strtotime($f['date_facture'])) ?></td>
                            <td><?= $f['nom_client'] ?: '<em>Non spécifié</em>' ?></td>
                            <td><?= $f['vendeur_prenom'] ?> <?= $f['vendeur_nom'] ?></td>
                            <td class="fw-bold text-primary"><?= number_format($f['montant_ttc'], 2) ?></td>
                            <td>
                                <span class="badge bg-<?= $f['statut'] == 'payee' ? 'success' : ($f['statut'] == 'partielle' ? 'warning' : 'secondary') ?>">
                                    <?= ucfirst($f['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="?page=facture&action=details&id=<?= $f['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                <button onclick="window.print()" class="btn btn-sm btn-secondary"><i class="bi bi-printer"></i></button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>