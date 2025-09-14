<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $numero = preg_replace('/\s+/', '', $_POST['numero']); // normaliser
    $plain = $_POST['motdepasse'];

    if ($nom && $numero && $plain) {
        // Vérifier si le numéro existe déjà
        $chk = $pdo->prepare("SELECT id FROM users WHERE numero = ?");
        $chk->execute([$numero]);
        if ($chk->fetch()) {
            $error = "Ce numéro existe déjà.";
        } else {
            $hash = password_hash($plain, PASSWORD_DEFAULT); // hachage sécurisé
            $ins = $pdo->prepare("INSERT INTO users (nom, numero, motdepasse) VALUES (?, ?, ?)");
            $ins->execute([$nom, $numero, $hash]);

            header("Location: login.php?success=1");
            exit;
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>
    <?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" required><br>

        <label>Numéro :</label>
        <input type="text" name="numero" required><br>

        <label>Mot de passe :</label>
        <input type="password" name="motdepasse" required><br>

        <button type="submit">S'inscrire</button>
    </form>
    <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>
</body>
</html>
