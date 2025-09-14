<?php
session_start();
require_once "config.php"; // ton fichier de connexion à la base

$message = "";
$message_type = ""; // success ou error

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // === INSCRIPTION ===
    if (isset($_POST["action"]) && $_POST["action"] === "register") {
        $nom = trim(htmlspecialchars($_POST["nom"]));
        $numero = trim(htmlspecialchars($_POST["numero"]));
        $motdepasse = $_POST["motdepasse"];
        $confirmation = $_POST["confirmation"];

        // Validation basique
        if (empty($nom) || empty($numero) || empty($motdepasse)) {
            $message = "Tous les champs sont obligatoires.";
            $message_type = "error";
        } elseif ($motdepasse !== $confirmation) {
            $message = "Les mots de passe ne correspondent pas.";
            $message_type = "error";
        } elseif (strlen($motdepasse) < 6) {
            $message = "Le mot de passe doit contenir au moins 6 caractères.";
            $message_type = "error";
        } else {
            $motdepasse_hash = password_hash($motdepasse, PASSWORD_BCRYPT);

            try {
                // Vérifier si le numéro existe déjà
                $check_stmt = $pdo->prepare("SELECT id FROM users WHERE numero = ?");
                $check_stmt->execute([$numero]);
                
                if ($check_stmt->fetch()) {
                    $message = "Ce numéro est déjà utilisé.";
                    $message_type = "error";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO users (nom, numero, motdepasse) VALUES (?, ?, ?)");
                    $stmt->execute([$nom, $numero, $motdepasse_hash]);
                    $message = "Inscription réussie ! Connectez-vous maintenant.";
                    $message_type = "success";
                }
            } catch (PDOException $e) {
                $message = "Erreur lors de l'inscription. Veuillez réessayer.";
                $message_type = "error";
                // En production, vous pourriez logger cette erreur: error_log($e->getMessage());
            }
        }
    }

    // === CONNEXION ===
    if (isset($_POST["action"]) && $_POST["action"] === "login") {
        $numero = trim(htmlspecialchars($_POST["numero"]));
        $motdepasse = $_POST["motdepasse"];

        if (empty($numero) || empty($motdepasse)) {
            $message = "Veuillez remplir tous les champs.";
            $message_type = "error";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE numero = ?");
            $stmt->execute([$numero]);
            $user = $stmt->fetch();

            if ($user && password_verify($motdepasse, $user["motdepasse"])) {
                $_SESSION["connecte"] = true;
                $_SESSION["nom"] = $user["nom"];
                $_SESSION["user_id"] = $user["id"];
                header("Location: accueil.php"); // page principale
                exit;
            } else {
                $message = "Numéro ou mot de passe incorrect.";
                $message_type = "error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Boutique - Connexion / Inscription</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --success: #2ecc71;
            --error: #e74c3c;
            --text: #2c3e50;
            --light-bg: #f5f6fa;
            --white: #ffffff;
            --gray: #ecf0f1;
            --border: #ddd;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background: var(--white);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }
        
        .header {
            display: flex;
            border-bottom: 1px solid var(--border);
        }
        
        .tab {
            flex: 1;
            padding: 16px;
            text-align: center;
            background: var(--gray);
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: var(--text);
        }
        
        .tab.active {
            background: var(--white);
            border-bottom: 3px solid var(--primary);
        }
        
        .form-container {
            padding: 25px;
        }
        
        .form {
            display: none;
        }
        
        .form.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: var(--text);
            font-size: 24px;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }
        
        input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 16px;
            transition: border 0.3s ease;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background 0.3s ease;
            margin-top: 10px;
        }
        
        button:hover {
            background: var(--primary-dark);
        }
        
        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        
        .message.success {
            background: rgba(46, 204, 113, 0.15);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .message.error {
            background: rgba(231, 76, 60, 0.15);
            color: var(--error);
            border: 1px solid var(--error);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #7f8c8d;
        }
        
        @media (max-width: 480px) {
            .container {
                max-width: 100%;
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
            <div class="tab active" data-tab="login">Connexion</div>
            <div class="tab" data-tab="register">Inscription</div>
        </div>
        
        <div class="form-container">
            <?php if (!empty($message)): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="form active" id="loginForm">
                <input type="hidden" name="action" value="login">
                
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="numero" placeholder="Numéro" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="motdepasse" id="loginPassword" placeholder="Mot de passe" required>
                    <span class="password-toggle" onclick="togglePassword('loginPassword', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                
                <button type="submit">Se connecter</button>
            </form>
            
            <form method="post" class="form" id="registerForm">
                <input type="hidden" name="action" value="register">
                
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" name="nom" placeholder="Nom complet" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="numero" placeholder="Numéro" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="motdepasse" id="regPassword" placeholder="Mot de passe" required>
                    <span class="password-toggle" onclick="togglePassword('regPassword', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirmation" id="regConfirmPassword" placeholder="Confirmer le mot de passe" required>
                    <span class="password-toggle" onclick="togglePassword('regConfirmPassword', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                
                <button type="submit">S'inscrire</button>
            </form>
        </div>
    </div>

    <script>
        // Basculer entre les onglets de connexion et d'inscription
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Activer l'onglet cliqué
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');
                
                // Afficher le formulaire correspondant
                const formId = tab.getAttribute('data-tab') + 'Form';
                document.querySelectorAll('.form').forEach(form => form.classList.remove('active'));
                document.getElementById(formId).classList.add('active');
                
                // Effacer les messages
                const message = document.querySelector('.message');
                if (message) message.remove();
            });
        });
        
        // Fonction pour afficher/masquer le mot de passe
        function togglePassword(inputId, toggleElement) {
            const passwordInput = document.getElementById(inputId);
            const icon = toggleElement.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
        
        // Validation basique côté client pour l'inscription
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('regPassword').value;
            const confirmPassword = document.getElementById('regConfirmPassword').value;
            
            if (password.length < 6) {
                e.preventDefault();
                showMessage('Le mot de passe doit contenir au moins 6 caractères.', 'error');
                return;
            }
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showMessage('Les mots de passe ne correspondent pas.', 'error');
            }
        });
        
        // Fonction pour afficher des messages
        function showMessage(text, type) {
            // Supprimer tout message existant
            const existingMessage = document.querySelector('.message');
            if (existingMessage) existingMessage.remove();
            
            // Créer le nouveau message
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${type}`;
            messageDiv.textContent = text;
            
            // Insérer le message au-dessus du formulaire
            const formContainer = document.querySelector('.form-container');
            formContainer.insertBefore(messageDiv, formContainer.firstChild);
        }
    </script>
</body>
</html>