<?php
// On récupère les valeurs depuis les variables d'environnement du serveur
$db_user = getenv('DB_USER_AGENCE');
$db_pass = getenv('DB_PASS_AGENCE');

$dsn = 'mysql:host=localhost;dbname=agence;charset=utf8mb4';

try {
    $pdo = new PDO($dsn, $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion.");
}
?>