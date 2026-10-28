<?php

require __DIR__ . '/bootstrap.php';

render('classement', [
    'title' => 'Classement',
    'rows' => findTopDishes($db),
]);
