<?php
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';

$title = 'Modifier Employé';
$user = $user ?? [
    'id' => 0,
    'prenom' => '',
    'nom' => '',
    'email' => '',
    'telephone' => '',
    'role' => 'vendeur',
    'username' => '',
    'statut' => 'actif'
];
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-pencil me-2"></i>Modifier Employé</h4>

        <div class="card">
            <div class="card-body">
                <form method="post" action="?page=user&action=update&id=<?= $user['id'] ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input class="form-control" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Rôle</label>
                            <select class="form-control" name="role">
                                <option value="vendeur" <?= $user['role'] == 'vendeur' ? 'selected' : '' ?>>Vendeur</option>
                                <option value="comptable" <?= $user['role'] == 'comptable' ? 'selected' : '' ?>>Comptable</option>
                                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Username</label>
                            <input class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Statut</label>
                            <select class="form-control" name="statut">
                                <option value="actif" <?= $user['statut'] == 'actif' ? 'selected' : '' ?>>Actif</option>
                                <option value="inactif" <?= $user['statut'] == 'inactif' ? 'selected' : '' ?>>Inactif</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit">Mettre à jour</button>
                        <a class="btn btn-secondary" href="?page=user">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>
