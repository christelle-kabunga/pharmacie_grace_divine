<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - Pharmacie grace divie' : 'Pharmacie grace divine' ?></title>
    
    <!-- Bootstrap 5 Local -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Bootstrap Icons (local). If fonts are missing, we apply a safe fallback to avoid PHP/Font errors -->
    <?php
    $biCssPath = __DIR__ . '/../../assets/css/bootstrap-icons.css';
    $biFontLocal = __DIR__ . '/../../assets/fonts/bootstrap-icons.woff2';
    if (file_exists($biCssPath)) {
        echo '<link rel="stylesheet" href="assets/css/bootstrap-icons.css">';
        if (!file_exists($biFontLocal)) {
            // fonts missing — add a tiny fallback to avoid ugly glyphs
            echo "<style>.bi::before,[class^=\"bi-\"]::before,[class*=' bi-']::before{content:'' !important}</style>";
            if (session_status() === PHP_SESSION_ACTIVE) {
                $_SESSION['error'] = 'Fichiers de police Bootstrap Icons manquants localement. Placez les .woff/.woff2 dans assets/fonts/.';
            }
        }
    } else {
        // CSS missing; silently continue but warn
        echo '<!-- bootstrap-icons.css absent — place it in assets/css/ -->';
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['error'] = 'Feuille de styles bootstrap-icons.css absente dans assets/css/.';
        }
    }
    ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
    
    <!-- Styles Personnalisés -->
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>