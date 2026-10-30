<section class="intro">
    <span>LA ROUE DU DESTIN</span>
    <h1>Je ne sais pas quoi manger !</h1>
    <p>Laisse le hasard choisir ton prochain plat.</p>
</section>

<?php if ($error) : ?>
    <?php // Cas limite : erreur de récupération des données ?>
    <p class="error"><?= h($error) ?></p>
    <a class="button" href="/controller/roue.php">Réessayer</a>

<?php elseif ($winner === null) : ?>
    <?php // Cas limite : aucun plat en base ?>
    <p class="message">Aucun plat disponible pour le moment. Reviens bientôt !</p>
    <a class="button button-secondary" href="/">Retour à la carte</a>

<?php else : ?>
    <?php // La roue : une part par plat. --n (nombre de parts) et --i (numéro de la part) servent au CSS pour calculer les angles ?>
    <?php // aria-hidden : la roue est décorative, le résultat est annoncé dans #wheel-result ?>
    <div class="wheel-box" aria-hidden="true">
        <div class="wheel-pointer"></div>
        <div id="wheel" class="wheel" style="--n: <?= count($names) ?>"
             data-count="<?= count($names) ?>" data-winner-index="<?= (int) $winnerIndex ?>">
            <?php foreach ($names as $i => $name) : ?>
                <span class="wheel-label" style="--i: <?= (int) $i ?>"><?= h($name) ?></span>
            <?php endforeach ?>
        </div>
    </div>

    <p class="wheel-actions">
        <?php // Caché par défaut (hidden) : c'est app.js qui l'affiche. Sans JS, le résultat est visible directement ?>
        <button id="wheel-spin" type="button" hidden>🎡 Lancer la roue !</button>
    </p>

    <div id="wheel-result" class="wheel-result" aria-live="polite">
        <span class="tag"><?= h($winner['category']) ?></span>
        <h2>🎉 <?= h($winner['name']) ?></h2>
        <p><?= h($winner['restaurant']) ?> · <?= h($winner['city']) ?></p>
        <a class="button" href="/controller/plat.php?id=<?= (int) $winner['id'] ?>">Découvrir et voter</a>
        <a class="button button-secondary" href="/controller/roue.php">🎲 Retenter ma chance</a>
    </div>
<?php endif ?>

