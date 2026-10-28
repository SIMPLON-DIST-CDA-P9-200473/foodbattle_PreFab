<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= h($title ?? 'FoodBattle') ?> · FoodBattle</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header>
    <a class="logo" href="/">FoodBattle 🍔</a>
    <nav>
        <a href="/">Découvrir</a>
        <a href="/classement.php">Classement</a>
        <?php // Les liens admin ne s'affichent qu'une fois connecté. Ce n'est pas une protection : c'est require_admin() qui bloque l'accès. ?>
        <?php if (!empty($_SESSION['admin_id'])) : ?>
            <a href="/dashboard.php">Dashboard</a>
            <?php // Déconnexion en POST avec jeton CSRF : un simple lien pourrait être déclenché depuis un autre site ?>
            <form method="post" action="/logout.php" class="nav-auth">
                <input type="hidden" name="token" value="<?= csrf() ?>">
                <button>Déconnexion</button>
            </form>
        <?php elseif (($_SERVER['SCRIPT_NAME'] ?? '') !== '/login.php') : ?>
            <a href="/login.php" class="button nav-auth">Connexion</a>
        <?php endif ?>
    </nav>
</header>
<main>
