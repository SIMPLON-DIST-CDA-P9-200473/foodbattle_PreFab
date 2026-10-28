<?php

function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
function csrf()
{
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(24));
    }
    return $_SESSION['token'];
}
function check_post()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !hash_equals(csrf(), $_POST['token'] ?? '')) {
        http_response_code(403);
        exit('Requête refusée.');
    }
}

// Bloque les pages admin : redirige vers la connexion si aucun admin n'est connecté.
// Le exit est indispensable : sans lui, la suite de la page s'exécuterait quand même.
function require_admin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_id'])) {
        header('Location: /login.php');
        exit;
    }
}

function image_for($category)
{
    $photos = ['burger' => '1639557', 'tacos' => '4958792', 'pizza' => '315755'];
    $id = $photos[$category] ?? '1639557';
    return 'https://images.pexels.com/photos/' . $id . '/pexels-photo-' . $id . '.jpeg?auto=compress&cs=tinysrgb&w=800';
}
function euro($n)
{
    return number_format((float)$n, 2, ',', ' ') . ' €';
}

const MIN_PRICE = 1;
function valid_price($raw)
{
    $raw = trim((string)$raw);
    if (!preg_match('/^\d{1,6}(\.\d{1,2})?$/', $raw)) {
        return null;
    }
    $price = (float)$raw;
    return $price >= MIN_PRICE ? $price : null;
}

// Affiche une page complète : header + template de la page + footer.
// $data contient les variables envoyées par le contrôleur, par exemple ['title' => 'Classement', 'rows' => $rows].
function render(string $template, array $data = []): void
{
    extract($data); // transforme ['rows' => …] en variable $rows, utilisable dans le template
    require __DIR__ . '/../templates/layout/header.php';
    require __DIR__ . '/../templates/' . $template . '.php';
    require __DIR__ . '/../templates/layout/footer.php';
}
