<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Avancée de Médicaments</title>
    
    <link rel="stylesheet" href="search.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
    
</head>
<body>
<?php
    include '../../database/appelBase.php';
?>

<div class="container">
   <div class="recherche"> 
        <h1>MEDIBI - Recherche Avancée de Médicaments</h1>
        <?php
        session_start();
        if (!empty($_SESSION['error'])) {
            echo "<p style='color:red; font-weight:bold;'>" . htmlspecialchars($_SESSION['error']) . "</p>";
            unset($_SESSION['error']);
        }
        ?>
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

            renderDualSelect("Dénomination", "denomination_filter_value", $denomination);
            renderDualSelect("Forme pharmaceutique", "forme_pharmaceutique_filter_value", $formes);
            renderDualSelect("Voie d'administration", "voie_administration_filter_value", recoverDistinct($voies));
            renderDualSelect("Titulaire", "titulaires_filter_value", $titulaires);
            renderDualSelect("Statut administratif", "libelle_statut_filter_value", $statuts);
            renderDualSelect("Valeur SMR", "valeurs_smr_filter_value", $valeurs_smr);
            renderDualSelect("Disponibilité du médicament", "disponibilite_filter_value", $libelle_statut);
            renderDualSelect("Condition de délivrance", "condition_delivrance_filter_value", $condition_delivrance);
            renderDualSelect("Médicaments génériques", "medicaments_generique_filter_value", $medicament_generique);
            renderDualSelect("Substances", "substances_filter_value", recoverDistinct($substances));
            
            ?>
            <button type="submit">Rechercher</button>
        </form>
    </div>
</div>
<?php
        function recoverDistinct($products) {
            $distinctProducts = [];

            foreach ($products as $row) {
                $parts = explode(';', $row);
                foreach ($parts as $product) {
                    $product = trim($product);

                    // Si la substance n'est pas vide et pas encore dans le tableau
                    if ($product !== '' && !in_array($product, $distinctProducts)) {
                        $distinctProducts[] = $product;
                    }
                }
            }

            return $distinctProducts;
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

        function renderDualSelect($label, $name, $values) {
            $includeKey = "{$name}_include";
            $excludeKey = "{$name}_exclude";
        
            $selectedInclude = $_POST[$includeKey] ?? [];
            $selectedExclude = $_POST[$excludeKey] ?? [];
        
            echo "<div class='filter-group'>";
            echo "<h3>{$label} :</h3>";
            echo "<div class='filter-row'>";
        
            // Inclure
            echo "<div class='filter-column'>";
            echo "<label>Inclure :</label>";
            echo "<select name='{$includeKey}[]' class='multi-select' multiple>";
            foreach ($values as $val) {
                $escapedVal = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                $selected = in_array($val, $selectedInclude) ? "selected" : "";
                $labelText = ($label === "Médicaments génériques") ? genericFormat($val) : $val;
                echo "<option value='{$escapedVal}' {$selected}>{$labelText}</option>";
            }
            echo "</select>";
            echo "</div>";
        
            // Exclure
            echo "<div class='filter-column'>";
            echo "<label>Exclure :</label>";
            echo "<select name='{$excludeKey}[]' class='multi-select' multiple>";
            foreach ($values as $val) {
                $escapedVal = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
                $selected = in_array($val, $selectedExclude) ? "selected" : "";
                $labelText = ($label === "Médicaments génériques") ? genericFormat($val) : $val;
                echo "<option value='{$escapedVal}' {$selected}>{$labelText}</option>";
            }
            echo "</select>";
            echo "</div>";
        
            echo "</div></div>";
        }

?>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="search.js"></script>
</body>
</html>
