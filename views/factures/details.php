<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$facture = $facture ?? [];
$details = $details ?? [];
$devise = $facture['devise'] ?? 'CDF';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h4 class="fw-bold"><i class="bi bi-receipt me-2"></i>Facture #<?= $facture['numero_facture'] ?></h4>
            <div class="d-flex gap-2">
                <a href="?page=facture" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Retour</a>
                <button onclick="window.print()" class="btn btn-primary"><i class="bi bi-printer me-2"></i>Imprimer</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-5">
                <!-- En-tête -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h3 class="fw-bold text-primary">PHARMACIE CENTRALE</h3>
                        <p class="mb-1">123 Rue Principale</p>
                        <p class="mb-1">Tél: +243 000 000 000</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h4 class="fw-bold">FACTURE</h4>
                        <p class="mb-1"><strong>N°:</strong> <?= $facture['numero_facture'] ?></p>
                        <p class="mb-1"><strong>Date:</strong> <?= date('d/m/Y', strtotime($facture['date_facture'])) ?></p>
                        <p><strong>Devise:</strong> <?= htmlspecialchars($devise) ?></p>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Info client et vendeur -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="fw-bold text-muted text-uppercase">Client</h6>
                        <p class="mb-1 fw-bold"><?= $facture['nom_client'] ?: 'Client comptant' ?></p>
                        <p class="mb-1">Tél: <?= $facture['telephone_client'] ?: 'N/A' ?></p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="fw-bold text-muted text-uppercase">Vendeur</h6>
                        <p class="mb-1 fw-bold"><?= $facture['vendeur_prenom'] ?> <?= $facture['vendeur_nom'] ?></p>
                        <p class="mb-1">N° Vente: <?= $facture['numero_vente'] ?></p>
                    </div>
                </div>

                <!-- Tableau -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Médicament</th>
                                <th class="text-center">Qté</th>
                                <th class="text-end">Prix Unitaire</th>
                                <th class="text-end">Remise</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($details as $d): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td>
                                    <div class="fw-bold"><?= $d['nom_generique'] ?></div>
                                    <small class="text-muted"><?= $d['dosage'] ?></small>
                                </td>
                                <td class="text-center"><?= $d['quantite'] ?></td>
                                <td class="text-end"><?= number_format($d['prix_unitaire'], 2) ?></td>
                                <td class="text-end"><?= number_format($d['remise_ligne'], 2) ?></td>
                                <td class="text-end fw-bold"><?= number_format($d['total_ligne'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Totaux -->
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-end">Sous-total:</td>
                                <td class="text-end fw-bold"><?= number_format($facture['sous_total'] ?? 0, 2) ?> <?= htmlspecialchars($devise) ?></td>
                            </tr>
                            <tr>
                                <td class="text-end">Remise:</td>
                                <td class="text-end"><?= number_format($facture['remise_totale'] ?? 0, 2) ?> <?= htmlspecialchars($devise) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr class="my-4">
                <div class="text-center text-muted">
                    <p class="mb-1">Merci de votre confiance !</p>
                    <small>Facture émise par <?= $facture['vendeur_prenom'] ?> <?= $facture['vendeur_nom'] ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>