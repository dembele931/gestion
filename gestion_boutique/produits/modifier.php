<?php
// Affichage des erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connexion à la base
include("../config.php"); // Ajuste le chemin selon ton projet

// Vérifie que l'ID est présent
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: liste.php");
    exit;
}

$id = intval($_GET['id']);

// Récupération du produit
$stmt = $pdo->prepare("SELECT * FROM produits WHERE id = ?");
$stmt->execute([$id]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    echo "Produit introuvable.";
    exit;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);

    $update = $pdo->prepare("UPDATE produits SET nom = ?, prix_vente = ?, quantite = ? WHERE id = ?");
    $update->execute([$nom, $prix, $quantite, $id]);

    header("Location: liste.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Produit</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Ton CSS existant complet ici */
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); color:#2c3e50; min-height:100vh; display:flex; justify-content:center; align-items:center; padding:20px; }
        .container { background:white; border-radius:12px; box-shadow:0 5px 20px rgba(0,0,0,0.08); width:100%; max-width:500px; overflow:hidden; }
        .header { padding:25px 30px; text-align:center; border-bottom:1px solid #eaeaea; background:#3498db; color:white; }
        .header h1 { font-size:24px; font-weight:600; }
        .form-container { padding:30px; }
        .form-group { margin-bottom:20px; }
        label { display:block; margin-bottom:8px; font-weight:500; color:#2c3e50; }
        input[type="text"], input[type="number"] { width:100%; padding:14px; border:1px solid #ddd; border-radius:8px; font-size:16px; transition:border 0.3s; }
        input[type="text"]:focus, input[type="number"]:focus { border-color:#3498db; outline:none; box-shadow:0 0 0 2px rgba(52,152,219,0.2); }
        .btn-group { display:flex; gap:15px; margin-top:10px; }
        .btn { padding:14px 20px; border-radius:8px; font-size:16px; font-weight:600; cursor:pointer; transition:all 0.3s; border:none; flex:1; display:flex; align-items:center; justify-content:center; gap:8px; }
        .btn-primary { background:#3498db; color:white; }
        .btn-primary:hover { background:#2980b9; }
        .btn-secondary { background:#95a5a6; color:white; }
        .btn-secondary:hover { background:#7f8c8d; }
        .footer { text-align:center; padding:20px; color:#7f8c8d; font-size:14px; border-top:1px solid #eaeaea; }
        @media (max-width:500px) { .header { padding:20px; } .form-container { padding:20px; } .btn-group { flex-direction:column; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-edit"></i> Modifier le produit</h1>
        </div>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-group">
                    <label for="nom">Nom du produit</label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($produit['nom']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="prix">Prix de vente (FCFA)</label>
                    <input type="number" id="prix" name="prix" step="0.01" min="0" value="<?= htmlspecialchars($produit['prix_vente']) ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="quantite">Quantité en stock</label>
                    <input type="number" id="quantite" name="quantite" min="0" value="<?= htmlspecialchars($produit['quantite']) ?>" required>
                </div>
                
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
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
</body>
</html>
