<?php

// Requêtes sur les établissements (table establishments).

function findAllEstablishments(PDO $db): array
{
    $stmt = $db->prepare('SELECT * FROM establishments ORDER BY id');
    $stmt->execute();

    return $stmt->fetchAll();
}
