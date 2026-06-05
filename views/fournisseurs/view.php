<?php
$pageTitle = "Détails du Fournisseur";
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-eye me-2"></i>Détails du Fournisseur</h4>
            <a href="index.php?page=fournisseur" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Retour
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-2">Informations générales</h5>
                        <p><strong>ID :</strong> <?= htmlspecialchars($fournisseur['id']) ?></p>
                        <p><strong>Nom :</strong> <?= htmlspecialchars($fournisseur['nom']) ?></p>
                        <p><strong>Téléphone :</strong> <?= htmlspecialchars($fournisseur['telephone'] ?? '-') ?></p>
                        <p><strong>Pays :</strong> <?= htmlspecialchars($fournisseur['pays'] ?? '-') ?></p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-2">Adresse</h5>
                        <p><strong>Adresse :</strong> <?= nl2br(htmlspecialchars($fournisseur['adresse'] ?? '-')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>