<?php
include("../config.php");

$id = $_GET['id'];
$sql = "DELETE FROM produits WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);

header("Location: liste.php");
exit();
?>