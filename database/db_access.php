<?php
// $user = getenv("DB_USER");
// $pwd = getenv("DB_PWD");

$dns = "mysql:host=localhost;dbname=catblog";
// Credentials de la BDD test
$usr = "root";
$pwd = "Octopus!127";

try {
    $pdo = new PDO($dns, $usr, $pwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    throw new Exception($e->getMessage());
}

return $pdo;
