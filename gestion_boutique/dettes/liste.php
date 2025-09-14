<?php
session_start();
if(!isset($_SESSION['connecte'])) {
    header("Location: ../index.php");
    exit;
}

require_once "../config.php";

$stmt = $pdo->query("SELECT * FROM dettes ORDER BY created_at DESC");
$dettes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des dettes</title>
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
        }
        
        .container {
            max-width: 1000px;
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
        
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 5px;
            overflow: hidden;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #3498db;
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .payee {
            color: #2ecc71;
            font-weight: bold;
        }
        
        .non-payee {
            color: #e74c3c;
            font-weight: bold;
        }
        
        .actions a {
            margin-right: 10px;
            text-decoration: none;
            color: #3498db;
        }
        
        .actions a:hover {
            text-decoration: underline;
        }
        
        .empty-message {
            text-align: center;
            padding: 40px;
            color: #7f8c8d;
            font-style: italic;
        }
        
        @media (max-width: 768px) {
            header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Liste des dettes</h1>
            <div>
                <a href="ajouter.php" class="btn btn-success">➕ Ajouter une dette</a>
                <a href="../logout.php" class="btn">Déconnexion</a>
            </div>
        </header>
        
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Montant</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($dettes) > 0): ?>
                    <?php foreach($dettes as $dette): ?>
                    <tr>
                        <td><?= htmlspecialchars($dette['nom']) ?></td>
                        <td><?= number_format($dette['montant'], 0, ',', ' ') ?> FCFA</td>
                        <td><?= date('d/m/Y', strtotime($dette['date_dette'])) ?></td>
                        <td>
                            <?php if($dette['payee']): ?>
                                <span class="payee">Payée</span>
                            <?php else: ?>
                                <span class="non-payee">Impayée</span>
                            <?php endif; ?>
                        </td>
                        <td class="actions">
                            <a href="modifier.php?id=<?= $dette['id'] ?>">Modifier</a>
                            <a href="supprimer.php?id=<?= $dette['id'] ?>" onclick="return confirm('Supprimer cette dette ?');">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="empty-message">Aucune dette enregistrée. <a href="ajouter.php">Ajouter une dette</a></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>