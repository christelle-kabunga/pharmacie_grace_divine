<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$medicaments = $medicaments ?? [];
$taux = $taux ?? [];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-cart-plus me-2"></i>Nouvelle Vente</h4>
            <a href="?page=vente" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Retour</a>
        </div>

        <form method="POST" action="?page=vente&action=enregistrer" id="formVente">
            <div class="row g-4">
                <!-- Client -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">Informations Client</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom du client</label>
                                    <input type="text" name="nom_client" class="form-control" placeholder="Nom du client (optionnel)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="text" name="telephone_client" class="form-control" placeholder="Téléphone (optionnel)">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Mode de Paiement *</label>
                                    <select name="mode_paiement" class="form-select" required>
                                        <option value="especes">Espèces</option>
                                        <option value="carte">Carte Bancaire</option>
                                        <option value="cheque">Chèque</option>
                                        <option value="mobile_money">Mobile Money</option>
                                    </select>
                                </div>
                                <input type="hidden" name="devise" value="CDF">
                            </div>
                        </div>
                    </div>

                    <!-- Lignes de vente -->
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <span>Médicaments</span>
                            <button type="button" class="btn btn-sm btn-primary" onclick="ajouterLigne()">
                                <i class="bi bi-plus-lg me-1"></i>Ajouter
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="tableLignes">
                                    <thead>
                                        <tr>
                                            <th>Médicament</th>
                                            <th style="width: 100px;">Qté</th>
                                            <th style="width: 130px;">Prix U.</th>
                                            <th style="width: 100px;">Remise</th>
                                            <th style="width: 130px;">Total</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="lignesVente"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Récapitulatif -->
                <div class="col-lg-4">
                    <div class="card sticky-top" style="top: 80px; z-index: 100;">
                        <div class="card-header fw-bold">Récapitulatif</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Sous-total:</span>
                                <span class="fw-bold" id="sousTotal">0.00</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Remise globale</label>
                                <input type="number" name="remise_totale" id="remiseGlobale" class="form-control" value="0" step="0.01" onchange="calculerTotal()">
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fs-5">TOTAL:</span>
                                <span class="fs-4 fw-bold text-primary" id="totalFinal">0.00</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Montant payé</label>
                                <input type="number" name="montant_paye" id="montantPaye" class="form-control" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small">Monnaie rendue</label>
                                <input type="text" id="monnaie" class="form-control" readonly>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                <i class="bi bi-check-lg me-2"></i>Valider la vente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <input type="hidden" name="sous_total" id="inputSousTotal" value="0">
        </form>
    </div>
</div>

<script>
const medicaments = <?= json_encode($medicaments) ?>;
const tauxMap = {};
let ligneIndex = 0;

function ajouterLigne() {
    const tbody = document.getElementById('lignesVente');
    const tr = document.createElement('tr');
    tr.className = 'ligne-vente';
    tr.innerHTML = `
        <td>
            <select name="medicaments[${ligneIndex}][id]" class="form-select medicament-select" required onchange="updatePrix(this)">
                <option value="">Choisir...</option>
                ${medicaments.map(m => `<option value="${m.id}" data-prix="${m.prix_vente}" data-stock="${m.quantite_stock}">${m.nom_generique} (Stock: ${m.quantite_stock})</option>`).join('')}
            </select>
        </td>
        <td><input type="number" name="medicaments[${ligneIndex}][quantite]" class="form-control qte" value="1" min="1" onchange="calculerTotal()"></td>
        <td><input type="number" name="medicaments[${ligneIndex}][prix]" class="form-control prix" step="0.01" readonly></td>
        <td><input type="number" name="medicaments[${ligneIndex}][remise]" class="form-control remise" value="0" step="0.01" onchange="calculerTotal()"></td>
        <td class="fw-bold total-ligne align-middle">0.00</td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="this.closest('tr').remove(); calculerTotal();"><i class="bi bi-trash"></i></button></td>
    `;
    tbody.appendChild(tr);
    ligneIndex++;
}

function updatePrix(select) {
    const option = select.selectedOptions[0];
    const prix = option.dataset.prix || 0;
    const tr = select.closest('tr');
    tr.querySelector('.prix').value = prix;
    calculerTotal();
}


// Calcul monnaie
function calculerTotal() {
    let sous = 0;
    document.querySelectorAll('.ligne-vente').forEach(tr => {
        const q = parseFloat(tr.querySelector('.qte').value) || 0;
        const prix = parseFloat(tr.querySelector('.prix').value) || 0;
        const remise = parseFloat(tr.querySelector('.remise').value) || 0;
        const totalLigne = (q * prix) - remise;
        tr.querySelector('.total-ligne').textContent = totalLigne.toFixed(2);
        sous += totalLigne;
    });

    document.getElementById('sousTotal').textContent = sous.toFixed(2);
    document.getElementById('inputSousTotal').value = sous.toFixed(2);

    const remiseGlob = parseFloat(document.getElementById('remiseGlobale').value) || 0;
    const totalFinal = Math.max(0, sous - remiseGlob);
    document.getElementById('totalFinal').textContent = totalFinal.toFixed(2);

    // totals are displayed in local currency (CDF)

    // update monnaie rendu when montant payé present
    const payeInp = document.getElementById('montantPaye');
    const paye = parseFloat(payeInp.value) || 0;
    document.getElementById('monnaie').value = (paye - totalFinal).toFixed(2);
}

// update monnaie on input
document.getElementById('montantPaye').addEventListener('input', function() {
    calculerTotal();
});

// initial calc

// ensure at least one ligne and initial calc
ajouterLigne();
calculerTotal();
</script>

<?php require 'views/templates/footer.php'; ?>