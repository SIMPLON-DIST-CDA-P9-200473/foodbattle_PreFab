<?php

// Requêtes sur les avis (table ratings). Statuts possibles : en_attente, publie, refuse.

function findPublishedRatingsForDish(PDO $db, int $dishId): array
{
    $stmt = $db->prepare("SELECT * FROM ratings WHERE dish_id = :dishId AND status = 'publie' ORDER BY id DESC");
    $stmt->execute(['dishId' => $dishId]);

    return $stmt->fetchAll();
}

// Un nouvel avis attend toujours la validation d'un admin.
function createRating(PDO $db, int $dishId, string $nickname, int $score, string $comment): void
{
    $stmt = $db->prepare(
        "INSERT INTO ratings (dish_id, nickname, score, comment, status)
        VALUES (:dishId, :nickname, :score, :comment, 'en_attente')"
    );
    $stmt->execute(['dishId' => $dishId, 'nickname' => $nickname, 'score' => $score, 'comment' => $comment]);
}

// Avis à relire : les plus anciens d'abord, pour les traiter dans l'ordre d'arrivée.
function findPendingRatings(PDO $db): array
{
    $stmt = $db->prepare(
        "SELECT r.*, d.name AS dish
        FROM ratings r
        JOIN dishes d ON d.id = r.dish_id
        WHERE r.status = 'en_attente'
        ORDER BY r.created_at ASC, r.id ASC"
    );
    $stmt->execute();

    return $stmt->fetchAll();
}

// Historique : avis déjà publiés ou refusés, les plus récents d'abord.
function findModeratedRatings(PDO $db): array
{
    $stmt = $db->prepare(
        "SELECT r.*, d.name AS dish
        FROM ratings r
        JOIN dishes d ON d.id = r.dish_id
        WHERE r.status <> 'en_attente'
        ORDER BY r.id DESC"
    );
    $stmt->execute();

    return $stmt->fetchAll();
}

// $status doit déjà être validé par le contrôleur ('publie' ou 'refuse').
// Renvoie false si l'avis n'existe pas ou a déjà été modéré.
function moderateRating(PDO $db, int $id, string $status): bool
{
    $stmt = $db->prepare("UPDATE ratings SET status = :status WHERE id = :id AND status = 'en_attente'");
    $stmt->execute(['status' => $status, 'id' => $id]);

    return $stmt->rowCount() > 0;
}
