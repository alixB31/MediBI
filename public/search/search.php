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
    <form method="POST" action="../result/result.php" class="search-form">
        <?php
        global $pdoVue, $pdoTable;

        $denomination = getDistinctValues("denomination", "cis", $pdoTable);
        $formes = getDistinctValues("forme_phamaceutique", "cis", $pdoTable);
        $voies = getDistinctValues("voie_administration", "cis", $pdoTable);
        $titulaires = getDistinctValues("titulaires", "cis", $pdoTable);
        $statuts = getDistinctValues("statut_administratif", "cis", $pdoTable);
        $valeurs_smr = getDistinctValues("valeur_smr", "cishassmr", $pdoTable);
        $libelle_statut = getDistinctValues("libelle_statut", "cisciodispo", $pdoTable);
        $condition_delivrance = getDistinctValues("condition", "ciscpd", $pdoTable);
        $medicament_generique = getDistinctValues("type_generique", "cisgener", $pdoTable);
        $substances = getDistinctValues("substances","liste_substances", $pdoVue);

        function recoverDistinct($products) {
            $distinctProducts = [];

            foreach ($products as $row) {
                // Découpe les substances séparées par des point virgules
                $parts = explode(';', $row);
                foreach ($parts as $product) {
                    // Nettoie les espaces
                    $product = trim($product);

                    // Si la substance n'est pas vide et pas encore dans le tableau
                    if ($product !== '' && !in_array($product, $distinctProducts)) {
                        $distinctProducts[] = $product;
                    }
                }
            }

            return $distinctProducts;
        }

        function mapGeneriqueLabels($typesMedecine) {
            $labels = [];

            foreach ($typesMedecine as $typeMedecine) {
                if (in_array($typeMedecine, [0])) {
                    $label = "Médicaments de marques";
                } elseif (in_array($typeMedecine, [1, 2, 4])) {
                    $label = "Médicaments génériques";
                } else {
                    $label = "Type $typeMedecine";
                }

                // Ajout dans le tableau s’il n’existe pas déjà
                if (!in_array($label, $labels)) {
                    $labels[] = $label;
                }
            }

            return $labels;
        }

        function renderDualSelect($label, $name, $values) {
            echo "<div>";
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
        renderDualSelect("Voie d'administration", "voie_administration_filter_value", recoverDistinct($voies));
        renderDualSelect("Titulaire", "titulaires_filter_value", $titulaires);
        renderDualSelect("Statut administratif", "libelle_statut_filter_value", $statuts);
        renderDualSelect("Valeur SMR", "valeurs_smr_filter_value", $valeurs_smr);
        renderDualSelect("Disponibilité du médicament", "disponibilite_filter_value", $libelle_statut);
        renderDualSelect("Condition de délivrance", "condition_delivrance_filter_value", $condition_delivrance);
        renderDualSelect("Médicaments génériques", "medicaments_generique_filter_value", mapGeneriqueLabels($medicament_generique));
        renderDualSelect("Substances", "substances_filter_value", recoverDistinct($substances));
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
