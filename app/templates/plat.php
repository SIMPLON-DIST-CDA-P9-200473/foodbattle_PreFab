<p><a href="/">← Tous les plats</a></p>

<section class="detail">
    <img src="<?= h(image_for($dish['category'])) ?>"
         alt="Photo d’illustration : <?= h($dish['category']) ?>"
         onerror="this.onerror=null;this.src='/assets/images/placeholder.svg'">
    <div>
        <span class="tag"><?= h($dish['category']) ?></span>
        <h1><?= h($dish['name']) ?></h1>
        <h2><?= h($dish['restaurant']) ?></h2>
        <p><?= h($dish['address']) ?>, <?= h($dish['city']) ?></p>
        <p><?= h($dish['description']) ?></p>
        <h3><?= euro($dish['price']) ?></h3>

        <form method="post" action="/action.php">
            <input type="hidden" name="token" value="<?= csrf() ?>">
            <input type="hidden" name="id" value="<?= (int) $dish['id'] ?>">
            <?php if ($hasVoted) : ?>
                <button disabled>✓ Voté</button>
            <?php else : ?>
                <button name="action" value="vote">♥ Voter pour ce plat</button>
            <?php endif ?>
            <span class="vote-count"><?= $voteCount ?> vote<?= $voteCount > 1 ? 's' : '' ?></span>
        </form>
        <?php // Message après un vote, affiché juste sous le bouton. Le nom du plat vient de la base (pas de l'URL) et passe par h(). ?>
        <?php if ($voteStatus === 'ok') : ?>
            <p class="message flash" role="status">Ton vote pour <?= h($dish['name']) ?> a bien été enregistré <button type="button" class="flash-close" aria-label="Fermer le message">×</button></p>
        <?php elseif ($voteStatus === 'deja') : ?>
            <p class="error flash" role="alert">Tu as déjà voté pour <?= h($dish['name']) ?> <button type="button" class="flash-close" aria-label="Fermer le message">×</button></p>
        <?php elseif ($voteStatus === 'erreur') : ?>
            <p class="error flash" role="alert">Ton vote n’a pas pu être enregistré, réessaie dans quelques instants <button type="button" class="flash-close" aria-label="Fermer le message">×</button></p>
        <?php endif ?>
        <small>Le vote désigne ton favori ; la note exprime ton appréciation sur 5.</small>
    </div>
</section>

<?php // Après l'envoi d'un avis : on prévient que l'avis n'est pas visible tout de suite (modération) ?>
<?php if ($ratingSent) : ?>
    <p class="message" role="status">Ton avis sera publié après relecture.</p>
<?php endif ?>

<h2>Donner mon avis</h2>
<form class="reviewform" method="post" action="/action.php">
    <input type="hidden" name="token" value="<?= csrf() ?>">
    <input type="hidden" name="id" value="<?= (int) $dish['id'] ?>">
    <input type="hidden" name="action" value="rate">
    <label>Pseudo<input name="nickname" maxlength="60" required></label>
    <label>Note
        <select name="score" required>
            <?php for ($i = 5; $i >= 1; $i--) : ?>
                <option value="<?= $i ?>"><?= $i ?>/5</option>
            <?php endfor ?>
        </select>
    </label>
    <label>Commentaire<textarea name="comment" maxlength="1000" required></textarea></label>
    <button>Publier mon avis</button>
</form>

<h2>Les avis</h2>
<?php // Commentaires saisis par les visiteurs : toujours échapper avec h() pour éviter une faille XSS ?>
<?php foreach ($reviews as $r) : ?>
    <article class="review">
        <b><?= h($r['nickname']) ?> · <?= (int) $r['score'] ?>/5</b>
        <p><?= h($r['comment']) ?></p>
    </article>
<?php endforeach ?>
<?php if (!$reviews) : ?>
    <p>Pas encore d’avis publié.</p>
<?php endif ?>
