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

function getDistinctValues($column, $table)
{
    global $pdoTable;

    $query = $pdoTable->prepare("SELECT DISTINCT `$column` FROM $table WHERE `$column` IS NOT NULL");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getDistinctValuesGeneric($column, $table)
{
    global $pdoTable;

    $query = $pdoTable->prepare("SELECT DISTINCT CASE 
        WHEN `$column` = 0 THEN 'Médicaments de marques'
        WHEN `$column` IN (1, 2, 4) THEN 'Médicaments génériques'
    END AS medicament_generique
    FROM $table
    ");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getDistinctValuesFromView($column, $table)
{
    global $pdoVue;

    $query = $pdoVue->prepare("SELECT DISTINCT `$column` FROM $table WHERE `$column` IS NOT NULL");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}
?>
