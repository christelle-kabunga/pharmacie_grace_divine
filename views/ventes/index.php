<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$canCreate = $_SESSION['user_permissions']['vente']['create'] ?? false;
$canDelete = $_SESSION['user_permissions']['vente']['delete'] ?? false;
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-cart me-2"></i>Liste des Ventes</h4>
            <?php if ($canCreate): ?>
            <a href="?page=vente&action=nouveau" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Nouvelle Vente
            </a>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>N° Vente</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Vendeur</th>
                            <th>Total</th>
                            <th>Payé</th>
                            <th>Devise</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ventes as $v): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark"><?= $v['numero_vente'] ?></span></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['date_vente'])) ?></td>
                            <td><?= $v['nom_client'] ?: '<em>Client comptant</em>' ?></td>
                            <td><?= $v['vendeur_prenom'] ?> <?= $v['vendeur_nom'] ?></td>
                            <td class="fw-bold"><?= number_format($v['total_final'], 2) ?> <?= $v['devise'] ?></td>
                            <td><?= number_format($v['montant_paye'], 2) ?></td>
                            <td><?= $v['devise'] ?></td>
                            <td>
                                <a href="?page=vente&action=details&id=<?= $v['id'] ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                                <a href="?page=facture&action=details&id=<?= $v['id'] ?>" class="btn btn-sm btn-secondary"><i class="bi bi-receipt"></i></a>
                                <?php if ($canDelete): ?>
                                <a href="?page=vente&action=annuler&id=<?= $v['id'] ?>" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-x-lg"></i></a>
                                <?php endif; ?>
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