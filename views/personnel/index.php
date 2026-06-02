<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-person-badge me-2"></i>Gestion du Personnel</h4>
            <a href="?page=personnel&action=nouveau" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Nouvel Employé
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Contact</th>
                            <th>Poste</th>
                            <th>Rôle</th>
                            <th>Salaire</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($personnel as $p): ?>
                        <tr>
                            <td><span class="badge bg-light text-dark"><?= $p['matricule'] ?></span></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:35px;height:35px;">
                                        <?= strtoupper(substr($p['prenom'], 0, 1) . substr($p['nom'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?= $p['prenom'] ?> <?= $p['nom'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= $p['telephone'] ?: 'N/A' ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $p['poste'])) ?></td>
                            <td><span class="badge bg-<?= $p['role'] == 'super_admin' ? 'danger' : ($p['role'] == 'admin' ? 'warning' : 'info') ?>"><?= ucfirst($p['role']) ?></span></td>
                            <td><?= number_format($p['salaire'], 2) ?> CDF</td>
                            <td><span class="badge bg-<?= $p['statut'] == 'actif' ? 'success' : 'secondary' ?>"><?= ucfirst($p['statut']) ?></span></td>
                            <td>
                                <a href="?page=personnel&action=edit&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <a href="?page=personnel&action=delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></a>
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