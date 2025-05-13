<?php
include '../../database/appelBase.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $info = getMedicamentById($id);
    $substances = getSubstancesByMedicament($id);

    if ($info) {
        echo "<p><strong>Nom :</strong> {$info['nom']}</p>";
        echo "<p><strong>Description :</strong> {$info['description']}</p>";
        echo "<p><strong>Substance(s) Active(s) :</strong> " . implode(", ", $substances) . "</p>";
        echo "<p><strong>Valeur SMR :</strong> {$info['smr']}</p>";
    } else {
        echo "<p>Médicament non trouvé.</p>";
    }
} else {
    echo "<p>ID de médicament invalide.</p>";
}
?>
