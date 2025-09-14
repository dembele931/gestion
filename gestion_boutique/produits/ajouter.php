<?php
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base
include("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];

    // Préparer et exécuter l'insertion
    $stmt = $pdo->prepare("INSERT INTO produits (nom, prix_vente, quantite) VALUES (:nom, :prix, :quantite)");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prix', $prix);
    $stmt->bindParam(':quantite', $quantite);

    if ($stmt->execute()) {
        echo "<script>alert('Produit ajouté avec succès !'); window.location.href='liste.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de l\\'ajout du produit.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --success: #27ae60;
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
            max-width: 500px;
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
        
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            background: var(--success);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn:hover {
            background: #219653;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #2980b9;
            text-decoration: underline;
        }
        
        .cfa-note {
            font-size: 14px;
            color: var(--gray);
            margin-top: 5px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-plus-circle"></i> Ajouter un nouveau produit</h1>
        </div>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom du produit</label>
                    <input type="text" id="nom" name="nom" required placeholder="Ex: Ordinateur Portable HP">
                </div>
                
                <div class="form-group">
                    <label for="prix">Prix de vente</label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0" required placeholder="Ex: 25000">
                    <div class="cfa-note">Prix en Francs CFA (FCFA)</div>
                </div>
                
                <div class="form-group">
                    <label for="quantite">Quantité initiale en stock</label>
                    <input type="number" id="quantite" name="quantite" min="0" required placeholder="Ex: 10">
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-save"></i> Ajouter le produit
                </button>
            </form>
            
            <a href="liste.php" class="back-link">
                <i class="fas fa-arrow-left"></i> Retour à la liste des produits
            </a>
        </div>
        
        <div class="footer">
            <p>Gestion Boutique &copy; 2023</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validation simple du formulaire
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const prix = document.getElementById('prix').value;
                const quantite = document.getElementById('quantite').value;
                
                if (prix <= 0) {
                    e.preventDefault();
                    alert('Le prix doit être supérieur à 0.');
                    return false;
                }
                
                if (quantite < 0) {
                    e.preventDefault();
                    alert('La quantité ne peut pas être négative.');
                    return false;
                }
            });
            
            // Formatage automatique du prix
            const prixInput = document.getElementById('prix');
            prixInput.addEventListener('blur', function() {
                if (this.value) {
                    this.value = parseFloat(this.value).toFixed(2);
                }
            });
        });
    </script>
</body>
</html>