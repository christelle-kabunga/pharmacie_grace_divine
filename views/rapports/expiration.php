<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-calendar-x me-2"></i><?= $title ?></h4>
            <div class="d-flex gap-2">
                <form method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="rapport">
                    <input type="hidden" name="action" value="expiration">
                    <select name="jours" class="form-select">
                        <option value="30" <?= ($jours ?? 90) == 30 ? 'selected' : '' ?>>30 jours</option>
                        <option value="60" <?= ($jours ?? 90) == 60 ? 'selected' : '' ?>>60 jours</option>
                        <option value="90" <?= ($jours ?? 90) == 90 ? 'selected' : '' ?>>90 jours</option>
                    </select>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i></button>
                </form>
                <button onclick="window.print()" class="btn btn-outline-secondary"><i class="bi bi-printer"></i></button>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">Médicaments proches de l'expiration</div>
            <div class="card-body">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th>Nom Générique</th>
                            <th>Catégorie</th>
                            <th class="text-center">Stock</th>
                            <th>Expiration</th>
                            <th class="text-center">Jours restants</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($perimes as $p): ?>
                        <tr class="<?= $p['jours_restant'] < 0 ? 'table-danger' : ($p['jours_restant'] <= 30 ? 'table-warning' : '') ?>">
                            <td class="fw-bold"><?= htmlspecialchars($p['nom_generique']) ?></td>
                            <td><?= $p['categorie'] ?></td>
                            <td class="text-center"><?= $p['quantite_stock'] ?></td>
                            <td><?= date('d/m/Y', strtotime($p['date_expiration'])) ?></td>
                            <td class="text-center">
                                <?php if ($p['jours_restant'] < 0): ?>
                                    <span class="badge bg-danger">PÉRIMÉ</span>
                                <?php else: ?>
                                    <span class="badge bg-<?= $p['jours_restant'] <= 30 ? 'warning text-dark' : 'success' ?>"><?= $p['jours_restant'] ?> j</span>
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