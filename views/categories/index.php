<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$canCreate = $_SESSION['user_permissions']['categorie']['create'] ?? false;
$canEdit = $_SESSION['user_permissions']['categorie']['edit'] ?? false;
$canDelete = $_SESSION['user_permissions']['categorie']['delete'] ?? false;
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-tags me-2"></i>Gestion des Catégories</h4>
            <?php if ($canCreate): ?>
            <a href="?page=categorie&action=nouveau" class="btn btn-primary">
                <i class="bi bi-plus-lg me-2"></i>Nouvelle Catégorie
            </a>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Médicaments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $c): 
                            $count = (new Categorie())->countMedicaments($c['id']);
                        ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($c['nom']) ?></td>
                            <td><?= htmlspecialchars($c['description'] ?? '') ?></td>
                            <td><span class="badge bg-info"><?= $count ?></span></td>
                            <td>
                                <?php if ($canEdit): ?>
                                <a href="?page=categorie&action=edit&id=<?= $c['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                <?php endif; ?>
                                <?php if ($canDelete): ?>
                                <a href="?page=categorie&action=supprimer&id=<?= $c['id'] ?>" class="btn btn-sm btn-danger btn-delete"><i class="bi bi-trash"></i></a>
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