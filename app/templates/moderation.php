<?php
// Libellés lisibles à l'écran (en base, les valeurs restent sans accents)
$labels = ['en_attente' => 'En attente','publie' => 'Publié','refuse' => 'Refusé'];
?>
<h1>Avis à valider</h1>

<?php // Messages affichés après une action (envoyés par moderer.php)  ?>
<?php if ($done === 'publie') :
    ?><p class="message" role="status">L'avis a été publié.</p><?php
endif ?>
<?php if ($done === 'refuse') :
    ?><p class="message" role="status">L'avis a été refusé.</p><?php
endif ?>
<?php if ($notFound) :
    ?><p class="error" role="alert">Cet avis n'existe pas ou a déjà été modéré.</p><?php
endif ?>


<h2>En attente (<?= count($pending) ?>)</h2>
<?php if (!$pending) : ?>
  <p>Aucun avis à relire.</p>
<?php else : ?>
  <table>
    <thead><tr><th>Plat</th><th>Pseudo</th><th>Note</th><th>Avis</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($pending as $r) : ?>
      <tr>
        <?php // Contenu saisi par les visiteurs : toujours échappé avec h() pour éviter une faille XSS (US-1) ?>
        <td><?= h($r['dish']) ?></td>
        <td><?= h($r['nickname']) ?></td>
        <td><?= (int)$r['score'] ?>/5</td>
        <td><?= h($r['comment']) ?></td>
        <td>
          <?php // Formulaire POST avec jeton CSRF : un simple lien pourrait être déclenché depuis un autre site ?>
          <form method="post" action="/moderer.php">
            <input type="hidden" name="token" value="<?= csrf() ?>">
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <?php // Deux boutons, un seul formulaire : seul le bouton cliqué envoie sa valeur "decision" ?>
            <button name="decision" value="publie">Publier</button>
            <button name="decision" value="refuse" class="delete">Refuser</button>
          </form>
        </td>
      </tr>
    <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>

<h2>Historique</h2>
<table>
  <thead><tr><th>Plat</th><th>Pseudo</th><th>Avis</th><th>Statut</th></tr></thead>
  <tbody>
  <?php foreach ($history as $r) : ?>
    <tr>
      <td><?= h($r['dish']) ?></td>
      <td><?= h($r['nickname']) ?></td>
      <td><?= h($r['comment']) ?></td>
      <td><?= h($labels[$r['status']] ?? $r['status']) ?></td>
    </tr>
  <?php endforeach ?>
  </tbody>
</table>

<p><a href="/dashboard.php">Retour au dashboard</a></p>