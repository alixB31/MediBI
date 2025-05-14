<link rel="stylesheet" href="./result.css">

<?php
include '../../database/appelBase.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $info = getMedicamentById($id);

    if ($info) {
        echo "<table class='details-table'>";
        echo "<tr><th>Code CIS</th><td>" . (!empty($info['code_cis']) ? $info['code_cis'] : "-") . "</td></tr>";
        echo "<tr><th>Dénomination</th><td>" . (!empty($info['denomination']) ? $info['denomination'] : "-") . "</td></tr>";
        echo "<tr><th>Titulaire</th><td>" . (!empty($info['titulaires']) ? $info['titulaires'] : "-") . "</td></tr>";
        echo "<tr><th>Forme Pharmaceutique</th><td>" . (!empty($info['forme_phamaceutique']) ? $info['forme_phamaceutique'] : "-") . "</td></tr>";
        echo "<tr><th>Voie d'Administration</th><td>" . (!empty($info['voie_administration']) ? $info['voie_administration'] : "-") . "</td></tr>";
        echo "<tr><th>Statut Administratif</th><td>" . (!empty($info['statut_administratif']) ? $info['statut_administratif'] : "-") . "</td></tr>";
        echo "<tr><th>État Commercialisation</th><td>" . (!empty($info['etat_commercialisation']) ? $info['etat_commercialisation'] : "-") . "</td></tr>";
        echo "<tr><th>Taux de Remboursement</th><td>" . (!empty($info['taux_remboursement']) ? $info['taux_remboursement'] : "-") . "</td></tr>";
        echo "<tr><th>Valeur SMR</th><td>" . (!empty($info['valeurs_smr']) ? $info['valeurs_smr'] : "-") . "</td></tr>";
        echo "<tr><th>Substance Active</th><td>" . (!empty($info['substances']) ? $info['substances'] : "-") . "</td></tr>";
        echo "<tr><th>Prix</th><td>" . (!empty($info['prix']) ? $info['prix'] . "€" : "-") . "</td></tr>";
        echo "<tr><th>Référence Dosage</th><td>" . (!empty($info['reference_dosage']) ? $info['reference_dosage'] : "-") . "</td></tr>";
        echo "<tr><th>Type de Médicament</th><td>" . (!empty($info['type_medicament']) ? $info['type_medicament'] : "-") . "</td></tr>";
        echo "</table>";
    } else {
        echo "<p>Médicament non trouvé.</p>";
    }
} else {
    echo "<p>ID de médicament invalide.</p>";
}
?>
