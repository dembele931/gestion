<?php
session_start();
if(!isset($_SESSION['connecte'])) {
    header("Location: ../index.php");
    exit;
}
require_once "../config.php";

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("DELETE FROM dettes WHERE id=?");
    $stmt->execute([$id]);
}

header("Location: liste.php");
exit;
