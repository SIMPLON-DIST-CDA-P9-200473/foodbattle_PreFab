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

Avant le `docker compose up --build`, dans `.env`, remplacer la valeur de `DB_PASSWORD` par un mot de passe de son choix (obligatoire : l'app refuse de démarrer sans).

Ouvrir **http://localhost:8080**

Arrêter : `docker compose down`

Les données sont fictives. Les photos sont chargées depuis Pexels : une connexion Internet est nécessaire pour les afficher.

## Pages

| Page | Adresse |
|---|---|
| Accueil | http://localhost:8080/ |
| Classement | http://localhost:8080/classement.php |
| Dashboard (admin, connexion requise) | http://localhost:8080/login.php |

## Base de données

La base est créée automatiquement au premier lancement à partir de `database/init.sql`.

Pour l’ouvrir avec DBeaver : hôte `localhost`, port `5432`, base `foodbattle`, utilisateur `foodbattle`.

Le script `init.sql` n'est exécuté que sur une base vide : après une modification de ce fichier, il faut repartir d'une base neuve avec `docker compose down -v` puis `docker compose up -d` (cela efface toutes les données, y compris les comptes admin).

### Mettre à jour une base existante

Quand le schéma de la base change, un script de migration permet de mettre à jour une base existante **sans perdre les données** (avis, votes, comptes admin).

Modération des avis (à lancer une seule fois) :

```bash
docker compose cp database/migration_review_moderation.sql db:/tmp/migration.sql
docker compose exec db psql -U <DB_USER> -d <DB_NAME> -f /tmp/migration.sql
```

Sous Git Bash, préfixer la seconde commande par `MSYS_NO_PATHCONV=1` (sinon le chemin `/tmp/...` est converti en chemin Windows).

Si la commande affiche `already exists` puis `ROLLBACK`, la migration est déjà appliquée : rien n'a été modifié.

## Espace admin

Les pages de gestion (dashboard, modification, suppression, modération) sont réservées aux admins : http://localhost:8080/login.php

Les avis publiés par les visiteurs sont **en attente** jusqu'à leur validation : ils n'apparaissent sur la fiche du plat et ne comptent dans la note moyenne qu'une fois publiés par un admin, depuis Dashboard → **Avis à valider**.

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

   ## Qualité du code (PSR-12)

Le code PHP suit la norme PSR-12, vérifiée avec PHP_CodeSniffer (règles dans `phpcs.xml`).

```bash
# installer l'outil (une seule fois)
docker run --rm -v "$PWD":/app composer:2 composer install

# vérifier le code
docker run --rm -v "$PWD":/app composer:2 composer lint

# corriger automatiquement ce qui peut l'être
docker run --rm -v "$PWD":/app composer:2 composer fix
```

