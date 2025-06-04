<?php
include '../../database/appelBase.php';
session_start();
$hasInput = false;
foreach ($_POST as $key => $value) {
    if (is_array($value) && count(array_filter($value)) > 0) {
        $hasInput = true;
        break;
    }
}

if (!$hasInput) {
    $_SESSION['error'] = "Veuillez sélectionner au moins un critère de recherche.";
    header("Location: ../search/index.php");
    exit;
}
function genericFormat($typesMedecine) {
    $result = "";
    switch($typesMedecine) {
        case "0" :
            $result = "princeps";
            break;
        case "1" : 
            $result = "générique";
            break;
        case "2":
            $result = "générique par complémentarité posologique";
            break;
        case "4" : 
            $result = "générique substituable";
            break;
    }
    return $result;
}
function getResultOfSearch() {
    global $pdoTable, $pdoVue;
    $medicaments = [];
    $codeCisList = [];

    // Liste des filtres disponibles : [nom POST => [table, colonne]]
    $filters = [
        'disponibilite' => ['cisciodispo', 'libelle_statut'],
        'valeurs_smr'   => ['cishassmr', 'valeur_smr'],
        'denomination'  => ['cis', 'denomination'],
        'titulaires'    => ['cis', 'titulaires'],
        'forme_pharmaceutique' => ['cis', 'forme_phamaceutique'],
        'voie_administration'  => ['cis', 'voie_administration'],
        'libelle_statut'       => ['cis', 'statut_administratif'],
        'condition_delivrance' => ['ciscpd', 'condition'],
        'medicaments_generique' => ['cisgener', 'type_generique'],
        'substances'            => ['liste_substances', 'substances']
    ];

    // On regroupe les filtres par table
    $groupedFilters = [];

    foreach ($filters as $filterKey => [$table, $column]) {
        $includeKey = $filterKey . '_filter_value_include';
        $excludeKey = $filterKey . '_filter_value_exclude';

        $includeValues = isset($_POST[$includeKey]) ? $_POST[$includeKey] : [];
        $excludeValues = isset($_POST[$excludeKey]) ? $_POST[$excludeKey] : [];

        if (isset($includeValues)) {
            $groupedFilters[$table]['include'][$column] = $includeValues;
        }

        if (isset($excludeValues)) {
            $groupedFilters[$table]['exclude'][$column] = $excludeValues;
        }
    }

    // Pour chaque table, on applique les filtres groupés
    foreach ($groupedFilters as $table => $fields) {
        $include = $fields['include'] ?? [];
        $exclude = $fields['exclude'] ?? [];
        if(hasNoEmptyFilters($include) || hasNoEmptyFilters($exclude)) {
            if($table === "liste_substances") {
                $cis = getCodeCisOfSearchMulti($table, $include, $exclude, $pdoVue);
            } else {
                $cis = getCodeCisOfSearchMulti($table, $include, $exclude, $pdoTable);
            }
        }
        if (!empty($cis)) {
            $codeCisList[] = $cis;
        }
    }

    // Intersection des résultats entre toutes les tables
    $codeCisList = getEqualCodeCis($codeCisList);

    // Récupération des données complètes
    $medicaments = getMedecine($codeCisList);
    return $medicaments;
}

function hasNoEmptyFilters(array $filters): bool {
    foreach ($filters as $values) {
        if (!empty($values)) {
            return true;
        }
    }
    return false;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la Recherche</title>
    <link rel="stylesheet" href="./result.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container" id="mainContainer">
    <div id="resultsSection">
        <h1>Résultats de la Recherche</h1>
        <table id="resultsTable" class="display">
            <thead>
            <tr>
                <th>Code CIS</th>
                <th>Dénomination</th>
                <th>Quantité</th>
                <th>Type générique</th>
                <th>Taux de remboursement</th>
            </tr>
            </thead>
            <tbody>
            <?php

            $medicaments = getResultOfSearch();

                

            foreach ($medicaments as $medicament) {
                $medicament['type_generique'] = genericFormat($medicament['type_generique']);
                echo "<tr data-id='{$medicament['code_cis']}' style='cursor:pointer;'>
                    <td>" . (isset($medicament['code_cis']) ? $medicament['code_cis'] : '-') . "</td>
                    <td>" . (isset($medicament['denomination']) ? $medicament['denomination'] : '-') . "</td>
                    <td>" . (isset($medicament['libelle']) ? $medicament['libelle'] : '-') . "</td>
                    <td>" . (isset($medicament['type_generique']) ? $medicament['type_generique'] : '-') . "</td>
                    <td>" . (isset($medicament['taux_remboursement']) ? $medicament['taux_remboursement'] : '-') . "</td>
                </tr>";
            }

            
            ?>
            </tbody>
        </table>
        <form method="post" action="../search/index.php">
            <button class="margin-bottom" type="submit">Nouvelle recherche</button>
        </form>
        <form id="modify-search" method="POST" action="../search/index.php">
            <?php
            foreach ($_POST as $key => $value) {
                if (is_array($value)) {
                    foreach ($value as $v) {
                        echo "<input type='hidden' name='{$key}[]' value=\"" . htmlspecialchars($v) . "\">";
                    }
                } else {
                    echo "<input type='hidden' name='{$key}' value=\"" . htmlspecialchars($value) . "\">";
                }
            }
            ?>
            <button type="submit">Modifier la recherche</button>
        </form>
    </div>

    <div id="detailsPanel">
        <h2>Détails du Médicament</h2>
        <div id="detailsContent">Cliquez sur un médicament pour voir les détails.</div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="result.js"></script>
<?php
function getEqualCodeCis($listResult) {
    $resultats = [];

    foreach ($listResult as $tableau1) {
        $intersection = $tableau1;

        // On compare ce tableau avec tous les autres tableaux
        foreach ($listResult as $tableau2) {
            $intersection = array_intersect($intersection, $tableau2);
        }

        // On stocke l'intersection des éléments pour ce tableau
        foreach($intersection as $valuesEquals) {
            $resultats[] = $valuesEquals;
        }
    }
    return $resultats;
} ?>
</body>
</html>
