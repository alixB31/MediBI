<?php
include __DIR__ . '/db_connect.php';


global $pdoTable;
global $pdoVue;

function getMedicaments()
{
    global $pdoVue;

    $query = $pdoVue->query("SELECT * FROM affichage_resultat_medicament LIMIT 100");
    $medicaments = $query->fetchAll(PDO::FETCH_ASSOC);

    return $medicaments;
}

function getMedicamentById($id)
{
    global $pdoVue;

    $query = $pdoVue->prepare("SELECT * FROM affichage_resultat_medicament WHERE code_cis = :id LIMIT 1");
    $query->execute(['id' => $id]);
    $medicament = $query->fetch(PDO::FETCH_ASSOC);

    return $medicament;
}

function getDistinctValues($column, $table, $pdo)
{
    $query = $pdo->prepare("SELECT DISTINCT `$column` FROM `$table` WHERE `$column` IS NOT NULL");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getMedicamentByDisponibilite($listValueInclude, $listValueExclude)
{
    global $pdoTable;
    $sql = "SELECT code_cis FROM cisciodispo WHERE 1"; // condition par défaut

    // Ajouter la condition IN pour les valeurs à inclure (libelle_statut)
    if (count($listValueInclude) > 0) {
        $placeholdersInclude = implode(',', array_fill(0, count($listValueInclude), '?'));
        $sql .= " AND libelle_statut IN ($placeholdersInclude)";
    }

    // Ajouter la condition NOT IN pour les valeurs à exclure (libelle_statut)
    if (count($listValueExclude) > 0) {
        $placeholdersExclude = implode(',', array_fill(0, count($listValueExclude), '?'));
        $sql .= " AND libelle_statut NOT IN ($placeholdersExclude)";
    }

    $query = $pdoTable->prepare($sql);

    $index = 1;
    foreach ($listValueInclude as $value) {
        $query->bindValue($index++, $value, PDO::PARAM_STR);
    }

    foreach ($listValueExclude as $value) {
        $query->bindValue($index++, $value, PDO::PARAM_STR);
    }

    $query->execute();

    return $query->fetchAll(PDO::FETCH_COLUMN);
}

?>
