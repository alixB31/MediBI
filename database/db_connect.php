<?php
// database/db_connect.php
$host = 'dolibarr.iut-rodez.fr';
$dbname = 'SAE_S6_2025';
$username = 'SAE_S6_2025_E';
$password = '333ikRwV';

try {
    global $pdo;
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
