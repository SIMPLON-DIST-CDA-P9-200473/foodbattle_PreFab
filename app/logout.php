<?php if (session_status() === PHP_SESSION_NONE) session_start();require 'includes/functions.php';check_post(); // en POST avec jeton CSRF : un simple lien permettrait à n'importe quel site de déconnecter l'admin

$_SESSION=[];session_destroy();
header('Location: /');exit;
