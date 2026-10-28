<?php

// Supprime un plat puis revient au dashboard avec un message.
require __DIR__ . '/bootstrap.php';
require_admin(); // supprimer un plat est une action admin
check_post();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// false = le plat n'existait pas (ou plus). Ses votes et avis partent avec lui (ON DELETE CASCADE).
if (!$id || !deleteDish($db, $id)) {
    header('Location: /dashboard.php?error=notfound');
    exit;
}

header('Location: /dashboard.php?deleted=1');
exit;
