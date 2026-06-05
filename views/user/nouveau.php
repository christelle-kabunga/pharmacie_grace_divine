<?php
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <h4 class="fw-bold mb-4"><i class="bi bi-person-plus me-2"></i>Nouvel Employé</h4>

        <div class="card">
            <div class="card-body">
                <form method="post" action="?page=user&action=enregistrer">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prénom</label>
                            <input class="form-control" name="prenom" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nom</label>
                            <input class="form-control" name="nom" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" name="email" type="email" />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Téléphone</label>
                            <input class="form-control" name="telephone" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Rôle</label>
                            <select class="form-control" name="role">
                                <option value="vendeur">Vendeur</option>
                                <option value="comptable">Comptable</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Username</label>
                            <input class="form-control" name="username" required />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input class="form-control" name="password" type="password" required />
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-primary" type="submit">Enregistrer</button>
                        <a class="btn btn-secondary" href="?page=user">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>
