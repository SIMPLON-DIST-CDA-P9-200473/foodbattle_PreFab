<section class="intro">
    <span>LA ROUE DU DESTIN</span>
    <h1>Je ne sais pas quoi manger !</h1>
    <p>Laisse le hasard choisir ton prochain plat parmi ceux du quartier.</p>
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
    <?php // data-names : liste des plats pour l'animation (JSON échappé avec h() pour rester dans l'attribut) ?>
    <?php // aria-hidden : un lecteur d'écran ne lit pas chaque nom qui défile, seulement le résultat ?>
    <div id="wheel" class="wheel" aria-hidden="true"
         data-names="<?= h(json_encode($names)) ?>"
         data-winner="<?= h($winner['name']) ?>"><?= h($winner['name']) ?></div>

    <?php // Sans JavaScript, ce bloc reste visible : la fonctionnalité marche quand même ?>
    <div id="wheel-result" class="wheel-result" aria-live="polite">
        <span class="tag"><?= h($winner['category']) ?></span>
        <h2>🎉 <?= h($winner['name']) ?></h2>
        <p><?= h($winner['restaurant']) ?> · <?= h($winner['city']) ?></p>
        <a class="button" href="/controller/plat.php?id=<?= (int) $winner['id'] ?>">Découvrir et voter</a>
        <a class="button button-secondary" href="/controller/roue.php">🎲 Retenter ma chance</a>
    </div>
<?php endif ?>
