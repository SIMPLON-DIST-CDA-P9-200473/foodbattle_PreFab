<?php

require __DIR__ . '/bootstrap.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_post();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $admin = findAdminByUsername($db, $username);

    // password_verify compare le mot de passe tapé au hash stocké en base
    if ($admin !== null && password_verify($password, $admin['password_hash'])) {
        // nouvel identifiant de session à la connexion (protège contre la fixation de session)
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $admin['id'];
        header('Location: /dashboard.php');
        exit;
    }

    // même message que le nom ou le mot de passe soit faux : on ne révèle pas quels comptes existent
    $error = 'Identifiants incorrects.';
}

render('login', [
    'title' => 'Connexion',
    'error' => $error,
]);
