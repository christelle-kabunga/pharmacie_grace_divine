<?php 
require 'views/templates/header.php';
require 'views/templates/sidebar.php';
require 'views/templates/navbar.php';
?>

<div class="main-content">
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-graph-up me-2"></i>Centre de Rapports</h4>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=journalier" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-calendar-day fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Journalier</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=hebdomadaire" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-success bg-opacity-10 text-success mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-calendar-week fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Hebdomadaire</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=mensuel" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-info bg-opacity-10 text-info mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-calendar-month fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Mensuel</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=annuel" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-warning bg-opacity-10 text-warning mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-calendar-year fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Annuel</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=global" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-globe fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Global</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=rupture" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-secondary bg-opacity-10 text-secondary mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-box-seam fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Ruptures</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="?page=rapport&action=expiration" class="text-decoration-none">
                    <div class="card h-100">
                        <div class="card-body text-center p-4">
                            <div class="icon-box bg-dark bg-opacity-10 text-dark mx-auto mb-3" style="width:60px;height:60px;border-radius:15px;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-calendar-x fs-2"></i>
                            </div>
                            <h5 class="fw-bold">Expirations</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php require 'views/templates/footer.php'; ?>