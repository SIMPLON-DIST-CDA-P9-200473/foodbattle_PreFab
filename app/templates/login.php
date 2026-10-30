<h1>Connexion</h1>

<?php if ($error !== null) : ?>
    <p role="alert"><?= h($error) ?></p>
<?php endif ?>

<form method="post" class="reviewform">
    <input type="hidden" name="token" value="<?= csrf() ?>">
    <label>Nom d’utilisateur
        <input name="username" autocomplete="username" required>
    </label>
    <label>Mot de passe
        <input type="password" name="password" autocomplete="current-password" required>
    </label>
    <button>Se connecter</button>
</form>
