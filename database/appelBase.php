<?php
include __DIR__ . '/db_connect.php';

global $pdoTable, $pdoVue;

function getMedicamentById($id)
{
    global $pdoVue;

    $query = $pdoVue->prepare("SELECT * FROM affichage_resultat_medicament WHERE code_cis = :id LIMIT 1");
    $query->execute(['id' => $id]);
    $medicament = $query->fetch(PDO::FETCH_ASSOC);

    return $medicament;
}

function getDistinctValues($column, $table, $pdo)
{
    $query = $pdo->prepare("SELECT DISTINCT `$column` FROM `$table` WHERE `$column` IS NOT NULL");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getCodeCisOfSearchMulti($table, $includeFilters = [], $excludeFilters = []) {
    global $pdoTable;

    $allowedTables = ['cis', 'cisciodispo', 'cishassmr', 'cisgener', 'ciscpd', 'liste_substances'];
    $allowedColumns = ['titulaires', 'denomination', 'forme_phamaceutique', 'voie_administration', 'statut_administratif', 'valeur_smr', 'libelle_statut', 'condition', 'type_generique', 'substances'];

    if (!in_array($table, $allowedTables)) {
        throw new InvalidArgumentException("Table non autorisée.");
    }

    $sql = "SELECT code_cis FROM $table WHERE 1=1";
    $params = [];

    // Filtres d'inclusion
    foreach ($includeFilters as $column => $values) {
        if (!in_array($column, $allowedColumns) || !is_array($values)) continue;

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $sql .= " AND $column IN ($placeholders)";
        $params = array_merge($params, $values);
    }

    // Filtres d'exclusion
    foreach ($excludeFilters as $column => $values) {
        if (!in_array($column, $allowedColumns) || !is_array($values)) continue;

        $placeholders = implode(',', array_fill(0, count($values), '?'));
        $sql .= " AND $column NOT IN ($placeholders)";
        $params = array_merge($params, $values);
    }

    $query = $pdoTable->prepare($sql);
    foreach ($params as $index => $value) {
        $query->bindValue($index + 1, $value, PDO::PARAM_STR);
    }

    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getMedecine($code_cis) {
    global $pdoVue;
       if (empty($code_cis)) {
        return [];
    }
    $placeholders = implode(',', array_fill(0, count($code_cis), '?'));

    $sql = "
        SELECT code_cis,
            denomination,
            libelle,
            type_generique,
            taux_remboursement
        FROM affichage_resultat_medicament
        WHERE code_cis IN ($placeholders)
        GROUP BY code_cis
    ";

    $query = $pdoVue->prepare($sql);

    foreach ($code_cis as $index => $value) {
        $query->bindValue($index + 1, $value, PDO::PARAM_STR);
    }

    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);
}
function getDetail($code_cis) {
    global $pdoTable;

    $sql = "
        SELECT cis.code_cis, titulaires, substances, date_amm, cis.denomination, forme_phamaceutique, voie_administration, cis.statut_administratif, nature_composant, valeur_smr, etat_commercialisation, taux_remboursement, prix_medicament_b, reference_dosage, lien_bpdm, cisciodispo.libelle_statut, ciscpd.condition, type_generique, libelle_asmr, texte, lien_page_avis_ct, substances 
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
        LEFT JOIN SAE_S6_2025_E.liste_substances ON liste_substances.code_cis = cis.code_cis
        WHERE cis.code_cis = :code_cis
        GROUP BY code_cis
    ";

    $query = $pdoTable->prepare($sql);

    $query->bindParam(':code_cis', $code_cis, PDO::PARAM_STR);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_ASSOC);

}

?>
