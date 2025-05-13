<?php
include 'db_connect.php';

global $pdo;

// Cache local
$medicamentCache = null;

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

function getMedicaments()
{
    global $pdo;
    // Sinon on récupère depuis la base de données
    $query = $pdo->query("SELECT * FROM affichage_resultat_medicament LIMIT 100");
    $medicamentCache = $query->fetchAll(PDO::FETCH_ASSOC);

    return $medicamentCache;
}

// Récupère un médicament par son code_cis
function getMedicamentById($id)
{
    global $pdo, $medicamentCache;

    // Vérifie dans le cache d'abord
    if ($medicamentCache !== null) {
        foreach ($medicamentCache as $medicament) {
            if ($medicament['code_cis'] == $id) {
                return $medicament;
            }
        }
    }

    // Sinon, récupère depuis la base de données
    $query = $pdo->prepare("SELECT * FROM affichage_resultat_medicament WHERE code_cis = :id LIMIT 1");
    $query->execute(['id' => $id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}
?>
