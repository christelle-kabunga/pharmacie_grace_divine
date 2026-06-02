<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-plus-circle me-2"></i>Nouveau Médicament</h4>
            <a href="?page=medicament" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Retour</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="?page=medicament&action=enregistrer">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom générique *</label>
                                    <input type="text" name="nom_generique" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Catégorie *</label>
                                    <select name="categorie_id" class="form-select" required>
                                        <option value="">Choisir...</option>
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?= $c['id'] ?>"><?= $c['nom'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fournisseur</label>
                                    <select name="fournisseur_id" class="form-select">
                                        <option value="">Choisir...</option>
                                        <?php foreach ($fournisseurs as $f): ?>
                                            <option value="<?= $f['id'] ?>"><?= $f['nom'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage" class="form-control" placeholder="ex: 500mg">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix d'achat *</label>
                                    <div class="input-group">
                                        <input type="number" name="prix_achat" class="form-control" step="0.01" required>
                                        <span class="input-group-text">CDF</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix de vente *</label>
                                    <div class="input-group">
                                        <input type="number" name="prix_vente" class="form-control" step="0.01" required>
                                        <span class="input-group-text">CDF</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Stock minimum</label>
                                    <input type="number" name="quantite_minimale" class="form-control" value="10" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Stock maximum</label>
                                    <input type="number" name="quantite_maximale" class="form-control" value="1000" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Emplacement</label>
                                    <input type="text" name="emplacement" class="form-control" placeholder="ex: Étagère A-12">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Date d'expiration *</label>
                                    <input type="date" name="date_expiration" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de fabrication</label>
                                    <input type="date" name="date_fabrication" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Numéro de lot</label>
                                    <input type="text" name="numero_lot" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Description</label>
                                    <textarea name="description" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Contre-indications</label>
                                    <textarea name="contre_indication" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Effets secondaires</label>
                                    <textarea name="effets_secondaires" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="reset" class="btn btn-outline-secondary me-2">Réinitialiser</button>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-check-lg me-2"></i>Enregistrer
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