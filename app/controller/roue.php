<?php

require __DIR__ . '/../bootstrap.php';

// Valeurs par défaut : utilisées si le tirage échoue
$winner = null;
$names = [];
$error = null;

try {
    // Le hasard est décidé ici, côté serveur, avec les vraies données de la base
    $winner = findRandomDish($db);
    // Pour l'animation, on n'a besoin que des noms : array_column extrait la colonne "name"
    $names = array_column(findDishes($db, '', null), 'name');
} catch (PDOException $e) {
    // Le détail technique va dans les logs, le visiteur voit un message simple
    error_log('Tirage de la roue impossible : ' . $e->getMessage());
    $error = 'Le tirage est indisponible pour le moment. Merci de réessayer plus tard.';
}

render('roue', [
    'title' => 'La roue du destin',
    'winner' => $winner,
    'names' => $names,
    'error' => $error,
]);
