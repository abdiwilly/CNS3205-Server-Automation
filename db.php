<?php
// IS Project 1 - Database Connection Configuration (CAT2 / CAT1)
$host = 'localhost';
$dbname = 'CAT2'; // Connected to CAT2 database created in Q3
$user = 'example_user';
$pass = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
