<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$canCreate = $_SESSION['user_permissions']['stock']['create'] ?? false;
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-box-seam me-2"></i>Gestion du Stock</h4>
            <div>
                <?php if ($canCreate): ?>
                <a href="?page=stock&action=entree" class="btn btn-success me-2">
                    <i class="bi bi-plus-lg me-2"></i>Entrée Stock
                </a>
                <?php endif; ?>
                <a href="?page=stock&action=inventaire" class="btn btn-outline-primary">
                    <i class="bi bi-clipboard-check me-2"></i>Inventaire
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Nom Générique</th>
                            <th>Catégorie</th>
                            <th>Stock</th>
                            <th>Min</th>
                            <th>Prix Achat</th>
                            <th>Prix Vente</th>
                            <th>Expiration</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($medicaments as $m): 
                            $joursRestant = $m['date_expiration'] ? (strtotime($m['date_expiration']) - time()) / 86400 : 999;
                        ?>
                        <tr class="<?= $m['quantite_stock'] <= $m['quantite_minimale'] ? 'table-danger' : '' ?>">
                            <td class="fw-bold"><?= htmlspecialchars($m['nom_generique']) ?></td>
                            <td><?= $m['categorie'] ?></td>
                            <td>
                                <span class="badge bg-<?= $m['quantite_stock'] <= $m['quantite_minimale'] ? 'danger' : 'success' ?> rounded-pill">
                                    <?= $m['quantite_stock'] ?>
                                </span>
                            </td>
                            <td><?= $m['quantite_minimale'] ?></td>
                            <td><?= number_format($m['prix_achat'], 2) ?> CDF</td>
                            <td class="fw-bold text-primary"><?= number_format($m['prix_vente'], 2) ?> CDF</td>
                            <td>
                                <?php if ($joursRestant < 0): ?>
                                    <span class="badge bg-danger">PÉRIMÉ</span>
                                <?php elseif ($joursRestant < 30): ?>
                                    <span class="badge bg-warning"><?= ceil($joursRestant) ?>j</span>
                                <?php else: ?>
                                    <?= date('d/m/Y', strtotime($m['date_expiration'])) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?= $m['statut'] == 'actif' ? 'success' : ($m['statut'] == 'epuise' ? 'danger' : 'secondary') ?>">
                                    <?= ucfirst($m['statut']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="?page=medicament&action=edit&id=<?= $m['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
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