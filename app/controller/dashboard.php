<?php

require __DIR__ . '/../bootstrap.php';
require_admin(); // ferme l'accès du dashboard aux utilisateurs non connectés

// Chargés avant le POST : la liste sert aussi à vérifier l'établissement choisi.
$establishments = findAllEstablishments($db);
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_post();
    $name = trim($_POST['name'] ?? '');
    $price = valid_price($_POST['price'] ?? '');
    $establishmentId = filter_input(INPUT_POST, 'establishment_id', FILTER_VALIDATE_INT);
    $category = $_POST['category'] ?? '';
    $description = trim($_POST['description'] ?? '');

    $isValid = $name !== ''
        && strlen($name) <= 120
        && $price !== null
        && in_array($category, ['burger', 'tacos', 'pizza'], true)
        && in_array($establishmentId, array_column($establishments, 'id'));

    if ($isValid) {
        createDish($db, $establishmentId, $name, $category, $description, $price);
        header('Location: /controller/dashboard.php?added=1');
        exit;
    }

    $error = 'Vérifie les champs du plat.';
}

render('dashboard', [
    'title' => 'Dashboard',
    'establishments' => $establishments,
    'dishes' => findAllDishesWithEstablishment($db),
    'error' => $error,
    'added' => isset($_GET['added']),
    'deleted' => isset($_GET['deleted']),
    'notFound' => ($_GET['error'] ?? '') === 'notfound',
]);
