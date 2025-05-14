CREATE VIEW medicaments_prescrits_par_mois
AS
SELECT 
    SAE_S6_2025.cis.denomination, 
    qte AS nombre_prescrit,
    YEAR(SAE_S6_2025.ordonnance.date_prescription) AS annee,
    MONTH(SAE_S6_2025.ordonnance.date_prescription) AS mois
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ordonnance
    ON SAE_S6_2025.ordonnance.id = SAE_S6_2025.ordonnancelignes.id_ordonnance
INNER JOIN SAE_S6_2025.ciscip
    ON SAE_S6_2025.ciscip.id = SAE_S6_2025.ordonnancelignes.id_ciscip
INNER JOIN SAE_S6_2025.cis
    ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
GROUP BY 
    YEAR(SAE_S6_2025.ordonnance.date_prescription), 
    MONTH(SAE_S6_2025.ordonnance.date_prescription),
    SAE_S6_2025.cis.code_cis
ORDER BY nombre_prescrit DESC

CREATE VIEW somme_commandee_labo
AS 
SELECT 
    SAE_S6_2025.cis.titulaires AS laboratoire, 
    SUM(SAE_S6_2025.ciscip.prix_medicament_a) AS somme
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ordonnance
    ON SAE_S6_2025.ordonnance.id = SAE_S6_2025.ordonnancelignes.id_ordonnance
INNER JOIN SAE_S6_2025.ciscip
    ON SAE_S6_2025.ciscip.id = SAE_S6_2025.ordonnancelignes.id_ciscip
INNER JOIN SAE_S6_2025.cis
    ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
GROUP BY 
    SAE_S6_2025.cis.titulaires
ORDER BY somme DESC
LIMIT 10

CREATE VIEW quantite_commandee_labo
AS
SELECT 
    SAE_S6_2025.cis.titulaires AS laboratoire, 
    SUM(qte) AS quantite
FROM SAE_S6_2025.ordonnancelignes
INNER JOIN SAE_S6_2025.ordonnance
    ON SAE_S6_2025.ordonnance.id = SAE_S6_2025.ordonnancelignes.id_ordonnance
INNER JOIN SAE_S6_2025.ciscip
    ON SAE_S6_2025.ciscip.id = SAE_S6_2025.ordonnancelignes.id_ciscip
INNER JOIN SAE_S6_2025.cis
    ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
GROUP BY 
    SAE_S6_2025.cis.titulaires
ORDER BY quantite DESC
LIMIT 10

CREATE VIEW medicaments_prescrits_par_ville
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

CREATE VIEW repartion_medicament_generique
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



CREATE VIEW comparaison_prix_medicaments
AS
SELECT SAE_S6_2025.cis.code_cis, denomination, prix_medicament_a, titulaires
FROM SAE_S6_2025.cis
INNER JOIN SAE_S6_2025.ciscip
ON SAE_S6_2025.ciscip.code_cis = SAE_S6_2025.cis.code_cis
WHERE prix_medicament_a != ''

CREATE VIEW dernieres_alertes_de_securite
AS
SELECT SAE_S6_2025.cis.denomination, date_fin, texte 
FROM SAE_S6_2025.cisinfosimportantes
INNER JOIN SAE_S6_2025.cis
ON SAE_S6_2025.cis.code_cis = SAE_S6_2025.cisinfosimportantes.code_cis
WHERE date_fin >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m-01')     AND date_fin < DATE_FORMAT(NOW(), '%Y-%m-01')

-- Vue pour l'affichage des resultats d'un medicament
CREATE VIEW affichage_resultat_medicament
AS 
SELECT 
    cis.code_cis, 
    denomination, 
    titulaires, 
    forme_phamaceutique, 
    voie_administration, 
    cis.statut_administratif, 
    etat_commercialisation, 
    taux_remboursement, 
    smr.valeurs_smr, 
    GROUP_CONCAT(DISTINCT compo.substances SEPARATOR '; ') AS substances, 
    prix_medicament_b AS prix, 
    ciscip.reference_dosage, 
    CASE 
        WHEN type_generique = 0 THEN 'Médicaments de marques'
        WHEN type_generique IN (1, 2, 4) THEN 'Médicaments génériques'
    END AS type_medicament
FROM SAE_S6_2025.cis
LEFT JOIN SAE_S6_2025.ciscip ON cis.code_cis = ciscip.code_cis
LEFT JOIN SAE_S6_2025.cisgener ON cis.code_cis = cisgener.code_cis
LEFT JOIN SAE_S6_2025_E.vue_smr AS smr ON cis.code_cis = smr.code_cis
LEFT JOIN SAE_S6_2025_E.vue_compo AS compo ON cis.code_cis = compo.code_cis
GROUP BY 
    cis.code_cis, 
    denomination, 
    titulaires, 
    forme_phamaceutique, 
    voie_administration, 
    cis.statut_administratif, 
    etat_commercialisation, 
    taux_remboursement, 
    smr.valeurs_smr, 
    compo.reference_dosage, 
    type_generique,
    prix;


CREATE VIEW vue_compo AS
SELECT code_cis, GROUP_CONCAT(DISTINCT denomination_substance SEPARATOR '; ') AS substances
FROM SAE_S6_2025.ciscompo
GROUP BY code_cis;


CREATE VIEW vue_smr AS
SELECT code_cis, valeur_smr AS valeurs_smr
FROM SAE_S6_2025.cishassmr
GROUP BY code_cis;
