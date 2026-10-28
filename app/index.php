<?php

require __DIR__ . '/bootstrap.php';

$search = trim($_GET['q'] ?? '');
$category = $_GET['categorie'] ?? '';

// Liste blanche : une catégorie inconnue est ignorée (on affiche tous les plats).
$validCategory = in_array($category, ['burger', 'tacos', 'pizza'], true) ? $category : null;

render('index', [
    'title' => 'Découvrir',
    'search' => $search,
    'category' => $category,
    'dishes' => findDishes($db, $search, $validCategory),
]);
