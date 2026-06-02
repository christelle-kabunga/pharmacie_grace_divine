<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifier <?= htmlspecialchars($medicament['nom_generique']) ?></h4>
            <a href="?page=medicament" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Retour</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="?page=medicament&action=update&id=<?= $medicament['id'] ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom générique *</label>
                                    <input type="text" name="nom_generique" class="form-control" value="<?= htmlspecialchars($medicament['nom_generique']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Catégorie *</label>
                                    <select name="categorie_id" class="form-select" required>
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?= $c['id'] ?>" <?= ($medicament['categorie_id'] == $c['id']) ? 'selected' : '' ?>><?= $c['nom'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fournisseur</label>
                                    <select name="fournisseur_id" class="form-select">
                                        <option value="">Aucun</option>
                                        <?php foreach ($fournisseurs as $f): ?>
                                            <option value="<?= $f['id'] ?>" <?= ($medicament['fournisseur_id'] == $f['id']) ? 'selected' : '' ?>><?= $f['nom'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage" class="form-control" value="<?= htmlspecialchars($medicament['dosage'] ?? '') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix d'achat</label>
                                    <input type="number" name="prix_achat" class="form-control" step="0.01" value="<?= $medicament['prix_achat'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix de vente</label>
                                    <input type="number" name="prix_vente" class="form-control" step="0.01" value="<?= $medicament['prix_vente'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Stock minimum</label>
                                    <input type="number" name="quantite_minimale" class="form-control" value="<?= $medicament['quantite_minimale'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Emplacement</label>
                                    <input type="text" name="emplacement" class="form-control" value="<?= htmlspecialchars($medicament['emplacement'] ?? '') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date d'expiration</label>
                                    <input type="date" name="date_expiration" class="form-control" value="<?= $medicament['date_expiration'] ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Numéro de lot</label>
                                    <input type="text" name="numero_lot" class="form-control" value="<?= htmlspecialchars($medicament['numero_lot'] ?? '') ?>">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($medicament['description'] ?? '') ?></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-lg me-2"></i>Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>