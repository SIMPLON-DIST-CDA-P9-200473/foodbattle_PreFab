<?php

require __DIR__ . '/bootstrap.php';
require_admin(); // page réservée aux admins (US-2)

render('moderation', [
    'title' => 'Avis à valider',
    'pending' => findPendingRatings($db),
    'history' => findModeratedRatings($db),
    'done' => $_GET['done'] ?? null,
    'notFound' => ($_GET['error'] ?? '') === 'notfound',
]);
