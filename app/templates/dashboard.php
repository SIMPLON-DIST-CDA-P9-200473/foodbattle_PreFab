<h1>Dashboard</h1>
<p>Espace de gestion du prototype local FoodBattle.</p>
<nav class="subnav">
    <a href="#plats">Plats</a> ·
    <a href="#etablissements">Établissements</a> ·
    <a href="/moderation.php">Avis à valider</a>
</nav>

<?php if ($error !== null) : ?>
    <p class="error" role="alert"><?= h($error) ?></p>
<?php endif ?>
<?php if ($added) : ?>
    <p class="message">Le plat a été ajouté.</p>
<?php endif ?>
<?php if ($deleted) : ?>
    <p class="message" role="status">Le plat a été supprimé.</p>
<?php endif ?>
<?php if ($notFound) : ?>
    <p class="error" role="alert">Ce plat n'existe pas ou a déjà été supprimé.</p>
<?php endif ?>

<h2 id="plats">Les plats (<?= count($dishes) ?>)</h2>
<table>
    <thead>
        <tr>
            <th>Plat</th>
            <th>Établissement</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($dishes as $d) : ?>
            <tr>
                <td><?= h($d['name']) ?></td>
                <td><?= h($d['restaurant']) ?></td>
                <td><?= euro($d['price']) ?></td>
                <td>
                    <a href="/modifier.php?id=<?= (int) $d['id'] ?>" class="button button-secondary">Modifier</a>
                    <?php // class et data-name utilisés par app.js pour la modale de confirmation ?>
                    <form method="post" action="/supprimer.php" class="delete-form" data-name="<?= h($d['name']) ?>">
                        <input type="hidden" name="token" value="<?= csrf() ?>">
                        <input type="hidden" name="id" value="<?= (int) $d['id'] ?>">
                        <button class="delete">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<dialog id="confirm-delete" class="modal">
    <h3>Supprimer « <span id="confirm-delete-name"></span> » ?</h3>
    <p class="modal-hint">Ses votes et ses avis seront aussi supprimés. Cette action est définitive.</p>
    <form method="dialog" class="modal-actions">
        <button value="cancel" class="modal-cancel">Annuler</button>
        <button value="confirm" class="delete">Supprimer</button>
    </form>
</dialog>

<details>
    <summary>Ajouter un plat</summary>
    <form method="post" class="reviewform">
        <input type="hidden" name="token" value="<?= csrf() ?>">
        <label>Nom<input name="name" maxlength="120" required></label>
        <label>Établissement
            <select name="establishment_id">
                <?php foreach ($establishments as $e) : ?>
                    <option value="<?= (int) $e['id'] ?>"><?= h($e['name']) ?></option>
                <?php endforeach ?>
            </select>
        </label>
        <label>Catégorie
            <select name="category">
                <option value="burger">Burger</option>
                <option value="tacos">Tacos</option>
                <option value="pizza">Pizza</option>
            </select>
        </label>
        <label>Prix<input type="number" name="price" min="<?= MIN_PRICE ?>" step="0.01" required></label>
        <label>Description<textarea name="description"></textarea></label>
        <button>Ajouter</button>
    </form>
</details>

<h2 id="etablissements">Les établissements</h2>
<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Ville</th>
            <th>Adresse</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($establishments as $e) : ?>
            <tr>
                <td><?= h($e['name']) ?></td>
                <td><?= h($e['city']) ?></td>
                <td><?= h($e['address']) ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
