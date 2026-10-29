<section class="intro">
    <span>BURGERS · TACOS · PIZZAS</span>
    <h1>Qui mérite la première place ?</h1>
    <p>Explore les spots du quartier, note les plats et vote pour ton favori.</p>
    <a class="button" href="/controller/classement.php">Voir le classement →</a>
    <a class="button button-secondary" href="/controller/roue.php">🎰 Je ne sais pas quoi manger !</a>

</section>

<h2 id="carte">À la carte</h2>
<form class="filters" method="get">
    <?php // Accessibilité : "for" relie explicitement chaque label à l'id de son champ (plus fiable que la seule imbrication) ?>
    <label for="q">Rechercher
        <input id="q" name="q" value="<?= h($search) ?>" placeholder="Un plat ou un établissement">
    </label>
    <label for="categorie">Catégorie
        <select id="categorie" name="categorie">
            <option value="">Tout</option>
            <?php foreach (['burger' => 'Burgers', 'tacos' => 'Tacos', 'pizza' => 'Pizzas'] as $value => $label) : ?>
                <option value="<?= $value ?>" <?= $category === $value ? 'selected' : '' ?>><?= $label ?></option>
            <?php endforeach ?>
        </select>
    </label>
    <button>Rechercher</button>
    <a href="/" class="button button-secondary">Réinitialiser</a>
</form>

<p><?= count($dishes) ?> plat(s) trouvé(s)</p>

<div class="cards">
    <?php foreach ($dishes as $d) : ?>
        <article>
            <img src="<?= h(image_for($d['category'])) ?>"
                 alt="Photo d’illustration : <?= h($d['category']) ?>"
                 onerror="this.onerror=null;this.src='/assets/images/placeholder.svg'">
            <div class="cardbody">
                <span class="tag"><?= h($d['category']) ?></span>
                <h3><?= h($d['name']) ?></h3>
                <p><?= h($d['restaurant']) ?> · <?= h($d['city']) ?></p>
                <p><?= h($d['description']) ?></p>
                <p>
                    <strong><?= euro($d['price']) ?></strong>
                    <span class="rating">★ <?= $d['average'] ? number_format((float) $d['average'], 1, ',', '') : '—' ?>/5</span>
                </p>
                <p><?= (int) $d['votes'] ?> vote(s)</p>
                <a class="button" href="/controller/plat.php?id=<?= (int) $d['id'] ?>">Découvrir et voter</a>
            </div>
        </article>
    <?php endforeach ?>
</div>

<?php if (!$dishes) : ?>
    <p>Aucun plat ou établissement trouvé</p>
<?php endif ?>
