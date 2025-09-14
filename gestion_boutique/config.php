<?php
$host = "localhost";
$db   = "gestion_boutique"; // le nom de ta base
$user = "root";             // utilisateur MySQL
$pass = "";                 // mot de passe MySQL (vide par défaut sous WAMP)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
