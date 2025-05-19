<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include '../../database/appelBase.php';

// if (isset($_POST['disponibilite_filter_value_include']) && isset($_POST['disponibilite_filter_value_exclude'])) {
//     $disponibilite = $_POST['disponibilite_filter_value_include']; // tableau de disponibilités
//     $exclude_disponibilite = $_POST['disponibilite_filter_value_exclude']; // tableau de indisponibilités

//     // Tu peux vérifier ce qui est reçu
//     foreach ($disponibilite as $val) {
//         var_dump($val);
//     }
//     foreach ($exclude_disponibilite as $val_exclude) {
//         var_dump($val_exclude);
//     }

//     // Appel de ta fonction avec le tableau reçu
//     $result_disponibilite = getCodeCisOfSearch("cisciodispo", "libelle_statut", $disponibilite, $exclude_disponibilite);
//     var_dump($result_disponibilite);
// } else {
//     echo "Aucune valeur de disponibilité reçue.";
// }

// $resultat_recherche_disponibilite = appelRecherche("disponibilite_filter_value" , ["cisciodispo", "libelle_statut"]);

function getResultOfSearch() {
    $medicaments = [];
    $codeCisList = [];

    // Liste des filtres disponibles : [nom POST => [table, colonne]]
    $filters = [
        'disponibilite' => ['cisciodispo', 'libelle_statut'],
        'valeurs_smr'   => ['cishassmr', 'valeur_smr'],
        'denomination'  => ['cis', 'denomination'],
        'titulaires'    => ['cis', 'titulaires'],
        'forme'         => ['cis', 'forme_phamaceutique'],
        'voie'          => ['cis', 'voie_administration'],
        'generique'     => ['cisgener', 'type_generique'],
        'substance'     => ['liste_substances', 'substances']
    ];

    foreach ($filters as $filterKey => $filterValue) {
        $table = $filterValue[0];
        $column = $filterValue[1];

        $includeKey = $filterKey . '_filter_value_include';
        $excludeKey = $filterKey . '_filter_value_exclude';

        $includeValues = !empty($_POST[$includeKey]) ? $_POST[$includeKey] : [];
        $excludeValues = !empty($_POST[$excludeKey]) ? $_POST[$excludeKey] : [];

        if (!empty($includeValues) || !empty($excludeValues)) {
            // Récupération des codes CIS filtrés
            array_push($codeCisList, getCodeCisOfSearch($table, $column, $includeValues, $excludeValues) );
            break; // un seul filtre à la fois
        }
    }
    $codeCisList = getEqualCodeCis($codeCisList);
    
    $medicaments = getMedecine($codeCisList);
    var_dump(count($medicaments));

    return $medicaments;
}

// var_dump($resultat_recherche_disponibilite);

// function appelRecherche($name, array $infosBD) {
//     var_dump($name.'_include');
//     if (isset($_POST[$name.'_include']) && isset($_POST[$name.'_exclude'])) {
//         $disponibilite = $_POST[$name.'_include']; // tableau de disponibilités
//         $exclude_disponibilite = $_POST[$name.'_exclude']; // tableau de indisponibilités

//         // Tu peux vérifier ce qui est reçu
//         foreach ($disponibilite as $val) {
//             var_dump($val);
//         }
//         foreach ($exclude_disponibilite as $val_exclude) {
//             var_dump($val_exclude);
//         }
//     }
//     // Appel de ta fonction avec le tableau reçu
//     $result_disponibilite = getCodeCisOfSearch($infosBD[0], $infosBD[1], $disponibilite, $exclude_disponibilite);
//     return $result_disponibilite;    
// }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la Recherche</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="./result.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
<?php 
include '../../includes/navigation.php';
?>

<div class="container" id="mainContainer">
    <div id="resultsSection">
        <h1>Résultats de la Recherche</h1>
        <table id="resultsTable" class="display">
            <thead>
            <tr>
                <th>Code CIS</th>
                <th>Dénomination</th>
                <th>Libelle</th>
                <th>Type générique</th>
                <th>Taux de remboursement</th>
            </tr>
            </thead>
            <tbody>
            <?php

            $medicaments = getResultOfSearch();

            foreach ($medicaments as $medicament) {
                echo "<tr data-id='{$medicament['code_cis']}' style='cursor:pointer;'>
                    <td>" . (!empty($medicament['code_cis']) ? $medicament['code_cis'] : '-') . "</td>
                    <td>" . (!empty($medicament['libelle']) ? $medicament['libelle'] : '-') . "</td>
                    <td>" . (!empty($medicament['denomination']) ? $medicament['denomination'] : '-') . "</td>
                    <td>" . (!empty($medicament['type_generique']) ? $medicament['type_generique'] : '-') . "</td>
                    <td>" . (!empty($medicament['taux_remboursement']) ? $medicament['taux_remboursement'] : '-') . "</td>
                </tr>";
            }

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
        }
            ?>
            </tbody>
        </table>
    </div>

    <div id="detailsPanel">
        <h2>Détails du Médicament</h2>
        <div id="detailsContent">Cliquez sur un médicament pour voir les détails.</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="result.js"></script>
</body>
</html>
