<?php
include '../../includes/navigation.php';
include '../../database/search_queries.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche Avancée de Médicaments</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

<div class="container">
    <h1>Recherche Avancée de Médicaments</h1>
    <form method="GET" action="search.php" class="search-form">
        <?php
        global $formes;
        global $statuts;
        global $substances;
        global $voies;

        $criteria = [
            "denomination" => "Dénomination",
            "forme_pharmaceutique" => "Forme pharmaceutique",
            "voie_administration" => "Voie d'administration",
            "titulaires" => "Titulaire",
            "libelle_statut" => "Statut",
            "denomination_substance" => "Substance active",
            "valeur_smr" => "Valeur SMR"
        ];

        foreach ($criteria as $field => $label) {
            echo "<div class='filter-group' id='{$field}_group'>
                    <label>$label :</label>
                    <div class='filter-options' id='{$field}_filters'>
                        <div class='filter-option'>
                            <select name='{$field}_filter_type[]'>
                                <option value='include'>Inclure</option>
                                <option value='exclude'>Exclure</option>
                            </select>";

            if ($field == "forme_pharmaceutique") {
                echo "<select name='{$field}_filter_value[]' class='multi-select'>";
                foreach ($formes as $forme) {
                    echo "<option value='{$forme}'>{$forme}</option>";
                }
                echo "</select>";
            } elseif ($field == "voie_administration") {
                echo "<select name='{$field}_filter_value[]' class='multi-select'>";
                foreach ($voies as $voie) {
                    echo "<option value='{$voie}'>{$voie}</option>";
                }
                echo "</select>";
            } elseif ($field == "libelle_statut") {
                echo "<select name='{$field}_filter_value[]' class='multi-select'>";
                foreach ($statuts as $statut) {
                    echo "<option value='{$statut}'>{$statut}</option>";
                }
                echo "</select>";
            } elseif ($field == "denomination_substance") {
                echo "<input type='text' name='{$field}_filter_value[]' placeholder='Substance active...'>";
            } else {
                echo "<input type='text' name='{$field}_filter_value[]' placeholder='Valeur...'>";
            }

            echo "<button type='button' class='remove-filter' data-field='{$field}'>×</button>
                    </div>
                    </div>
                    <button type='button' class='add-filter' data-field='{$field}'>+ Ajouter un filtre</button>
                  </div>";
        }
        ?>
        <button type="submit">Rechercher</button>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script src="search.js"></script>
</body>
</html>
