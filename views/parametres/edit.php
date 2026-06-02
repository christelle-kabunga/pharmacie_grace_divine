<?php
$pageTitle = "Modifier Paramètre";
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-cog"></i> Modifier Paramètre</h2>
        <a href="index.php?page=parametres" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="index.php?page=parametres&action=edit&id=<?= $parametre['id'] ?>">
                <div class="mb-3">
                    <label class="form-label">Clé</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($parametre['cle']) ?>" disabled>
                    <div class="form-text">La clé ne peut pas être modifiée</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Valeur</label>
                    <input type="text" name="valeur" class="form-control" value="<?= htmlspecialchars($parametre['valeur']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($parametre['description'] ?? '') ?></textarea>
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