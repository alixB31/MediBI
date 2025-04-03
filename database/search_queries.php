<?php
// Inclure la connexion à la base de données
include 'db_connect.php';

global $pdo;
global $formes;
global $voies;
global $statuts;
global $substances;

// Récupérer les données pour chaque champ
function getDistinctValues($column, $table) {
    global $pdo;
    $query = $pdo->prepare("SELECT DISTINCT $column FROM $table");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_COLUMN);
}

$formes = getDistinctValues("forme_phamaceutique", "cis");
$voies = getDistinctValues("voie_administration", "cis");
$statuts = getDistinctValues("libelle_statut", "cisciodispo");
$substances = getDistinctValues("denomination_substance", "ciscompo");
?>
