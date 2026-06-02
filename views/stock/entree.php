<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-box-arrow-in-down me-2"></i>Entrée de Stock</h4>
            <a href="?page=stock" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Retour</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="?page=stock&action=enregistrerEntree">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Médicament *</label>
                                    <select name="medicament_id" class="form-select" required>
                                        <option value="">Sélectionner...</option>
                                        <?php foreach ($medicaments as $m): ?>
                                            <option value="<?= $m['id'] ?>"><?= $m['nom_generique'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Fournisseur</label>
                                    <select name="fournisseur_id" class="form-select">
                                        <option value="">Sélectionner...</option>
                                        <?php foreach ($fournisseurs as $f): ?>
                                            <option value="<?= $f['id'] ?>"><?= $f['nom'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Quantité *</label>
                                    <input type="number" name="quantite" class="form-control" min="1" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix d'achat unitaire *</label>
                                    <input type="number" name="prix_achat" class="form-control" step="0.01" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Prix de vente unitaire *</label>
                                    <input type="number" name="prix_vente" class="form-control" step="0.01" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date de fabrication</label>
                                    <input type="date" name="date_fabrication" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date d'expiration *</label>
                                    <input type="date" name="date_expiration" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Numéro de lot</label>
                                    <input type="text" name="numero_lot" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
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