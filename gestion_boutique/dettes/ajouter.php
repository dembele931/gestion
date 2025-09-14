<?php
session_start();
if(!isset($_SESSION['connecte'])) {
    header("Location: ../index.php");
    exit;
}
require_once "../config.php";

$message = "";
$message_type = "";

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $montant = floatval($_POST['montant']);
    $date_dette = $_POST['date_dette'];
    $description = trim($_POST['description']);
    
    if($nom && $montant && $date_dette) {
        $stmt = $pdo->prepare("INSERT INTO dettes (nom,montant,date_dette,description) VALUES (?,?,?,?)");
        $stmt->execute([$nom,$montant,$date_dette,$description]);
        $message = "Dette ajoutée avec succès !";
        $message_type = "success";
    } else {
        $message = "Veuillez remplir tous les champs obligatoires.";
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une dette</title>
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
            max-width: 800px;
            margin: 0 auto;
        }
        
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #3498db;
        }
        
        h1 {
            color: #2c3e50;
        }
        
        .btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 10px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #2c3e50;
        }
        
        .btn-secondary:hover {
            background-color: #1a252f;
        }
        
        .btn-home {
            background-color: #2ecc71;
        }
        
        .btn-home:hover {
            background-color: #27ae60;
        }
        
        .form-container {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-title {
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
        }
        
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        input:focus,
        textarea:focus {
            outline: none;
            border-color: #3498db;
        }
        
        textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .message {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .required {
            color: #e74c3c;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Ajouter une dette</h1>
            <div>
                <a href="../accueil.php" class="btn btn-home">🏠 Accueil</a>
                <a href="liste.php" class="btn btn-secondary">📋 Liste des dettes</a>
                <a href="../logout.php" class="btn">🔒 Déconnexion</a>
            </div>
        </header>
        
        <div class="form-container">
            <h2 class="form-title">Nouvelle dette</h2>
            
            <?php if($message): ?>
            <div class="message <?= $message_type === 'success' ? 'success' : 'error' ?>">
                <?= $message ?>
            </div>
            <?php endif; ?>
            
            <form method="post" id="detteForm">
                <div class="form-group">
                    <label for="nom">Nom de la dette <span class="required">*</span></label>
                    <input type="text" id="nom" name="nom" required placeholder="Ex: Prêt voiture">
                </div>
                
                <div class="form-group">
                    <label for="montant">Montant (FCFA) <span class="required">*</span></label>
                    <input type="number" id="montant" name="montant" step="0.01" min="0" required placeholder="0.00">
                </div>
                
                <div class="form-group">
                    <label for="date_dette">Date <span class="required">*</span></label>
                    <input type="date" id="date_dette" name="date_dette" required value="<?= date('Y-m-d') ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Détails supplémentaires sur cette dette..."></textarea>
                </div>
                
                <div class="form-actions">
                    <a href="../accueil.php" class="btn btn-home">🏠 Accueil</a>
                    <a href="liste.php" class="btn btn-secondary">📋 Liste des dettes</a>
                    <button type="submit" class="btn">➕ Ajouter la dette</button>
                </div>
            </form>
        </div>
        
        <footer>
            <p>© <?= date('Y') ?> Gestion des Dettes</p>
        </footer>
    </div>

    <script>
        // Validation côté client
        document.getElementById('detteForm').addEventListener('submit', function(e) {
            let isValid = true;
            const nom = document.getElementById('nom');
            const montant = document.getElementById('montant');
            const date = document.getElementById('date_dette');
            
            // Validation du nom
            if (!nom.value.trim()) {
                alert('Veuillez saisir un nom pour la dette.');
                nom.focus();
                isValid = false;
            }
            
            // Validation du montant
            else if (!montant.value || parseFloat(montant.value) <= 0) {
                alert('Veuillez saisir un montant valide.');
                montant.focus();
                isValid = false;
            }
            
            // Validation de la date
            else if (!date.value) {
                alert('Veuillez sélectionner une date.');
                date.focus();
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>