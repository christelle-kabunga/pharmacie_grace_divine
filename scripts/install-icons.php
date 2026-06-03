<?php
// Usage (CLI): php scripts/install-icons.php
// Place the downloaded Bootstrap Icons package files into `import/bootstrap-icons/` before running.

$srcDir = __DIR__ . '/../import/bootstrap-icons';
$fontsDest = __DIR__ . '/../assets/css/fonts';
$cssDest = __DIR__ . '/../assets/css/bootstrap-icons.css';

if (!is_dir($srcDir)) {
    echo "Source folder not found: $srcDir\n";
    echo "Create the folder and extract the bootstrap-icons package there (include .woff/.woff2 and bootstrap-icons.css)\n";
    exit(1);
}

if (!is_dir($fontsDest)) {
    mkdir($fontsDest, 0755, true);
}

$copied = 0;
foreach (glob($srcDir . '/*') as $file) {
    $lower = strtolower($file);
    if (preg_match('/\.woff2?$|bootstrap-icons\.css$/', $lower)) {
        $base = basename($file);
        $dest = $base === 'bootstrap-icons.css' ? $cssDest : ($fontsDest . '/' . $base);
        if (!copy($file, $dest)) {
            echo "Failed to copy $file to $dest\n";
        } else {
            echo "Copied $file -> $dest\n";
            $copied++;
        }
    }
}

if ($copied === 0) {
    echo "No .woff/.woff2 or bootstrap-icons.css files found in $srcDir\n";
    exit(1);
}

echo "Installation complete.\n";
exit(0);
