<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require 'includes/functions.php';
require_admin(); // modérer un avis est une action admin
require 'includes/db.php';
check_post();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$decision = $_POST['decision'] ?? '';

// Liste blanche : seules ces deux valeurs peuvent être écrites en base.
// Sans ce contrôle, quelqu'un pourrait modifier le formulaire et envoyer n'importe quel statut.
if (!$id || !in_array($decision, ['publie', 'refuse'], true)) {
    http_response_code(400);
    exit('Requête invalide.');
}

$s = $db->prepare("UPDATE ratings SET status = ? WHERE id = ? AND status = 'en_attente'");
$s->execute([$decision, $id]);

// Aucune ligne modifiée = l'avis n'existe pas ou a déjà été modéré
if ($s->rowCount() === 0) {
    header('Location: /moderation.php?error=notfound');
    exit;
}

header('Location: /moderation.php?done=' . $decision);
exit;
