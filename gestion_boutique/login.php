<?php
session_start();
require_once 'config.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $numero = preg_replace('/\s+/', '', $_POST['numero']);
    $plain = $_POST['motdepasse'];

    $stmt = $pdo->prepare("SELECT id, nom, motdepasse FROM users WHERE numero = ?");
    $stmt->execute([$numero]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($plain, $user['motdepasse'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        header("Location: accueil.php");  // Redirection vers accueil.php
        exit;
    } else {
        $error = "Numéro ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Gestion des Dettes</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #3498db 0%, #2c3e50 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 30px;
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .login-header h2 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .login-header p {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .btn {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #2980b9;
        }
        
        .error-message {
            background-color: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: center;
            border-left: 4px solid #c62828;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .register-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 480px) {
            .login-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h2>Connexion</h2>
            <p>Accédez à votre espace de gestion</p>
        </div>
        
        <?php if (!empty($error)): ?>
        <div class="error-message">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="numero">Numéro de téléphone</label>
                <input type="text" id="numero" name="numero" required placeholder="Votre numéro">
            </div>
            
            <div class="form-group">
                <label for="motdepasse">Mot de passe</label>
                <input type="password" id="motdepasse" name="motdepasse" required placeholder="Votre mot de passe">
            </div>
            
            <button type="submit" class="btn">Se connecter</button>
        </form>
        
        <div class="register-link">
            <p>Pas encore inscrit ? <a href="register.php">Créer un compte</a></p>
        </div>
    </div>

    <script>
        // Ajouter une validation basique côté client
        document.querySelector('form').addEventListener('submit', function(e) {
            const numero = document.getElementById('numero');
            const password = document.getElementById('motdepasse');
            
            if (!numero.value.trim()) {
                alert('Veuillez saisir votre numéro de téléphone.');
                numero.focus();
                e.preventDefault();
                return false;
            }
            
            if (!password.value.trim()) {
                alert('Veuillez saisir votre mot de passe.');
                password.focus();
                e.preventDefault();
                return false;
            }
        });
    </script>
</body>
</html>