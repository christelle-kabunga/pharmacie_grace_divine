<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <button class="btn btn-link text-decoration-none" id="sidebarToggle">
            <i class="bi bi-list fs-4"></i>
        </button>
        
        <div class="d-flex align-items-center gap-3">
            <!-- Thème -->
            <button class="btn btn-link text-decoration-none" id="themeToggle" title="Changer de thème">
                <i class="bi bi-moon-fill fs-5"></i>
            </button>
            
            <!-- Profil -->
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width:35px;height:35px;font-size:14px;">
                        <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                    </div>
                    <span class="d-none d-md-block fw-medium"><?= $_SESSION['user_name'] ?? 'Utilisateur' ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="?page=user&action=profil"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="?page=auth&action=logout"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>