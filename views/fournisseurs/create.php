<?php
$pageTitle = "Nouveau Fournisseur";
require_once __DIR__ . '/../templates/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-truck"></i> Nouveau Fournisseur</h2>
        <a href="index.php?page=fournisseur" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="index.php?page=fournisseur&action=create">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nom <span class="text-danger">*</span></label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <textarea name="adresse" class="form-control" rows="2"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" class="form-control" value="RDC">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">NIF</label>
                        <input type="text" name="nif" class="form-control">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="actif" selected>Actif</option>
                        <option value="inactif">Inactif</option>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>