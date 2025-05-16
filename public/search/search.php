<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Avancée de Médicaments</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="search.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
</head>
<body>
<?php
include '../../includes/navigation.php';
include '../../database/appelBase.php';
?>

<div class="container">
    <h1>Recherche Avancée de Médicaments</h1>
    <form method="GET" action="search.php" class="search-form">
        <?php
        $denomination = getDistinctValues("denomination", "cis");
        $formes = getDistinctValues("forme_phamaceutique", "cis");
        $voies = getDistinctValues("voie_administration", "cis");
        $titulaires = getDistinctValues("titulaires", "cis");
        $statuts = getDistinctValues("statut_administratif", "cis");
        $valeurs_smr = getDistinctValues("valeur_smr", "cishassmr");
        $libelle_statut = getDistinctValues("libelle_statut", "cisciodispo");
        $condition_delivrance = getDistinctValues("condition", "ciscpd");
        $medicament_generique = getDistinctValuesGeneric("type_generique", "cisgener");
        $substances = getDistinctValuesFromView("substances","liste_substances");


        function renderDualSelect($label, $name, $values) {
            echo "<div class='filter-group'>";
            echo "<label>{$label} :</label>";
            echo "<div class='filter-row'>";
            
            // Inclure
            echo "<div class='filter-column'>";
            echo "<label>Inclure :</label>";
            echo "<select name='{$name}_include[]' class='multi-select' multiple>";
            foreach ($values as $val) {
                echo "<option value='{$val}'>{$val}</option>";
            }
            echo "</select>";
            echo "</div>";

            // Exclure
            echo "<div class='filter-column'>";
            echo "<label>Exclure :</label>";
            echo "<select name='{$name}_exclude[]' class='multi-select' multiple>";
            foreach ($values as $val) {
                echo "<option value='{$val}'>{$val}</option>";
            }
            echo "</select>";
            echo "</div>";

            echo "</div></div>";
        }

        renderDualSelect("Dénomination", "denomination_filter_value", $denomination);
        renderDualSelect("Forme pharmaceutique", "forme_pharmaceutique_filter_value", $formes);
        renderDualSelect("Voie d'administration", "voie_administration_filter_value", $voies);
        renderDualSelect("Titulaire", "titulaires_filter_value", $titulaires);
        renderDualSelect("Statut administratif", "libelle_statut_filter_value", $statuts);
        renderDualSelect("Valeur SMR", "valeurs_smr_filter_value", $valeurs_smr);
        renderDualSelect("Disponibilité du médicament", "disponibilite_filter_value", $libelle_statut);
        renderDualSelect("Condition de délivrance", "condition_delivrance_filter_value", $condition_delivrance);
        renderDualSelect("Médicaments génériques", "medicaments_generique_filter_value", $medicament_generique);
        renderDualSelect("Substances", "substances_filter_value", $substances);
        ?>
        <button type="submit">Rechercher</button>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="search.js"></script>
</body>
</html>
