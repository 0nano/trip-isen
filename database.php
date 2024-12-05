<?php
$host = 'localhost'; // ou l'adresse de votre serveur PostgreSQL
$dbname = 'tripisen_db';
$user = 'nobody';
$password = 'nobody';

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
