<?php
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$title = 'Mon Profil';
// Défauts si $user manquant
$user = $user ?? ['prenom' => '', 'nom' => '', 'email' => '', 'telephone' => '', 'role' => 'vendeur'];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-person-circle me-2"></i>Mon Profil</h4>

        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nom complet</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user['email']) ?></dd>

                    <dt class="col-sm-3">Téléphone</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user['telephone']) ?></dd>

                    <dt class="col-sm-3">Rôle</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user['role']) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>
