<link rel="stylesheet" href="./result.css">

<?php
include '../../database/appelBase.php';
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
$code_cis = isset($_GET['id']) ? intval($_GET['id']) : 0;


$detail = getDetail($code_cis)[0];
if ($detail) {
    $detail['type_generique'] = genericFormat($detail['type_generique']);
    echo "<table class='details-table'>";
    echo "<tr><th>Code CIS</th><td>" . (!empty($detail['code_cis']) ? $detail['code_cis'] : "-") . "</td></tr>";
    echo "<tr><th>Dénomination</th><td>" . (!empty($detail['denomination']) ? $detail['denomination'] : "-") . "</td></tr>";
    echo "<tr><th>Titulaire</th><td>" . (!empty($detail['titulaires']) ? $detail['titulaires'] : "-") . "</td></tr>";
    echo "<tr><th>Forme Pharmaceutique</th><td>" . (!empty($detail['forme_phamaceutique']) ? $detail['forme_phamaceutique'] : "-") . "</td></tr>";
    echo "<tr><th>Voie d'Administration</th><td>" . (!empty($detail['voie_administration']) ? $detail['voie_administration'] : "-") . "</td></tr>";
    echo "<tr><th>Statut Administratif</th><td>" . (!empty($detail['statut_administratif']) ? $detail['statut_administratif'] : "-") . "</td></tr>";
    echo "<tr><th>Date_amm </th><td>" . (!empty($detail['date_amm']) ? $detail['date_amm'] : "-") . "</td></tr>";
    echo "<tr><th>Nature du composant </th><td>" . (!empty($detail['nature_composant']) ? $detail['nature_composant'] : "-") . "</td></tr>";
    echo "<tr><th>Valeur SMR</th><td>" . (!empty($detail['valeur_smr']) ? $detail['valeur_smr'] : "-") . "</td></tr>";
    echo "<tr><th>État Commercialisation</th><td>" . (!empty($detail['etat_commercialisation']) ? $detail['etat_commercialisation'] : "-") . "</td></tr>";
    echo "<tr><th>Prix</th><td>" . (!empty($detail['prix_medicament_b']) ? $detail['prix_medicament_b'] . "€" : "-") . "</td></tr>";
    echo "<tr><th>Taux de Remboursement</th><td>" . (!empty($detail['taux_remboursement']) ? $detail['taux_remboursement'] : "-") . "</td></tr>";
    echo "<tr><th>Référence Dosage</th><td>" . (!empty($detail['reference_dosage']) ? $detail['reference_dosage'] : "-") . "</td></tr>";
    echo "<tr><th>Lien BPDM</th><td>" . (!empty($detail['lien_bpdm']) ? '<a href="' . htmlspecialchars($detail['lien_bpdm']) . '" target="_blank">Voir la fiche BPDM</a>' : "-") . "</td></tr>";
    echo "<tr><th>Disponibilité </th><td>" . (!empty($detail['libelle_statut']) ? $detail['libelle_statut'] : "-") . "</td></tr>";
    echo "<tr><th>Condition de prescription</th><td>" . (!empty($detail['condition']) ? $detail['condition'] : "-") . "</td></tr>";
    echo "<tr><th>Type de Médicament</th><td>" . (!empty($detail['type_generique']) ? $detail['type_generique'] : "-") . "</td></tr>";
    echo "<tr><th>Libelle ASMR</th><td>" . (!empty($detail['libelle_asmr']) ? $detail['libelle_asmr'] : "-") . "</td></tr>";
    echo "<tr><th>Texte </th><td>" . (!empty($detail['texte']) ? $detail['texte'] : "-") . "</td></tr>";
    echo "<tr><th>Lien page avis CT</th><td>" . (!empty($detail['lien_page_avis_ct']) 
    ? '<a href="' . htmlspecialchars($detail['lien_page_avis_ct']) . '" target="_blank">Voir l’avis CT</a>' : "-") . "</td></tr>";
    echo "<tr><th>Substance Active</th><td>" . (!empty($detail['substances']) ? $detail['substances'] : "-") . "</td></tr>";
    echo "</table>";
}

?>
