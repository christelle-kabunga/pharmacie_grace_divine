<?php
$pageTitle = "Paramètres du Système";
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-cog"></i> Paramètres du Système</h2>
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

    <div class="row">
        <!-- Paramètres Généraux -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Informations Pharmacie</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=parametres&action=updateGeneraux">
                        <div class="mb-3">
                            <label class="form-label">Nom de la Pharmacie</label>
                            <input type="text" name="nom_pharmacie" class="form-control" value="<?= htmlspecialchars($generaux['nom_pharmacie']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <textarea name="adresse_pharmacie" class="form-control" rows="2"><?= htmlspecialchars($generaux['adresse_pharmacie']) ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone_pharmacie" class="form-control" value="<?= htmlspecialchars($generaux['telephone_pharmacie']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email_pharmacie" class="form-control" value="<?= htmlspecialchars($generaux['email_pharmacie']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Devise par défaut</label>
                            <select name="devise_defaut" class="form-select">
                                <option value="CDF" <?= $generaux['devise_defaut'] == 'CDF' ? 'selected' : '' ?>>CDF (Franc Congolais)</option>
                                <option value="USD" <?= $generaux['devise_defaut'] == 'USD' ? 'selected' : '' ?>>USD (Dollar US)</option>
                                <option value="EUR" <?= $generaux['devise_defaut'] == 'EUR' ? 'selected' : '' ?>>EUR (Euro)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Paramètres d'Alertes -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-bell"></i> Alertes & Seuils</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=parametres&action=updateAlertes">
                        <div class="mb-3">
                            <label class="form-label">Seuil minimum de stock</label>
                            <input type="number" name="seuil_alerte_stock" class="form-control" value="<?= htmlspecialchars($alertes['seuil_alerte_stock']) ?>" min="1">
                            <div class="form-text">Alerte quand stock ≤ cette valeur</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jours avant expiration</label>
                            <input type="number" name="jours_alerte_expiration" class="form-control" value="<?= htmlspecialchars($alertes['jours_alerte_expiration']) ?>" min="1">
                            <div class="form-text">Alerte avant expiration (en jours)</div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Paramètres Interface -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-desktop"></i> Interface</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=parametres&action=updateInterface">
                        <div class="mb-3">
                            <label class="form-label">Thème par défaut</label>
                            <select name="theme_defaut" class="form-select">
                                <option value="light" <?= $interface['theme_defaut'] == 'light' ? 'selected' : '' ?>>Clair</option>
                                <option value="dark" <?= $interface['theme_defaut'] == 'dark' ? 'selected' : '' ?>>Sombre</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste complète des paramètres -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Tous les Paramètres</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Clé</th>
                            <th>Valeur</th>
                            <th>Description</th>
                            <th>Dernière mise à jour</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($parametres as $row): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($row['cle']) ?></code></td>
                            <td><?= htmlspecialchars($row['valeur']) ?></td>
                            <td><?= htmlspecialchars($row['description'] ?? '-') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($row['updated_at'])) ?></td>
                            <td>
                                <a href="index.php?page=parametres&action=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
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

<?php require_once __DIR__ . '/../templates/footer.php'; ?>