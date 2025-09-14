<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit_id = $_POST['produit_id'];
    $quantite = $_POST['quantite'];

    // Récupérer le prix du produit
    $stmt = $pdo->prepare("SELECT prix_vente, quantite AS stock FROM produits WHERE id = ?");
    $stmt->execute([$produit_id]);
    $produit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        $error = "Produit non trouvé !";
    } else if ($quantite > $produit['stock']) {
        $error = "Quantité demandée supérieure au stock disponible !";
    } else {
        $prix_unitaire = $produit['prix_vente'];
        $prix_total = $prix_unitaire * $quantite;

        // Insérer la vente
        $stmt = $pdo->prepare("INSERT INTO ventes (produit_id, quantite, montant, date_vente) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$produit_id, $quantite, $prix_total]);

        // Mettre à jour le stock
        $stmt = $pdo->prepare("UPDATE produits SET quantite = quantite - ? WHERE id = ?");
        $stmt->execute([$quantite, $produit_id]);

        header("Location: liste.php");
        exit;
    }
}

// Récupérer la liste des produits pour la liste déroulante
$stmt = $pdo->query("SELECT id, nom, prix_vente, quantite FROM produits WHERE quantite > 0 ORDER BY nom");
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Vente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #27ae60;
            --secondary: #2c3e50;
            --danger: #e74c3c;
            --light: #f8f9fa;
            --dark: #343a40;
            --gray: #6c757d;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecef 100%);
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
        }
        
        .header {
            padding: 25px 30px;
            text-align: center;
            border-bottom: 1px solid #eaeaea;
            background: var(--primary);
            color: white;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 600;
        }
        
        .form-container {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
        }
        
        select, input[type="number"] {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        select:focus, input[type="number"]:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(39, 174, 96, 0.2);
        }
        
        .product-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
            display: none;
        }
        
        .product-info.visible {
            display: block;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .total-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed #ddd;
        }
        
        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #219653;
        }
        
        .btn-secondary {
            background: var(--gray);
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .error-message {
            background: #ffecec;
            color: var(--danger);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: <?php echo isset($error) ? 'block' : 'none'; ?>;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: var(--gray);
            font-size: 14px;
            border-top: 1px solid #eaeaea;
        }
        
        @media (max-width: 500px) {
            .header {
                padding: 20px;
            }
            
            .form-container {
                padding: 20px;
            }
            
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-cash-register"></i> Nouvelle Vente</h1>
        </div>
        
        <div class="form-container">
            <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="venteForm">
                <div class="form-group">
                    <label for="produit_id">Choisir un produit :</label>
                    <select name="produit_id" id="produit_id" required>
                        <option value="">-- Sélectionner un produit --</option>
                        <?php foreach ($produits as $p): ?>
                            <option value="<?= $p['id'] ?>" 
                                    data-prix="<?= $p['prix_vente'] ?>" 
                                    data-stock="<?= $p['quantite'] ?>">
                                <?= htmlspecialchars($p['nom']) ?> (Stock: <?= $p['quantite'] ?>, <?= number_format($p['prix_vente'], 0, '', ' ') ?> FCFA)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="product-info" id="productInfo">
                    <div class="info-row">
                        <span>Prix unitaire:</span>
                        <span id="prixUnitaire">0 FCFA</span>
                    </div>
                    <div class="info-row">
                        <span>Stock disponible:</span>
                        <span id="stockDisponible">0</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="quantite">Quantité :</label>
                    <input type="number" id="quantite" name="quantite" value="1" min="1" required>
                </div>
                
                <div class="product-info visible" id="totalInfo">
                    <div class="info-row">
                        <span>Total:</span>
                        <span class="total-price" id="totalPrice">0 FCFA</span>
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-check"></i> Valider la vente
                    </button>
                    <a href="liste.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
        
        <div class="footer">
            <p>Gestion Boutique &copy; 2023</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const produitSelect = document.getElementById('produit_id');
            const quantiteInput = document.getElementById('quantite');
            const productInfo = document.getElementById('productInfo');
            const prixUnitaire = document.getElementById('prixUnitaire');
            const stockDisponible = document.getElementById('stockDisponible');
            const totalPrice = document.getElementById('totalPrice');
            
            // Afficher les informations du produit sélectionné
            produitSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const prix = selectedOption.getAttribute('data-prix');
                    const stock = selectedOption.getAttribute('data-stock');
                    
                    prixUnitaire.textContent = new Intl.NumberFormat('fr-FR').format(prix) + ' FCFA';
                    stockDisponible.textContent = stock;
                    productInfo.classList.add('visible');
                    
                    // Mettre à jour le total
                    updateTotal(prix, quantiteInput.value);
                    
                    // Définir la quantité max selon le stock
                    quantiteInput.max = stock;
                } else {
                    productInfo.classList.remove('visible');
                    totalPrice.textContent = '0 FCFA';
                }
            });
            
            // Mettre à jour le total quand la quantité change
            quantiteInput.addEventListener('input', function() {
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                if (selectedOption.value) {
                    const prix = selectedOption.getAttribute('data-prix');
                    updateTotal(prix, this.value);
                }
            });
            
            function updateTotal(prix, quantite) {
                const total = prix * quantite;
                totalPrice.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' FCFA';
            }
            
            // Validation du formulaire
            document.getElementById('venteForm').addEventListener('submit', function(e) {
                const selectedOption = produitSelect.options[produitSelect.selectedIndex];
                const quantite = parseInt(quantiteInput.value);
                const stock = parseInt(selectedOption.getAttribute('data-stock'));
                
                if (selectedOption.value && quantite > stock) {
                    e.preventDefault();
                    alert('Erreur: La quantité demandée dépasse le stock disponible!');
                }
            });
        });
    </script>
</body>
</html>