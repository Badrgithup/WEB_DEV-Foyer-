<?php
session_start();
require_once 'db_connect.php';

$login_error = '';
$register_error = '';
$register_success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $cin = filter_var($_POST['cin'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        try {
            $stmt = $pdo->prepare("SELECT id, password FROM students WHERE cin = ?");
            $stmt->execute([$cin]);
            $student = $stmt->fetch();

            if ($student) {
                if ($password === $student['password']) {
                    session_regenerate_id(true);
                    $_SESSION['student_id'] = $student['id'];
                    header("Location: EspaceEtudiant.php");
                    exit();
                } else {
                    $login_error = "Mot de passe incorrect.";
                }
            } else {
                $login_error = "Numéro de CIN non trouvé.";
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $login_error = "Erreur lors de la connexion.";
        }
    }

    if (isset($_POST['register'])) {
        $name = filter_var($_POST['nom'] . ' ' . $_POST['prenom'], FILTER_SANITIZE_STRING);
        $cin = filter_var($_POST['cin'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $contact = filter_var($_POST['contact'], FILTER_SANITIZE_STRING);
        $date_of_birth = filter_var($_POST['date_naissance'], FILTER_SANITIZE_STRING);
        $establishment = filter_var($_POST['etablissement'], FILTER_SANITIZE_STRING);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if (!preg_match('/^\d{8}$/', $cin)) {
            $register_error = "Le numéro de CIN doit comporter exactement 8 chiffres.";
        }
        // Contact validation: must be a valid phone number (e.g., 8 digits for Tunisian numbers)
        elseif (!preg_match('/^\d{8}$/', $contact)) {
            $register_error = "Le numéro de téléphone doit comporter exactement 8 chiffres.";
        }
        // Date of birth validation: must be at least 17 years old
        elseif (strtotime($date_of_birth)) {
            $today = new DateTime('2025-05-07');
            $birth_date = new DateTime($date_of_birth);
            $interval = $today->diff($birth_date);
            $age = $interval->y;
            if ($age < 17) {
                $register_error = "Vous devez avoir au moins 17 ans pour vous inscrire.";
            }
        } else {
            $register_error = "Date de naissance invalide.";
        }

        if (!$register_error && $password !== $confirm_password) {
            $register_error = "Les mots de passe ne correspondent pas.";
        } elseif (!$register_error && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $register_error = "Adresse e-mail invalide.";
        } elseif (!$register_error) {
            try {
                $stmt = $pdo->prepare("INSERT INTO students (name, cin, password, email, contact, date_of_birth, establishment) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $success = $stmt->execute([$name, $cin, $password, $email, $contact, $date_of_birth, $establishment]);
                if ($success) {
                    $register_success = "Inscription réussie. Veuillez vous connecter.";
                } else {
                    $register_error = "Erreur lors de l'inscription.";
                }
            } catch (PDOException $e) {
                error_log("Registration error: " . $e->getMessage());
                $register_error = "Erreur: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription/Connexion</title>
    <link rel="stylesheet" href="stylesInsc.css" />
</head>
<body>
<section class="section">
    <div class="title">
        <h2>Connexion / S'inscrire</h2>
    </div>

    <div class="about-center section-center">
        <article class="about">
            <div class="btn-container">
                <button class="tab-btn active" data-id="connexion">Connexion</button>
                <button class="tab-btn" data-id="inscription">S'inscrire</button>
            </div>
            <div class="about-content">
                <div class="content active" id="connexion">
                    <?php if ($login_error): ?>
                        <p class="error-message"><?php echo htmlspecialchars($login_error); ?></p>
                    <?php endif; ?>
                    <form method="POST">
                        <label for="cin">Numéro de CIN :</label>
                        <input type="text" id="cin" name="cin" pattern="\d{8}" title="Le CIN doit comporter 8 chiffres." required>
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" required>
                        <input type="hidden" name="login" value="1">
                        <input type="submit" value="Se connecter">
                    </form>
                </div>
                <div class="content" id="inscription">
                    <?php if ($register_error): ?>
                        <p class="error-message"><?php echo htmlspecialchars($register_error); ?></p>
                    <?php endif; ?>
                    <?php if ($register_success): ?>
                        <p class="success-message"><?php echo htmlspecialchars($register_success); ?></p>
                    <?php endif; ?>
                    <form method="POST">
                        <label for="nom">Nom :</label>
                        <input type="text" id="nom" name="nom" required>
                        <label for="prenom">Prénom :</label>
                        <input type="text" id="prenom" name="prenom" required>
                        <label for="cin">Numéro de CIN :</label>
                        <input type="text" id="cin" name="cin" pattern="\d{8}" title="Le CIN doit comporter 8 chiffres." required>
                        <label for="contact">Numéro de téléphone :</label>
                        <input type="text" id="contact" name="contact" pattern="\d{8}" title="Le numéro de téléphone doit comporter 8 chiffres." required>
                        <label for="date_naissance">Date de naissance :</label>
                        <input type="date" id="date_naissance" name="date_naissance" max="2008-05-07" title="Vous devez avoir au moins 17 ans." required>
                        <label for="email">Votre e-mail :</label>
                        <input type="email" id="email" name="email" required>
                        <label for="etablissement">Établissement universitaire :</label>
                        <select id="etablissement" name="etablissement" required>
                            <option value="">-- Sélectionnez --</option>
                            <option value="ENSI">École Nationale des Sciences de l'Informatique</option>
                            <option value="ISAMM">Institut Supérieur des Arts Multimédia de la Manouba</option>
                            <option value="ISCAE">Institut Supérieur de Comptabilité et d'Administration des Entreprises</option>
                            <option value="FLAHM">Faculté des Lettres, des Arts et des Humanités de la Manouba</option>
                            <option value="MSE">Manouba School of Engineering</option>
                        </select>
                        <label for="password">Mot de passe :</label>
                        <input type="password" id="password" name="password" required>
                        <label for="confirm_password">Confirmer le mot de passe :</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <input type="hidden" name="register" value="1">
                        <input type="submit" value="S'inscrire">
                    </form>
                </div>
            </div>
        </article>
    </div>
</section>
<script src="scriptInsc.js"></script>
</body>
</html>