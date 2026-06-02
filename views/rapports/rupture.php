<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-exclamation-triangle me-2"></i><?= $title ?></h4>
            <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
        </div>

        <div class="card">
            <div class="card-header bg-danger text-white">Médicaments en rupture</div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Nom Générique</th>
                            <th>Catégorie</th>
                            <th>Fournisseur</th>
                            <th class="text-center">Stock</th>
                            <th class="text-center">Min</th>
                            <th>Prix Vente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ruptures as $r): ?>
                        <tr class="<?= $r['quantite_stock'] == 0 ? 'table-danger' : 'table-warning' ?>">
                            <td class="fw-bold"><?= htmlspecialchars($r['nom_generique']) ?></td>
                            <td><?= $r['categorie'] ?></td>
                            <td><?= $r['fournisseur'] ?: 'N/A' ?></td>
                            <td class="text-center"><span class="badge bg-danger fs-6"><?= $r['quantite_stock'] ?></span></td>
                            <td class="text-center"><?= $r['quantite_minimale'] ?></td>
                            <td><?= number_format($r['prix_vente'], 2) ?> CDF</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>