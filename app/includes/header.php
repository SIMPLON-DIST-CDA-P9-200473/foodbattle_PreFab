<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__.'/functions.php'; // Les liens admin du menu ne s'affichent qu'une fois connecté. Ce n'est pas une protection : c'est require_admin() qui bloque l'accès.

?><!doctype html><html lang="fr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?= h($title ?? 'FoodBattle') ?> · FoodBattle</title><link rel="stylesheet" href="/assets/style.css"></head><body><header><a class="logo" href="/">FoodBattle 🍔</a><nav><a href="/">Découvrir</a><a href="/classement.php">Classement</a><?php if(!empty($_SESSION['admin_id'])): ?><a href="/dashboard.php">Dashboard</a><form method="post" action="/logout.php"><input type="hidden" name="token" value="<?= csrf() ?>"><button>Déconnexion</button></form><?php endif ?></nav>
</header><main>
