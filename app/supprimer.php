<?php
// Supprime un plat puis revient au dashboard avec un message.
if (session_status() === PHP_SESSION_NONE) session_start();
require 'includes/functions.php';
require_admin(); // supprimer un plat est une action admin
require 'includes/db.php';
check_post();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: /dashboard.php?error=notfound');
    exit;
}

// Les votes et avis du plat sont également supprimés grâce à la contrainte ON DELETE CASCADE de la base de données.
$s = $db->prepare('DELETE FROM dishes WHERE id = ?');
$s->execute([$id]);

// Aucune ligne supprimée = le plat n'existait pas (ou plus)
if ($s->rowCount() === 0) {
    header('Location: /dashboard.php?error=notfound');
    exit;
}

header('Location: /dashboard.php?deleted=1');
exit;