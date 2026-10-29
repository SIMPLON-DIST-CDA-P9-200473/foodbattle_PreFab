<?php

require __DIR__ . '/../bootstrap.php';

// Valeurs par défaut : utilisées s'il n'y a aucun plat ou si la base ne répond pas
$winner = null;
$winnerIndex = 0;
$names = [];
$error = null;

try {
    $dishes = findDishes($db, '', null); // tous les plats, avec leur restaurant

    if ($dishes) {
        // Le tirage est fait ici, côté serveur : random_int est un générateur aléatoire sûr
        $winnerIndex = random_int(0, count($dishes) - 1);
        $winner = $dishes[$winnerIndex];
        // Les noms, dans le même ordre : une part de la roue par plat
        $names = array_column($dishes, 'name');
    }
} catch (PDOException $e) {
    // Le détail technique va dans les logs, le visiteur voit un message simple
    error_log('Tirage de la roue impossible : ' . $e->getMessage());
    $error = 'Le tirage est indisponible pour le moment. Merci de réessayer plus tard.';
}

render('roue', [
    'title' => 'La roue du destin',
    'winner' => $winner,
    'winnerIndex' => $winnerIndex,
    'names' => $names,
    'error' => $error,
]);
