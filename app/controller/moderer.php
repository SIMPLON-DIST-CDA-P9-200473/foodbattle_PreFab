<?php

require __DIR__ . '/../bootstrap.php';
require_admin(); // modérer un avis est une action admin
check_post();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$decision = $_POST['decision'] ?? '';

// Liste blanche : seules ces deux valeurs peuvent être écrites en base.
// Sans ce contrôle, quelqu'un pourrait modifier le formulaire et envoyer n'importe quel statut.
if (!$id || !in_array($decision, ['publie', 'refuse'], true)) {
    http_response_code(400);
    exit('Requête invalide.');
}

// false = l'avis n'existe pas ou a déjà été modéré
if (!moderateRating($db, $id, $decision)) {
    header('Location: /controller/moderation.php?error=notfound');
    exit;
}

header('Location: /controller/moderation.php?done=' . $decision);
exit;
