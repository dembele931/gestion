<?php
// liste.php - Historique des ventes
require_once '../config.php'; // connexion à la base $pdo

try {
    // Récupérer toutes les ventes avec le nom du produit et le total
    $ventes = $pdo->prepare("
        SELECT 
            v.id, 
            v.quantite, 
            v.date_vente, 
            p.nom, 
            p.prix_vente,
            (v.quantite * p.prix_vente) AS prix_total
        FROM ventes v
        JOIN produits p ON v.produit_id = p.id
        ORDER BY v.date_vente DESC
    ");
    $ventes->execute();
    $ventes = $ventes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des ventes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Ventes - Gestion Boutique</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background:#f5f7fa; padding:20px; color:#2c3e50; }
        .container { max-width:1000px; margin:auto; background:white; padding:20px; border-radius:12px; box-shadow:0 5px 15px rgba(0,0,0,0.1); }
        h1 { text-align:center; color:#3498db; margin-bottom:20px; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th, td { padding:12px; border-bottom:1px solid #ddd; text-align:left; }
        th { background:#f8f9fa; }
        tr:hover { background:#f1f1f1; }
        .total { font-weight:bold; }
        .btn { padding:8px 12px; border:none; border-radius:6px; text-decoration:none; color:white; background:#27ae60; display:inline-flex; align-items:center; gap:5px; }
        .btn:hover { background:#219653; }
    </style>
</head>
<body>
<div class="container">
    <h1><i class="fas fa-shopping-cart"></i> Historique des ventes</h1>

    <a href="ajouter.php" class="btn"><i class="fas fa-plus-circle"></i> Nouvelle vente</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire (FCFA)</th>
                <th>Total (FCFA)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalJour = 0;
            if ($ventes) {
                foreach ($ventes as $vente) {
                    echo "<tr>";
                    echo "<td>{$vente['id']}</td>";
                    echo "<td>".htmlspecialchars($vente['nom'])."</td>";
                    echo "<td>{$vente['quantite']}</td>";
                    echo "<td>".number_format($vente['prix_vente'],0,'',' ')."</td>";
                    echo "<td>".number_format($vente['prix_total'],0,'',' ')."</td>";
                    echo "<td>{$vente['date_vente']}</td>";
                    echo "</tr>";
                    $totalJour += $vente['prix_total'];
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Aucune vente enregistrée</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <p class="total" style="text-align:right; margin-top:15px;">Total jour : <?= number_format($totalJour,0,'',' ') ?> FCFA</p>
</div>
</body>
</html>
