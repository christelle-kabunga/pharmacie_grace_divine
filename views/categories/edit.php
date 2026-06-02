<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-pencil-square me-2"></i>Modifier Catégorie</h4>
            <a href="?page=categorie" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Retour</a>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="?page=categorie&action=update&id=<?= $categorie['id'] ?>">
                            <div class="mb-3">
                                <label class="form-label">Nom *</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($categorie['nom']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($categorie['description'] ?? '') ?></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-lg me-2"></i>Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>