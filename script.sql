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
SELECT cis.code_cis, denomination, libelle, voie_administration, type_generique, taux_remboursement
FROM SAE_S6_2025.cis
INNER JOIN SAE_S6_2025.ciscip ON cis.code_cis = ciscip.code_cis
INNER JOIN SAE_S6_2025.cisgener ON cis.code_cis = cisgener.code_cis




SELECT titulaires, cis.denomination, forme_phamaceutique, voie_administration, cis.statut_administratif, nature_composant, valeur_smr, etat_commercialisation, taux_remboursement, prix_medicament_b, reference_dosage, lien_bpdm, libelle_statut, ciscpd.condition, type_generique, libelle_asmr, texte, lien_page_avis_ct 
FROM cis
LEFT JOIN cisciodispo ON cis.code_cis = cisciodispo.code_cis
LEFT JOIN ciscip ON cis.code_cis = ciscip.code_cis
LEFT JOIN ciscompo ON cis.code_cis = ciscompo.code_cis
LEFT JOIN ciscpd ON cis.code_cis = ciscpd.code_cis
LEFT JOIN cisgener ON cis.code_cis = cisgener.code_cis
LEFT JOIN cishasasmr ON cis.code_cis = cishasasmr.code_cis
LEFT JOIN cishassmr ON cis.code_cis = cishassmr.code_cis
LEFT JOIN cisinfosimportantes ON cis.code_cis = cisinfosimportantes.code_cis
LEFT JOIN cismitm ON cis.code_cis = cismitm.code_cis
LEFT JOIN haslienpagect ON cishasasmr.code_dossier_has = haslienpagect.code_dossier_has
WHERE cis.code_cis = '68546034'