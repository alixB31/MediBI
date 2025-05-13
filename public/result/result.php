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
<?php include '../../includes/navigation.php'; ?>

<div class="container" id="mainContainer">
    <div id="resultsSection">
        <h1>Résultats de la Recherche</h1>
        <table id="resultsTable" class="display">
            <thead>
            <tr>
                <th>ID</th>
                <th>Dénomination</th>
                <th>Forme Pharmaceutique</th>
                <th>Voie d'Administration</th>
                <th>Titulaire</th>
                <th>Statut</th>
                <th>Substance Active</th>
                <th>Valeur SMR</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $medicaments = [
                ["id" => 1, "denomination" => "Doliprane", "forme" => "Comprimé", "voie" => "Orale", "titulaire" => "Sanofi", "statut" => "Autorisé", "substance" => "Paracétamol", "smr" => "Important"],
                ["id" => 2, "denomination" => "Efferalgan", "forme" => "Effervescent", "voie" => "Orale", "titulaire" => "UPSA", "statut" => "Autorisé", "substance" => "Paracétamol", "smr" => "Modéré"]
            ];
            foreach ($medicaments as $medicament) {
                echo "<tr data-id='{$medicament['id']}' style='cursor:pointer;'>
                        <td>{$medicament['id']}</td>
                        <td>{$medicament['denomination']}</td>
                        <td>{$medicament['forme']}</td>
                        <td>{$medicament['voie']}</td>
                        <td>{$medicament['titulaire']}</td>
                        <td>{$medicament['statut']}</td>
                        <td>{$medicament['substance']}</td>
                        <td>{$medicament['smr']}</td>
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
