<?php
echo "<h2>Diagnostic PharmaGest</h2>";

$checks = [
    'config/Database.php' => file_exists('config/Database.php'),
    'controllers/DashboardController.php' => file_exists('controllers/DashboardController.php'),
    'controllers/AuthController.php' => file_exists('controllers/AuthController.php'),
    'models/User.php' => file_exists('models/User.php'),
    'views/templates/header.php' => file_exists('views/templates/header.php'),
    'views/templates/sidebar.php' => file_exists('views/templates/sidebar.php'),
    'views/templates/navbar.php' => file_exists('views/templates/navbar.php'),
    'views/templates/footer.php' => file_exists('views/templates/footer.php'),
    'views/dashboard/index.php' => file_exists('views/dashboard/index.php'),
    'views/auth/login.php' => file_exists('views/auth/login.php'),
    'assets/css/bootstrap.min.css' => file_exists('assets/css/bootstrap.min.css'),
    'assets/js/bootstrap.bundle.min.js' => file_exists('assets/js/bootstrap.bundle.min.js'),
    'assets/css/custom.css' => file_exists('assets/css/custom.css'),
    'assets/js/custom.js' => file_exists('assets/js/custom.js'),
    '.htaccess' => file_exists('.htaccess'),
];

echo "<table border='1' cellpadding='10'>";
foreach ($checks as $file => $exists) {
    $color = $exists ? 'green' : 'red';
    $status = $exists ? 'OK' : 'MANQUANT';
    echo "<tr><td>$file</td><td style='color:$color;font-weight:bold;'>$status</td></tr>";
}
echo "</table>";

if (in_array(false, $checks)) {
    echo "<h3 style='color:red;'>Fichiers manquants détectés !</h3>";
} else {
    echo "<h3 style='color:green;'>Tous les fichiers sont présents !</h3>";
    echo "<a href='index.php'>Lancer l'application</a>";
}
?>