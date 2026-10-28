<?php

require __DIR__ . '/bootstrap.php';
require_admin(); // ferme l'accès à la page aux utilisateurs non connectés

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$dish = $id ? findDishById($db, $id) : null;

if ($dish === null) {
    http_response_code(404);
    render('not_found', ['title' => 'Modifier un plat']);
    exit;
}

$error = null;
$saved = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_post();
    $name = trim($_POST['name'] ?? '');
    $price = valid_price($_POST['price'] ?? '');

    if ($name !== '' && strlen($name) <= 120 && $price !== null) {
        updateDish($db, $id, $name, $price);
        $saved = true;
        // on réaffiche les nouvelles valeurs dans le formulaire
        $dish['name'] = $name;
        $dish['price'] = $price;
    } else {
        $error = 'Vérifie le nom et le prix.';
    }
}

render('modifier', [
    'title' => 'Modifier un plat',
    'dish' => $dish,
    'saved' => $saved,
    'error' => $error,
]);
