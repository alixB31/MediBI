<?php
// database/db_connect.php

// require_once __DIR__ . '/../vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();

// $host = $_ENV['HOST_URL'];
// $dbname = $_ENV['DB_NAME'];
// $username = $_ENV['USERNAME'];
// $password = $_ENV['PASSWORD'];

$host = 'dolibarr.iut-rodez.fr';
$dbname = 'SAE_S6_2025_E';
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
