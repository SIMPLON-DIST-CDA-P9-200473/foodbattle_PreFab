<?php

require __DIR__ . '/bootstrap.php';
check_post();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$id || findDishById($db, $id) === null) {
    http_response_code(404);
    exit('Plat introuvable.');
}

$action = $_POST['action'] ?? '';

if ($action === 'vote') {
    // Si la base plante, on prévient le visiteur au lieu d'afficher une page d'erreur
    try {
        $saved = addVote($db, $id, session_id());
    } catch (PDOException $e) {
        error_log('Vote impossible : ' . $e->getMessage()); // détail technique dans les logs serveur, jamais à l'écran
        header('Location: /plat.php?id=' . $id . '&vote=erreur');
        exit;
    }
    // false = ce visiteur avait déjà voté pour ce plat (contrainte UNIQUE)
    header('Location: /plat.php?id=' . $id . '&vote=' . ($saved ? 'ok' : 'deja'));
    exit;
}

if ($action === 'rate') {
    $score = filter_input(INPUT_POST, 'score', FILTER_VALIDATE_INT);
    $nickname = trim($_POST['nickname'] ?? '');
    $comment = trim($_POST['comment'] ?? '');

    $isValid = $score && $score >= 1 && $score <= 5
        && $nickname !== '' && strlen($nickname) <= 240
        && $comment !== '' && strlen($comment) <= 4000;

    if (!$isValid) {
        http_response_code(422);
        exit('Vérifie les champs de ton avis.');
    }

    // Modération : un nouvel avis est "en_attente", il ne s'affiche qu'après validation par un admin
    createRating($db, $id, $nickname, $score, $comment);
    // Redirection propre à l'avis : le message affiché ne sera pas le même que pour un vote
    header('Location: /plat.php?id=' . $id . '&avis=1');
    exit;
}

http_response_code(400);
exit('Action inconnue.');
