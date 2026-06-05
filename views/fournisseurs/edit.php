<?php
$pageTitle = "Modifier Fournisseur";
require_once __DIR__ . '/../layouts/header.php';

$fournisseur = $fournisseur ?? [
    'id' => '',
    'nom' => '',
    'telephone' => '',
    'pays' => '',
    'statut' => 'inactif'
];
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-truck"></i> Modifier Fournisseur</h2>
        <a href="index.php?page=fournisseurs" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="index.php?page=fournisseurs&action=edit&id=<?= $fournisseur['id'] ?>">
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($fournisseur['nom']) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($fournisseur['telephone'] ?? '') ?>">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" class="form-control" value="<?= htmlspecialchars($fournisseur['pays'] ?? '') ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Statut</label>
                        <select name="statut" class="form-select">
                            <option value="actif" <?= $fournisseur['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                            <option value="inactif" <?= $fournisseur['statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="actif" <?= $fournisseur['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                        <option value="inactif" <?= $fournisseur['statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>