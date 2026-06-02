<?php
// Ce fichier est un point de sortie simple, mais la déconnexion est gérée par le contrôleur
// Redirection vers la page de connexion
header('Location: ?page=auth&action=login');
exit;
?>