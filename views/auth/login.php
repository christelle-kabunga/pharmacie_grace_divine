<?php require 'views/templates/header.php'; ?>

<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="card shadow-lg" style="width: 400px; border-radius: 20px; border: none;">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <div class="icon-box bg-primary text-white mx-auto mb-3" style="width: 70px; height: 70px; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-capsule-pill fs-2"></i>
                </div>
                <h3 class="fw-bold">Pharmacie grace divine</h3>
                <p class="text-muted">Système de Gestion de Pharmacie</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <form method="POST" action="?page=auth&action=login">
                <div class="mb-3">
                    <label class="form-label">Nom d'utilisateur</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="admin" required autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="password" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Connexion
                </button>
            </form>
            
            <div class="text-center mt-3">
                <small class="text-muted">Défaut: admin / password</small>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>