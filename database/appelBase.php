<?php
include __DIR__ . '/db_connect.php';

global $pdoTable, $pdoVue;

function getMedicaments() {
    global $pdoTable, $pdoVue;

    $filters = $_GET;
    // Map labels to codes for medicaments_generique filters
    if (isset($filters['medicaments_generique_filter_value_include'])) {
        $mappedValues = [];
        foreach ($filters['medicaments_generique_filter_value_include'] as $label) {
            if ($label === "Médicaments de marques") {
                $mappedValues[] = '0';
            } elseif ($label === "Médicaments génériques") {
                $mappedValues = array_merge($mappedValues, ['1', '2', '4']);
            }
        }
        $filters['medicaments_generique_filter_value_include'] = $mappedValues;
    }

    if (isset($filters['medicaments_generique_filter_value_exclude'])) {
        $mappedValues = [];
        foreach ($filters['medicaments_generique_filter_value_exclude'] as $label) {
            if ($label === "Médicaments de marques") {
                $mappedValues[] = '0';
            } elseif ($label === "Médicaments génériques") {
                $mappedValues = array_merge($mappedValues, ['1', '2', '4']);
            }
        }
        $filters['medicaments_generique_filter_value_exclude'] = $mappedValues;
    }

    $baseQuery = "SELECT DISTINCT cis.code_cis, cis.denomination, cis.titulaires, cis.voie_administration, cis.statut_administratif, ciscip.prix_medicament_b
                  FROM cis";

    $joins = [];
    $conditions = [];
    $params = [];

    // Mappings
    $filterMap = [
        'denomination_filter_value' => 'denomination',
        'forme_pharmaceutique_filter_value' => 'forme_phamaceutique',
        'voie_administration_filter_value' => 'voie_administration',
        'titulaires_filter_value' => 'titulaires',
        'libelle_statut_filter_value' => 'statut_administratif',
        'disponibilite_filter_value' => ['table' => 'cisciodispo', 'column' => 'libelle_statut', 'join' => 'cis.code_cis = cisciodispo.code_cis'],
        'valeurs_smr_filter_value' => ['table' => 'cishassmr', 'column' => 'valeur_smr', 'join' => 'cis.code_cis = cishassmr.code_cis'],
        'condition_delivrance_filter_value' => ['table' => 'ciscpd', 'column' => 'condition', 'join' => 'cis.code_cis = ciscpd.code_cis'],
        'medicaments_generique_filter_value' => ['table' => 'cisgener', 'column' => 'type_generique', 'join' => 'cis.code_cis = cisgener.code_cis'],
        'substances_filter_value' => ['table' => 'ciscompo', 'column' => 'denomination_substance', 'join' => 'cis.code_cis = ciscompo.code_cis'],

    ];

    // Jointures fixes
    $joins['ciscip'] = "LEFT JOIN ciscip ON cis.code_cis = ciscip.code_cis";

    $likeFilters = ['substances_filter_value', 'voie_administration_filter_value'];


    foreach ($filters as $key => $values) {
        if (!is_array($values) || empty($values)) continue;

        $include = substr($key, -8) === '_include';
        $exclude = substr($key, -8) === '_exclude';
        $filterKey = str_replace(['_include', '_exclude'], '', $key);

        if (!isset($filterMap[$filterKey])) continue;

        $filterInfo = $filterMap[$filterKey];
        $alias = 'cis';

        if (is_array($filterInfo)) {
            $table = $filterInfo['table'];
            $column = $filterInfo['column'];
            $alias = $table;

            if (!isset($joins[$table])) {
                $joins[$table] = "LEFT JOIN $table ON " . $filterInfo['join'];
            }
        } else {
            $column = $filterInfo;
        }

        if (in_array($filterKey, $likeFilters)) {
            // Construction de la condition avec LIKE pour chaque valeur
            $likeConditions = [];
            foreach ($values as $val) {
                $likeConditions[] = "$alias.`$column` " . ($include ? "LIKE ?" : "NOT LIKE ?");
                $params[] = "%$val%";
            }
            // Combine conditions avec OR (pour include) ou AND (pour exclude)
            $condition = $include ? '(' . implode(' OR ', $likeConditions) . ')' : '(' . implode(' AND ', $likeConditions) . ')';
        } else {
            // Construction classique IN / NOT IN
            $placeholders = implode(',', array_fill(0, count($values), '?'));
            $condition = "$alias.`$column` " . ($include ? "IN" : "NOT IN") . " ($placeholders)";
            $params = array_merge($params, $values);
        }

        $conditions[] = $condition;
    }


    foreach ($joins as $join) {
        $baseQuery .= " $join";
    }

    if (!empty($conditions)) {
        $baseQuery .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $pdoTable->prepare($baseQuery);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Substances via pdoVue
    $cisCodes = array_column($results, 'code_cis');

    if (!empty($cisCodes)) {
        $placeholders = implode(',', array_fill(0, count($cisCodes), '?'));
        $stmtSub = $pdoVue->prepare("SELECT code_cis, substances FROM liste_substances WHERE code_cis IN ($placeholders)");
        $stmtSub->execute($cisCodes);
        $substancesData = $stmtSub->fetchAll(PDO::FETCH_KEY_PAIR);
    } else {
        $substancesData = [];
    }

    foreach ($results as &$medicament) {
        $cis = $medicament['code_cis'];
        $medicament['substances'] = $substancesData[$cis] ?? '-';
        $medicament['prix'] = $medicament['prix_medicament_b'] ?? '-';
        unset($medicament['prix_medicament_b']);
    }

    return $results;
}

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

function getMedicamentByDisponibilite($listValueInclude, $listValueExclude)
{
    global $pdoTable;
    $sql = "SELECT code_cis FROM cisciodispo WHERE 1"; // condition par défaut

    // Ajouter la condition IN pour les valeurs à inclure (libelle_statut)
    if (count($listValueInclude) > 0) {
        $placeholdersInclude = implode(',', array_fill(0, count($listValueInclude), '?'));
        $sql .= " AND libelle_statut IN ($placeholdersInclude)";
    }

    // Ajouter la condition NOT IN pour les valeurs à exclure (libelle_statut)
    if (count($listValueExclude) > 0) {
        $placeholdersExclude = implode(',', array_fill(0, count($listValueExclude), '?'));
        $sql .= " AND libelle_statut NOT IN ($placeholdersExclude)";
    }

    $query = $pdoTable->prepare($sql);

    $index = 1;
    foreach ($listValueInclude as $value) {
        $query->bindValue($index++, $value, PDO::PARAM_STR);
    }

    foreach ($listValueExclude as $value) {
        $query->bindValue($index++, $value, PDO::PARAM_STR);
    }

    $query->execute();

    return $query->fetchAll(PDO::FETCH_COLUMN);
}

?>
