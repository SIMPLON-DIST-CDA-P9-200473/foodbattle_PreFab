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
| Dashboard (admin) | http://localhost:8080/dashboard.php |

## Base de données

La base est créée automatiquement au premier lancement à partir de `database/init.sql`.

Pour l’ouvrir avec DBeaver : hôte `localhost`, port `5432`, base `foodbattle`, utilisateur `foodbattle`.

Pour repartir d’une base neuve : `docker compose down -v` puis `docker compose up -d`.