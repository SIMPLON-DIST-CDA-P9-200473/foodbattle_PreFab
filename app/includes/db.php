<?php
try {   // permet de catch le mdp manquant et l'échec de connexion à la BDD
    $password = getenv('DB_PASSWORD');
    if ($password === false || $password === '') {
        throw new RuntimeException('DB_PASSWORD manquant : vérifie ton fichier .env');
    }

    $db = new PDO(
        'pgsql:host=' . (getenv('DB_HOST') ?: 'localhost') . ';port=' . (getenv('DB_PORT') ?: '5432') . ';dbname=' . (getenv('DB_NAME') ?: 'foodbattle'),
        getenv('DB_USER') ?: 'foodbattle',
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (RuntimeException $e) {
    error_log('Connexion à la base impossible : ' .$e->getMessage()); // contient le message technique dans les logs du serveur
    http_response_code(500); // renvoie le code HTTP erreur serveur à la page HTML
    exit('Service momentanément indisponible. Merci de réessayer plus tard.'); // affiche un message au visiteur
}


