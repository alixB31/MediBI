
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
include '../../database/appelBase.php';

$medicaments = getMedicaments();
?>

<div class="container" id="mainContainer">
    <div id="resultsSection">
        <h1>Résultats de la Recherche</h1>
        <table id="resultsTable" class="display">
            <thead>
            <tr>
                <th>Code CIS</th>
                <th>Dénomination</th>
                <th>Titulaire</th>
                <th>Voie d'Administration</th>
                <th>Substance Active</th>
                <th>Prix</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($medicaments as $medicament) {
                echo "<tr data-id='{$medicament['code_cis']}' style='cursor:pointer;'>
                        <td>{$medicament['code_cis']}</td>
                        <td>{$medicament['denomination']}</td>
                        <td>{$medicament['titulaires']}</td>
                        <td>{$medicament['voie_administration']}</td>
                        <td>{$medicament['substances']}</td>
                        <td>" . (!empty($medicament['prix']) ? $medicament['prix'] . "€" : "-") . "</td>
                    </tr>";
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
