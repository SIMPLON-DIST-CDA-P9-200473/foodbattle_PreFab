<p><a href="/dashboard.php">← Dashboard</a></p>
<h1>Modifier un plat</h1>

<?php if ($saved) : ?>
    <p class="message">Modifications enregistrées.</p>
<?php endif ?>
<?php if ($error !== null) : ?>
    <p class="error" role="alert"><?= h($error) ?></p>
<?php endif ?>

<form method="post" class="reviewform">
    <input type="hidden" name="token" value="<?= csrf() ?>">
    <label>Nom
        <input name="name" value="<?= h($dish['name']) ?>" maxlength="120" required>
    </label>
    <label>Prix
        <input name="price" type="number" step="0.01" min="<?= MIN_PRICE ?>" value="<?= h($dish['price']) ?>" required>
    </label>
    <button>Enregistrer</button>
</form>
