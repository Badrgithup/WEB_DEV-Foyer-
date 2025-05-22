<?php
session_start();
require_once 'db_connect.php';

$login_error = '';

if (isset($_SESSION['admin_id'])) {
    header("Location: admin_dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if ($admin && $password === $admin['password']) {
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $login_error = $admin ? "Mot de passe incorrect." : "Nom d'utilisateur non trouvé.";
        }
    } catch (PDOException $e) {
        error_log("Login error: " . $e->getMessage());
        $login_error = "Erreur lors de la connexion.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Foyer Ibn Zohr - Connexion Administrateur</title>
    <link rel="stylesheet" href="admin1.css" />
</head>
<body>
<div class="container">
    <div class="login-box">
        <h2>Connexion Administrateur</h2>
        <?php if ($login_error): ?>
            <p class="error-message"><?php echo htmlspecialchars($login_error); ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="input-group">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Se connecter</button>
        </form>
    </div>
</div>
</body>
</html>
