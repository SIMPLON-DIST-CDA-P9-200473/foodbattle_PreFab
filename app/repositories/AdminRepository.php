<?php

// Requêtes sur les comptes admin (table admins).

function findAdminByUsername(PDO $db, string $username): ?array
{
    $stmt = $db->prepare('SELECT id, password_hash FROM admins WHERE username = :username');
    $stmt->execute(['username' => $username]);

    return $stmt->fetch() ?: null;
}
