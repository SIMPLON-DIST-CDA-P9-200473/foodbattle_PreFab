-- À lancer UNE SEULE FOIS sur une base créée avec l'ancien init.sql.
-- Si on la relance, l'étape 4 échoue ("already exists") : cela veut dire qu'elle est déjà appliquée.

BEGIN;

-- 1. Traduire les anciens statuts
UPDATE ratings SET status = 'publie'     WHERE status = 'published';
UPDATE ratings SET status = 'en_attente' WHERE status = 'pending';

-- 2. Avis piégés : on les refuse au lieu de les publier.
UPDATE ratings SET status = 'refuse'
WHERE comment ~ '[<>]' OR nickname ~ '[<>]';

-- 3. Nouvelle valeur par défaut : un nouvel avis attend la relecture
ALTER TABLE ratings ALTER COLUMN status SET DEFAULT 'en_attente';

-- 4. Seuls les trois statuts prévus sont autorisés.
ALTER TABLE ratings ADD CONSTRAINT ratings_status_check
    CHECK (status IN ('en_attente', 'publie', 'refuse'));

COMMIT;