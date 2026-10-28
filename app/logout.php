<?php

require __DIR__ . '/bootstrap.php';
// en POST avec jeton CSRF : un simple lien permettrait à n'importe quel site de déconnecter l'admin
check_post();

$_SESSION = [];
session_destroy();
header('Location: /');
exit;
