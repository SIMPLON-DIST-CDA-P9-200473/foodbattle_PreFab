<?php

// Point de départ commun à toutes les pages : session, fonctions utilitaires, connexion à la base.
// Chaque contrôleur commence par : require __DIR__ . '/bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//__DIR__ est le dossier du fichier courant.Avec lui, le chemin fonctionne quel que soit le dossier depuis lequel le fichier est inclus
//require_once évite de charger deux fois le même fichier.
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/repositories/DishRepository.php';
require_once __DIR__ . '/repositories/EstablishmentRepository.php';
require_once __DIR__ . '/repositories/RatingRepository.php';
require_once __DIR__ . '/repositories/VoteRepository.php';
require_once __DIR__ . '/repositories/AdminRepository.php';
