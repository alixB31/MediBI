<link rel="stylesheet" href="./result.css">

<?php
include '../../database/appelBase.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $info = getMedicamentById($id);

    if ($info) {
        echo "<table class='details-table'>";
        echo "<tr><th>Code CIS</th><td>{$info['code_cis']}</td></tr>";
        echo "<tr><th>Dénomination</th><td>{$info['denomination']}</td></tr>";
        echo "<tr><th>Titulaire</th><td>{$info['titulaires']}</td></tr>";
        echo "<tr><th>Forme Pharmaceutique</th><td>{$info['forme_phamaceutique']}</td></tr>";
        echo "<tr><th>Voie d'Administration</th><td>{$info['voie_administration']}</td></tr>";
        echo "<tr><th>Statut Administratif</th><td>{$info['statut_administratif']}</td></tr>";
        echo "<tr><th>État Commercialisation</th><td>{$info['etat_commercialisation']}</td></tr>";
        echo "<tr><th>Taux de Remboursement</th><td>{$info['taux_remboursement']}</td></tr>";
        echo "<tr><th>Valeur SMR</th><td>{$info['valeur_smr']}</td></tr>";
        echo "<tr><th>Substance Active</th><td>{$info['substances']}</td></tr>";
        echo "<tr><th>Prix</th><td>{$info['prix']}</td></tr>";
        echo "<tr><th>Référence Dosage</th><td>{$info['reference_dosage']}</td></tr>";
        echo "<tr><th>Type de Médicament</th><td>{$info['type_medicament']}</td></tr>";
        echo "</table>";
    } else {
        echo "<p>Médicament non trouvé.</p>";
    }
} else {
    echo "<p>ID de médicament invalide.</p>";
}
?>
