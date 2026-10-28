<?php

require __DIR__ . '/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$dish = $id ? findDishWithEstablishment($db, $id) : null;

if ($dish === null) {
    http_response_code(404);
    render('not_found', [
        'title' => 'Le plat',
        'backUrl' => '/',
        'backLabel' => 'Retour à la carte',
    ]);
    exit;
}

render('plat', [
    'title' => 'Le plat',
    'dish' => $dish,
    'reviews' => findPublishedRatingsForDish($db, $id),
    'hasVoted' => hasVoted($db, $id, session_id()),
    'voteCount' => countVotes($db, $id),
    // Résultat du vote renvoyé par action.php : ok, deja ou erreur
    'voteStatus' => $_GET['vote'] ?? '',
    'ratingSent' => isset($_GET['avis']),
]);
