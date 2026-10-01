<?php
// Si vous utilisez composer, décommentez la ligne suivante :
// require 'vendor/autoload.php';

// Si vous n'avez pas de bibliothèque, voici une méthode manuelle simple pour lire le .env :
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(sprintf('%s=%s', trim($name), trim($value)));
    }
}

$host = getenv('DB_HOST');
$db   = getenv('DB_NAME');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}
?>