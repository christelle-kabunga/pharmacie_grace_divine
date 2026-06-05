<?php
$pageTitle = "Gestion des Fournisseurs";
require_once __DIR__ . '/../layouts/header.php';

$fournisseurs = $fournisseurs ?? [];
$total = $total ?? count($fournisseurs);
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck"></i> Fournisseurs</h2>
        <a href="index.php?page=fournisseurs&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau
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
                <input type="hidden" name="page" value="fournisseurs">
                <input type="hidden" name="action" value="search">
                <input type="text" name="q" class="form-control me-2" placeholder="Rechercher..." value="<?= $_GET['q'] ?? '' ?>">
                <button type="submit" class="btn btn-outline-secondary"><i class="fas fa-search"></i></button>
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
                            <th>Contact</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Ville</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fournisseurs as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><strong><?= htmlspecialchars($row['nom']) ?></strong></td>
                            <td><?= htmlspecialchars($row['contact'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['telephone'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['email'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($row['ville'] ?? '-') ?></td>
                            <td>
                                <?php if ($row['statut'] == 'actif'): ?>
                                    <span class="badge bg-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="index.php?page=fournisseurs&action=view&id=<?= $row['id'] ?>" class="btn btn-sm btn-info" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?page=fournisseurs&action=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?page=fournisseurs&action=toggle&id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-<?= $row['statut'] == 'actif' ? 'secondary' : 'success' ?>" 
                                   title="<?= $row['statut'] == 'actif' ? 'Désactiver' : 'Activer' ?>">
                                    <i class="fas fa-power-off"></i>
                                </a>
                                <a href="index.php?page=fournisseurs&action=delete&id=<?= $row['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Supprimer ce fournisseur ?')"
                                   title="Supprimer">
                                    <i class="fas fa-trash"></i>
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

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>