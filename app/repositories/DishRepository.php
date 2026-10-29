<?php

// Requêtes sur les plats (table dishes).

// Recherche de la page d'accueil. $category doit déjà être validée par le contrôleur (ou null = toutes).
function findDishes(PDO $db, string $search, ?string $category): array
{
    $sql = "SELECT d.*, e.name AS restaurant, e.city,
                (SELECT AVG(score) FROM ratings WHERE dish_id = d.id AND status = 'publie') AS average,
                (SELECT COUNT(*) FROM votes WHERE dish_id = d.id) AS votes
            FROM dishes d
            JOIN establishments e ON e.id = d.establishment_id
            WHERE 1 = 1";
    $params = [];

    // On n'ajoute que des morceaux de SQL fixes : les valeurs saisies passent toujours par $params.
    if ($search !== '') {
        $sql .= ' AND (unaccent(d.name) ILIKE unaccent(:dishName) OR unaccent(e.name) ILIKE unaccent(:restaurantName))';
        $params['dishName'] = '%' . $search . '%';
        $params['restaurantName'] = '%' . $search . '%';
    }
    if ($category !== null) {   //correspond au filtre à côté de la section recherche
        $sql .= ' AND d.category = :category';
        $params['category'] = $category;
    }
    $sql .= ' ORDER BY d.id';

    $stmt = $db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll();
}

function findTopDishes(PDO $db, int $limit = 10): array  //permet de trier les plats sur la page classement
{
    $stmt = $db->prepare(
        "SELECT d.id, d.name, d.category, e.name AS restaurant,
            (SELECT COUNT(*) FROM votes WHERE dish_id = d.id) AS votes,
            (SELECT AVG(score) FROM ratings WHERE dish_id = d.id AND status = 'publie') AS average
        FROM dishes d
        JOIN establishments e ON e.id = d.establishment_id
        ORDER BY votes DESC, average DESC NULLS LAST, d.id ASC
        LIMIT :limit"
    );
    $stmt->bindValue('limit', $limit, PDO::PARAM_INT); // LIMIT attend un entier, pas une chaîne
    $stmt->execute();

    return $stmt->fetchAll();
}

function findDishWithEstablishment(PDO $db, int $id): ?array    //affiche la page de détail du plat avec les infos du restaurant
{
    $stmt = $db->prepare(
        'SELECT d.*, e.name AS restaurant, e.city, e.address
        FROM dishes d
        JOIN establishments e ON e.id = d.establishment_id
        WHERE d.id = :id'
    );
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() ?: null;
}

function findDishById(PDO $db, int $id): ?array
{
    $stmt = $db->prepare('SELECT * FROM dishes WHERE id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->fetch() ?: null;
}

function findAllDishesWithEstablishment(PDO $db): array
{
    $stmt = $db->prepare(
        'SELECT d.*, e.name AS restaurant
        FROM dishes d
        JOIN establishments e ON e.id = d.establishment_id
        ORDER BY d.id'
    );
    $stmt->execute();

    return $stmt->fetchAll();
}

function findRandomDish(PDO $db): ?array
{
    $stmt = $db->prepare(
        'SELECT d.id, d.name, d.category, e.name AS restaurant, e.city
        FROM dishes d
        JOIN establishments e ON e.id = d.establishment_id
        ORDER BY RANDOM()
        LIMIT 1'
    );
    $stmt->execute(); // aucun paramètre : rien ne vient de l'utilisateur

    return $stmt->fetch() ?: null; // fetch() renvoie false si la table est vide
}

function createDish(PDO $db, int $establishmentId, string $name, string $category, string $description, float $price): void
{
    $stmt = $db->prepare(
        'INSERT INTO dishes (establishment_id, name, category, description, price)
        VALUES (:establishmentId, :name, :category, :description, :price)'
    );
    $stmt->execute([
        'establishmentId' => $establishmentId,
        'name' => $name,
        'category' => $category,
        'description' => $description,
        'price' => $price,
    ]);
}

function updateDish(PDO $db, int $id, string $name, float $price): void
{
    $stmt = $db->prepare('UPDATE dishes SET name = :name, price = :price WHERE id = :id');
    $stmt->execute(['name' => $name, 'price' => $price, 'id' => $id]);
}

// Renvoie false si aucun plat n'a été supprimé (id inexistant ou déjà supprimé).
// Les votes et avis sont supprimés aussi grâce à ON DELETE CASCADE.
function deleteDish(PDO $db, int $id): bool
{
    $stmt = $db->prepare('DELETE FROM dishes WHERE id = :id');
    $stmt->execute(['id' => $id]);

    return $stmt->rowCount() > 0;
}
