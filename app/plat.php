<?php
$title='Le plat'; require 'includes/header.php'; require 'includes/db.php';
$id=filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT); $s=$db->prepare('SELECT d.*,e.name AS restaurant,e.city,e.address FROM dishes d JOIN establishments e ON e.id=d.establishment_id WHERE d.id=?');$s->execute([$id]);$d=$s->fetch();
if(!$d){http_response_code(404);echo '<h1>Plat introuvable</h1><a href="/">Retour à la carte</a>';require 'includes/footer.php';exit;}
$s=$db->prepare("SELECT * FROM ratings WHERE dish_id=? AND status='publie' ORDER BY id DESC");$s->execute([$id]);$reviews=$s->fetchAll();
$s=$db->prepare('SELECT 1 FROM votes WHERE dish_id=? AND visitor_key=?');$s->execute([$id,session_id()]);$dejaVote=$s->fetch();
// Nombre total de votes du plat, affiché à côté du bouton
$s=$db->prepare('SELECT COUNT(*) FROM votes WHERE dish_id=?');$s->execute([$id]);$nbVotes=(int)$s->fetchColumn();
?>
<p><a href="/">← Tous les plats</a></p><section class="detail"><img src="<?= h(image_for($d['category'])) ?>" alt="Photo d’illustration : <?= h($d['category']) ?>" onerror="this.onerror=null;this.src='/assets/images/placeholder.svg'"><div><span class="tag"><?= h($d['category']) ?></span><h1><?= h($d['name']) ?></h1><h2><?= h($d['restaurant']) ?></h2><p><?= h($d['address']) ?>, <?= h($d['city']) ?></p><p><?= h($d['description']) ?></p><h3><?= euro($d['price']) ?></h3><form method="post" action="/action.php"><input type="hidden" name="token" value="<?= csrf() ?>"><input type="hidden" name="id" value="<?= $id ?>"><?php if($dejaVote): ?><button disabled>✓ Voté</button><?php else: ?><button name="action" value="vote">♥ Voter pour ce plat</button><?php endif ?>
<span class="vote-count"><?= $nbVotes ?> vote<?= $nbVotes > 1 ? 's' : '' ?></span>
</form>
<?php // Message après un vote, affiché juste sous le bouton. Le nom du plat vient de la base (pas de l'URL) et passe par h(). ?>
<?php $vote = $_GET['vote'] ?? ''; ?>
<?php if($vote === 'ok'): ?>
  <p class="message flash" role="status">Ton vote pour <?= h($d['name']) ?> a bien été enregistré <button type="button" class="flash-close" aria-label="Fermer le message">×</button></p>
<?php elseif($vote === 'deja'): ?>
  <p class="error flash" role="alert">Tu as déjà voté pour <?= h($d['name']) ?> <button type="button" class="flash-close" aria-label="Fermer le message">×</button></p>
<?php elseif($vote === 'erreur'): ?>
  <p class="error flash" role="alert">Ton vote n’a pas pu être enregistré, réessaie dans quelques instants <button type="button" class="flash-close" aria-label="Fermer le message">×</button></p>
<?php endif ?>
<small>Le vote désigne ton favori ; la note exprime ton appréciation sur 5.</small></div></section>        <?php // Après l'envoi d'un avis : on prévient que l'avis n'est pas visible tout de suite (modération) ?>
<?php if(isset($_GET['avis'])): ?><p class="message" role="status">Ton avis sera publié après relecture.</p><?php endif ?>

<?php //Commentaires saisis par les visiteurs : toujours échapper avec h() pour éviter une faille XSS ?>
<h2>Donner mon avis</h2><form class="reviewform" method="post" action="/action.php"><input type="hidden" name="token" value="<?= csrf() ?>"><input type="hidden" name="id" value="<?= $id ?>"><input type="hidden" name="action" value="rate"><label>Pseudo<input name="nickname" maxlength="60" required></label><label>Note<select name="score" required><?php for($i=5;$i>=1;$i--): ?><option value="<?= $i ?>"><?= $i ?>/5</option><?php endfor ?></select></label><label>Commentaire<textarea name="comment" maxlength="1000" required></textarea></label><button>Publier mon avis</button></form><h2>Les avis</h2><?php foreach($reviews as $r): ?><article class="review"><b><?= h($r['nickname']) ?> · <?= (int)$r['score'] ?>/5</b><p><?= h($r['comment']) ?></p></article><?php endforeach ?><?php if(!$reviews): ?><p>Pas encore d’avis publié.</p><?php endif ?><?php require 'includes/footer.php'; ?>
