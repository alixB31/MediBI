
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
SELECT sum(prix_medicament_a * qte) AS prix_total, SAE_S6_2025.cis.titulaires
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ordonnancelignes.id_ciscip = SAE_S6_2025.ciscip.id
INNER JOIN SAE_S6_2025.cis 
ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
GROUP BY SAE_S6_2025.cis.titulaires