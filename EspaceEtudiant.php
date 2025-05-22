<?php
session_start();
require_once 'db_connect.php';

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['student_id'])) {
    header("Location: inscription.php");
    exit();
}

$student_id = $_SESSION['student_id'];
$student = null;
$announcements = [];
$action_message = '';

try {
    $stmt = $pdo->prepare("SELECT name, cin, room FROM students WHERE id = ?");
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();

    if (!$student) {
        session_unset();
        session_destroy();
        header("Location: inscription.php");
        exit();
    }

    $stmt = $pdo->prepare("SELECT title, content FROM announcements ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $announcements = $stmt->fetchAll();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_request'])) {
        $type = filter_var($_POST['type'], FILTER_SANITIZE_STRING);
        $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

        if (!empty($type) && !empty($message)) {
            $stmt = $pdo->prepare("INSERT INTO requests (student_id, type, message) VALUES (?, ?, ?)");
            $stmt->execute([$student_id, $type, $message]);
            $action_message = "Demande envoyée avec succès.";
        } else {
            $action_message = "Tous les champs sont requis.";
        }
    }

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: inscription.php");
        exit();
    }
} catch (PDOException $e) {
    error_log("Error in EspaceEtudiant: " . $e->getMessage());
    $action_message = "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Espace Étudiant</title>
    <link rel="stylesheet" href="EsapaceEtudaint.css">
</head>
<body>
<script>
    fetch('check_session.php')
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                window.location.href = 'inscription.php';
            }
        });
</script>

<header class="header">
    <h1>Bienvenue, <?php echo htmlspecialchars(explode(' ', $student['name'])[0]); ?></h1>
    <form method="POST">
        <input type="hidden" name="logout" value="1">
        <button type="submit" class="logout-btn">Déconnexion</button>
    </form>
</header>

<main class="main-container">
    <section class="profile-card">
        <h2>Bienvenue dans votre espace</h2>
        <h2><?php echo htmlspecialchars($student['name']); ?></h2>
        <p><strong>Numéro :</strong> <?php echo htmlspecialchars($student['cin']); ?></p>
        <p><strong>Chambre :</strong> <?php echo htmlspecialchars($student['room'] ?? 'Non assignée'); ?></p>
    </section>

    <section class="content-column">
        <div class="card">
            <h3>📢 Annonces</h3>
            <?php if (empty($announcements)): ?>
                <p>Aucune annonce pour le moment.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($announcements as $announcement): ?>
                        <li><?php echo htmlspecialchars($announcement['title'] . ': ' . $announcement['content']); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <div class="card">
            <h3>📬 Faire une demande</h3>
            <?php if ($action_message): ?>
                <p class="action-message"><?php echo htmlspecialchars($action_message); ?></p>
            <?php endif; ?>
            <form id="demandeForm" method="POST">
                <label for="type">Type de demande :</label>
                <select id="type" name="type" required>
                    <option value="reparation">Réparation</option>
                    <option value="changement">Changement de chambre</option>
                </select>
                <label for="message">Détail :</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                <input type="hidden" name="submit_request" value="1">
                <button type="submit">Envoyer</button>
            </form>
        </div>

        <div class="card">
            <h3>📄 Documents</h3>
            <ul>
                <li><a href="pdf/aa.pdf" download>📥 Attestation de résidence</a></li>
                <li><a href="pdf/Document sans titre (1).pdf" download>📥 Règlement intérieur</a></li>
            </ul>
        </div>
    </section>
</main>

<script src="espaceEtudiant.js"></script>
</body>
</html>