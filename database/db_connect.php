<?php
// database/db_connect.php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$host = $_ENV['HOST_URL'];
$dbnameTable = $_ENV['DB_NAME'];
$dbnameVue = $_ENV['DB_NAME_VUE'];
$username = $_ENV['USERNAME'];
$password = $_ENV['PASSWORD'];

try {
    // Connexion à la base "vue"
    $pdoVue = new PDO("mysql:host=$host;dbname=$dbnameVue;charset=utf8", $username, $password);
    $pdoVue->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Connexion à la base "table"
    $pdoTable = new PDO("mysql:host=$host;dbname=$dbnameTable;charset=utf8", $username, $password);
    $pdoTable->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>