# FoodBattle

Site web pour découvrir, noter et voter pour les meilleurs plats street food du quartier (burgers, tacos, pizzas).

## Technologies

PHP 8.5 · PostgreSQL 16 · Docker

## Lancer le projet

Prérequis : [Docker Desktop](https://www.docker.com/products/docker-desktop/) installé et lancé.

```bash
git clone <url-du-depot>
cd foodbattle
cp .env.example .env
docker compose up --build
```

Ouvrir **http://localhost:8080**

Arrêter : `docker compose down`

## Pages

| Page | Adresse |
|---|---|
| Accueil | http://localhost:8080/ |
| Classement | http://localhost:8080/classement.php |
| Dashboard (admin, connexion requise) | http://localhost:8080/login.php |

## Base de données

La base est créée automatiquement au premier lancement à partir de `database/init.sql`.

Pour l’ouvrir avec DBeaver : hôte `localhost`, port `5432`, base `foodbattle`, utilisateur `foodbattle`.

Pour repartir d’une base neuve : `docker compose down -v` puis `docker compose up -d`.

## Espace admin

Les pages de gestion (dashboard, modification, suppression, modération) sont réservées aux admins : http://localhost:8080/login.php

Aucun compte n'est fourni. Il faut en créer un après chaque base neuve :

1. Générer le hash du mot de passe (taper le mot de passe puis Entrée) :
   ```bash
   docker compose exec app php -r 'echo password_hash(trim(fgets(STDIN)), PASSWORD_DEFAULT), PHP_EOL;'
   ```
2. Ouvrir la base (avec les valeurs de son `.env`) :
   ```bash
   docker compose exec db psql -U <DB_USER> -d <DB_NAME>
   ```
   puis :
   ```sql
   INSERT INTO admins (username, password_hash) VALUES ('mon_nom', 'LE_HASH');
   ```
