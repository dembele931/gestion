<?php
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base
include("../config.php");

// Récupération des produits
$stmt = $pdo->query("SELECT * FROM produits ORDER BY id DESC");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistiques
$totalProduits = count($produits);
$valeurTotale = 0;
$articlesStock = 0;
$produitsAReappro = 0;

foreach ($produits as $produit) {
    $valeurTotale += $produit['prix_vente'] * $produit['quantite'];
    $articlesStock += $produit['quantite'];
    if ($produit['quantite'] < 5) {
        $produitsAReappro++;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .header {
            background: #3498db;
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .stat-card {
            background: white;
            border-radius: 6px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            font-size: 18px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-success {
            background-color: #2ecc71;
        }
        
        .btn-success:hover {
            background-color: #27ae60;
        }
        
        .search-box {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border-radius: 4px;
            padding: 8px 12px;
            border: 1px solid #ddd;
        }
        
        .search-box input {
            border: none;
            outline: none;
            padding: 8px;
            font-size: 14px;
            width: 200px;
            background: transparent;
        }
        
        .products-table {
            width: 100%;
            padding: 20px;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        tr:hover {
            background: #f9f9f9;
        }
        
        .quantity {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .quantity-high {
            background: #e8f5e9;
            color: #27ae60;
        }
        
        .quantity-medium {
            background: #fff3e0;
            color: #f39c12;
        }
        
        .quantity-low {
            background: #ffebee;
            color: #e74c3c;
        }
        
        .action-links {
            display: flex;
            gap: 10px;
        }
        
        .action-links a {
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
        }
        
        .edit-link {
            background: #3498db;
            color: white;
        }
        
        .edit-link:hover {
            background: #2980b9;
        }
        
        .delete-link {
            background: #e74c3c;
            color: white;
        }
        
        .delete-link:hover {
            background: #c0392b;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-size: 14px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        
        @media (max-width: 768px) {
            .actions {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-box {
                width: 100%;
            }
            
            .search-box input {
                width: 100%;
            }
            
            .products-table {
                padding: 10px;
            }
            
            th, td {
                padding: 8px 10px;
            }
            
            .action-links {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Gestion des Produits</h1>
            <p>Inventaire de votre boutique</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3><?= $totalProduits ?></h3>
                <p>Produits au total</p>
            </div>
            <div class="stat-card">
                <h3><?= number_format($valeurTotale, 0, ',', ' ') ?> FCFA</h3>
                <p>Valeur totale</p>
            </div>
            <div class="stat-card">
                <h3><?= $articlesStock ?></h3>
                <p>Articles en stock</p>
            </div>
            <div class="stat-card">
                <h3><?= $produitsAReappro ?></h3>
                <p>Produits à réapprovisionner</p>
            </div>
        </div>
        
        <div class="actions">
            <a href="ajouter.php" class="btn btn-success">➕ Nouveau produit</a>
            
            <div class="search-box">
                <input type="text" placeholder="Rechercher un produit...">
            </div>
        </div>
        
        <div class="products-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom du produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($produits) > 0): ?>
                        <?php foreach ($produits as $produit): ?>
                            <tr>
                                <td><?= $produit['id'] ?></td>
                                <td><?= htmlspecialchars($produit['nom']) ?></td>
                                <td><?= number_format($produit['prix_vente'], 0, ',', ' ') ?> FCFA</td>
                                <td>
                                    <?php 
                                        $qty = $produit['quantite'];
                                        $class = $qty >= 15 ? 'quantity-high' : ($qty >= 5 ? 'quantity-medium' : 'quantity-low');
                                    ?>
                                    <span class="quantity <?= $class ?>"><?= $qty ?></span>
                                </td>
                                <td class="action-links">
                                    <a href="modifier.php?id=<?= $produit['id'] ?>" class="edit-link">Modifier</a>
                                    <a href="supprimer.php?id=<?= $produit['id'] ?>" class="delete-link" onclick="return confirm('Supprimer ce produit ?')">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center; padding:20px;">Aucun produit disponible.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="footer">
            <p>Gestion Boutique &copy; <?= date('Y') ?></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonctionnalité de recherche
            const searchInput = document.querySelector('.search-box input');
            searchInput.addEventListener('keyup', function() {
                const searchText = this.value.toLowerCase();
                const rows = document.querySelectorAll('.products-table tbody tr');
                
                rows.forEach(row => {
                    const productName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    if (productName.includes(searchText)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>