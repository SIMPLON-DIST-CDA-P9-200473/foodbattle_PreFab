<?php

// Requêtes sur les votes (table votes). Un visiteur = un vote par plat (contrainte UNIQUE en base).

// Renvoie false si ce visiteur avait déjà voté pour ce plat.
function addVote(PDO $db, int $dishId, string $visitorKey): bool
{
    $stmt = $db->prepare(
        'INSERT INTO votes (dish_id, visitor_key) VALUES (:dishId, :visitorKey) ON CONFLICT DO NOTHING'
    );
    $stmt->execute(['dishId' => $dishId, 'visitorKey' => $visitorKey]);

    return $stmt->rowCount() > 0;
}

function hasVoted(PDO $db, int $dishId, string $visitorKey): bool
{
    $stmt = $db->prepare('SELECT 1 FROM votes WHERE dish_id = :dishId AND visitor_key = :visitorKey');
    $stmt->execute(['dishId' => $dishId, 'visitorKey' => $visitorKey]);

    return $stmt->fetch() !== false;
}

function countVotes(PDO $db, int $dishId): int
{
    $stmt = $db->prepare('SELECT COUNT(*) FROM votes WHERE dish_id = :dishId');
    $stmt->execute(['dishId' => $dishId]);

    return (int) $stmt->fetchColumn();
}
