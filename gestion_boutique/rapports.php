<?php
// Inclure la configuration
require 'config.php';

// Récupérer toutes les ventes avec produits
$query = "
    SELECT v.id, p.nom AS produit_nom, v.quantite, v.montant, v.date_vente
    FROM ventes v
    JOIN produits p ON v.produit_id = p.id
    ORDER BY v.date_vente DESC
";
$stmt = $pdo->query($query);
$ventes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports des ventes</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f5f7fa; }
        h1 { color: #3498db; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Rapports des ventes</h1>

    <?php if ($ventes): ?>
    <table>
        <thead>
            <tr>
                <th>ID Vente</th>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Montant (FCFA)</th>
                <th>Date de vente</th>
            </tr>
        </thead>
        <tbody>
            <?php $total_jour = 0; ?>
            <?php foreach ($ventes as $vente): ?>
                <?php $total_jour += $vente['montant']; ?>
                <tr>
                    <td><?= htmlspecialchars($vente['id']) ?></td>
                    <td><?= htmlspecialchars($vente['produit_nom']) ?></td>
                    <td><?= htmlspecialchars($vente['quantite']) ?></td>
                    <td><?= number_format($vente['montant'], 0, ',', ' ') ?></td>
                    <td><?= htmlspecialchars($vente['date_vente']) ?></td>
                </tr>
            <?php endforeach; ?>
            <tr class="total">
                <td colspan="3">Total jour</td>
                <td colspan="2"><?= number_format($total_jour, 0, ',', ' ') ?> FCFA</td>
            </tr>
        </tbody>
    </table>
    <?php else: ?>
        <p>Aucune vente enregistrée pour le moment.</p>
    <?php endif; ?>
</body>
</html>
