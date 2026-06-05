<?php
$pageTitle = "Gestion des Fournisseurs";
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/sidebar.php';
require_once __DIR__ . '/../templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-truck"></i> Fournisseurs</h2>
            <a href="index.php?page=fournisseur&action=create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nouveau
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="index.php" class="d-flex">
                    <input type="hidden" name="page" value="fournisseur">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="q" class="form-control me-2" placeholder="Rechercher..." value="<?= $_GET['q'] ?? '' ?>">
                    <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <span class="badge bg-secondary">Total: <?= $total ?></span>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Telephone</th>
                                <th>Adresse</th>
                                <th>Pays</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fournisseurs as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><strong><?= htmlspecialchars($row['nom']) ?></strong></td>
                                <td><?= htmlspecialchars($row['telephone'] ?? '-') ?></td>
                                <td><?= nl2br(htmlspecialchars($row['adresse'] ?? '-')) ?></td>
                                <td><?= htmlspecialchars($row['pays'] ?? '-') ?></td>
                                <td>
                                    <a href="index.php?page=fournisseur&action=view&id=<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Voir">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="index.php?page=fournisseur&action=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="index.php?page=fournisseur&action=delete&id=<?= $row['id'] ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Supprimer ce fournisseur ?')"
                                       title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>