<?php
include __DIR__ . '/db_connect.php';
include __DIR__ . '/../includes/cache_utils.php';

global $pdo;

function getMedicaments()
{
    global $pdo;
    $cacheFile = realpath(__DIR__ . '/../cache') . '/medicaments.json';
    $ttl = 3600; // 1h

    $cached = getCache($cacheFile, $ttl);
    if ($cached !== null) {
        return $cached;
    }

    $query = $pdo->query("SELECT * FROM affichage_resultat_medicament LIMIT 10000");
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    setCache($cacheFile, $results);

    return $results;
}

function getMedicamentById($id)
{
    $cacheFile = realpath(__DIR__ . '/../cache') . '/medicaments.json';
    $ttl = 3600;

    $cached = getCache($cacheFile, $ttl);
    if ($cached !== null) {
        foreach ($cached as $medicament) {
            if ($medicament['code_cis'] == $id) {
                return $medicament;
            }
        }
    }

    global $pdo;
    $query = $pdo->prepare("SELECT * FROM affichage_resultat_medicament WHERE code_cis = :id LIMIT 1");
    $query->execute(['id' => $id]);
    $medicament = $query->fetch(PDO::FETCH_ASSOC);

    if ($medicament && $cached !== null) {
        $cached[] = $medicament;
        setCache($cacheFile, $cached);
    }

    return $medicament;
}

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
?>
