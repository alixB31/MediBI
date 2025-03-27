
-- Créeation de la vue permettant de récupérer la somme des médicaments prescrits par laboratoire
CREATE VIEW vente_par_labo
AS 
SELECT sum(qte), SAE_S6_2025.cis.titulaires
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ordonnancelignes.id_ciscip = SAE_S6_2025.ciscip.id
INNER JOIN SAE_S6_2025.cis 
ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
GROUP BY SAE_S6_2025.cis.titulaires
ORDER BY sum(qte) DESC

-- Création de la vue permettant de récupérer la somme des médicaments prescrits 
CREATE VIEW vente_medicaments
AS 
SELECT sum(qte), SAE_S6_2025.cis.code_cis, SAE_S6_2025.cis.denomination
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ordonnancelignes.id_ciscip = SAE_S6_2025.ciscip.id
INNER JOIN SAE_S6_2025.cis 
ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
GROUP BY SAE_S6_2025.cis.code_cis
ORDER BY sum(qte) DESC

-- Requête qui permet de récupérer les laboratoires a qui on acheter la plus grande somme de médicaments qu'on a prescrit
CREATE VIEW achat_laboratoire_argent
AS
SELECT SUM(prix_medicament_a * qte) AS prix_total, SAE_S6_2025.cis.titulaires
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ordonnancelignes.id_ciscip = SAE_S6_2025.ciscip.id
INNER JOIN SAE_S6_2025.cis 
ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
WHERE prix_medicament_a IS NOT NULL AND prix_medicament_a <> ''
GROUP BY SAE_S6_2025.cis.titulaires
ORDER BY prix_total DESC

-- Vue retournant les médicaments les plus vendus par ville
CREATE VIEW medicament_vendu_par_ville
AS
SELECT sum(qte) as quantite, denomination, TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(SUBSTRING_INDEX(adresse_postale, ',', -2), ',', 1), ' ',-1)) AS ville
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ordonnance
ON SAE_S6_2025.ordonnance.id = SAE_S6_2025.ordonnancelignes.id_ordonnance
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ciscip.id = SAE_S6_2025.ordonnancelignes.id_ciscip
INNER JOIN SAE_S6_2025.cis
ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
INNER JOIN SAE_S6_2025.patients
ON SAE_S6_2025.patients.numero_securite_sociale = SAE_S6_2025.ordonnance.numero_securite_sociale
GROUP BY ville, SAE_S6_2025.ciscip.code_cis
ORDER BY quantite DESC

-- Vue retournant la part d emedicaments génériques et la part de médicaments de marque
CREATE VIEW part_medicament_generiques
AS
SELECT sum(qte) AS quantite, CASE 
        WHEN type_generique = 0 THEN 'Médicaments de marques'
        WHEN type_generique IN (1, 2, 4) THEN 'Médicaments génériques'
    END AS type_medicaments
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ciscip.id = SAE_S6_2025.ordonnancelignes.id_ciscip
INNER JOIN SAE_S6_2025.cisgener
ON SAE_S6_2025.cisgener.code_cis = SAE_S6_2025.ciscip.code_cis
GROUP BY     CASE 
        WHEN type_generique = 0 THEN 'Médicaments de marques'
        WHEN type_generique IN (1, 2, 4) THEN 'Médicaments génériques'
    END