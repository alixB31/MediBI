<?php
global $pdo;
require_once '../database/db_connect.php';

$query = "SELECT * FROM medicaments WHERE 1=1";
$params = [];

if (!empty($_GET['denomination'])) {
    $query .= " AND denomination LIKE :denomination";
    $params['denomination'] = "%" . $_GET['denomination'] . "%";
}
if (!empty($_GET['forme_pharmaceutique'])) {
    $query .= " AND forme_pharmaceutique LIKE :forme_pharmaceutique";
    $params['forme_pharmaceutique'] = "%" . $_GET['forme_pharmaceutique'] . "%";
}
if (!empty($_GET['voie_administration'])) {
    $query .= " AND voie_administration LIKE :voie_administration";
    $params['voie_administration'] = "%" . $_GET['voie_administration'] . "%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$medicaments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Résultats :</h2>
<ul>
    <?php foreach ($medicaments as $medicament) : ?>
        <li>
            <a href="../public/medicament.php?id=<?= $medicament['id'] ?>">
                <?= htmlspecialchars($medicament['denomination']) ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
