<?php
include 'db_connect.php';

global $pdo;

function getMedicaments()
{
    global $pdo;
    $query = $pdo->query("
        SELECT * FROM 'affichage_resultat_medicament'
    ");

    $medicaments = $query->fetchAll(PDO::FETCH_ASSOC);

    // foreach ($medicaments as &$medicament) {
    //     $medicament['substance'] = implode(", ", getSubstancesByMedicament($medicament['id']));
    // }

    return $medicaments;
}


// Récupère les valeurs distinctes pour les filtres
function getDistinctValues($column, $table)
{
    global $pdo;
    $query = $pdo->prepare("SELECT DISTINCT $column FROM $table WHERE $column IS NOT NULL AND $column != ''");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

function getFormes() {
    return getDistinctValues("forme_phamaceutique", "cis");
}

function getVoies() {
    return getDistinctValues("voie_administration", "cis");
}

function getStatuts() {
    return getDistinctValues("libelle_statut", "cisciodispo");
}

function getSubstances() {
    return getDistinctValues("denomination_substance", "ciscompo");
}

// Récupère un médicament par son code_cis
function getMedicamentById($id)
{
    global $pdo;
    $query = $pdo->prepare("
        SELECT 
            cis.code_cis,
            cis.denomination AS nom,
            cishassmr.valeur_smr AS smr
        FROM cis
        LEFT JOIN cishassmr ON cis.code_cis = cishassmr.code_cis
        WHERE cis.code_cis = :id
        LIMIT 1
    ");
    $query->execute(['id' => $id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}

// Récupère les substances d’un médicament
function getSubstancesByMedicament($id)
{
    global $pdo;
    $query = $pdo->prepare("
        SELECT denomination_substance 
        FROM ciscompo 
        WHERE code_cis = :id
    ");
    $query->execute(['id' => $id]);
    return $query->fetchAll(PDO::FETCH_COLUMN);
}
?>
