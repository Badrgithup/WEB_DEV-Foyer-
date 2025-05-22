<?php
date_default_timezone_set('Africa/Tunis');

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'] ?? '', FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $message = filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING);

    if ($name && $email && $message) {
        $to = 'badreddine.younsi@ensi-uma.tn';
        $subject = 'Nouveau message depuis le site Foyer Ibn Zohr';

        $email_content = "Nous avons bien reçu votre message : \n";
        $email_content .= "Nom : $name \n";
        $email_content .= "Email : $email \n";
        $email_content .= "Message : \n$message \n";
        $email_content .= "Cordialement";

        $headers = "From: $email\r\n";
        $headers .= "cc: badreddine.younsi@ensi-uma.tn\r\n";
        $headers .= "bcc: badreddine.younsi@ensi-uma.tn\r\n";
        $headers .= "Reply-To:рики $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "Date: " . date('d/m/Y H:i:s O') . "\r\n";

        // Send the email
        if (mail($to, $subject, $email_content, $headers)) {
            $success_message = 'Vous allez recevoir un mail de confirmation';
        } else {
            $error_message = "Le mail n'a pas été envoyé: recommencez!";
        }
    } else {
        $error_message = 'Veuillez remplir tous les champs correctement.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Foyer Ibn Zohr Manouba - Un cadre de vie équilibré pour étudiants combinant logement confortable et vie communautaire dynamique">
    <title>Foyer Ibn Zohr Manouba</title>
    <link rel="stylesheet" href="./fontawesome-free-5.12.0-web/css/all.min.css" />
    <link rel="stylesheet" href="styles.css" />
</head>
<body>
<header id="home">
    <nav id="nav">
        <div class="date-time"><?php echo date('d/m/Y H:i'); ?></div>
        <div class="nav-center">
            <div class="nav-header">
                <img src="image/logo.png" class="logo" alt="Logo Foyer Ibn Zohr" />
                <button class="nav-toggle" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="links-container">
                <ol class="links">
                    <li>
                        <a href="#home" class="scroll-link">Accueil</a>
                    </li>
                    <li>
                        <a href="#about" class="scroll-link">À propos</a>
                    </li>
                    <li>
                        <a href="#services" class="scroll-link">Services</a>
                    </li>
                    <li>
                        <a href="#tours" class="scroll-link">Explorer</a>
                    </li>
                    <li>
                        <a href="#contact" class="scroll-link">Contact</a>
                    </li>
                    <li>
                        <a href="inscription.php" target="_blank">Votre espace</a>
                    </li>
                </ol>
            </div>
        </div>
    </nav>
    <div class="banner">
        <div class="container">
            <h1>FOYER IBN ZOHR MANOUBA</h1>
            <article>
                <p class="intro-text"><span class="first-title">Bienvenue au Foyer Ibn Zohr Manouba</span>
                    Nous offrons aux étudiants un cadre de vie équilibré, combinant logement confortable, espaces d'étude et vie communautaire dynamique, pour une expérience universitaire enrichissante.</p>
            </article>
            <a href="#tours" class="scroll-link btn btn-white">Explorer notre foyer</a>
        </div>
    </div>
</header>
<section id="about" class="section">
    <div class="title">
        <h2>À propos</h2>
        <p>
            Découvrez l'histoire, la mission et la vie quotidienne de notre foyer .
        </p>
    </div>

    <div class="about-center section-center">
        <article class="about-img">
            <img src="image/about-bg.png" alt="Photo du foyer Ibn Zohr" />
        </article>
        <article class="about-info">
            <div class="btn-container">
                <button class="tab-btn active" data-id="history">Histoire</button>
                <button class="tab-btn" data-id="vision">Objectifs</button>
                <button class="tab-btn" data-id="goals">Vie au foyer</button>
            </div>
            <div class="about-content">
                <div class="content active" id="history">
                    <h4>Histoire & Présentation</h4>
                    <p>
                        Fondé en 1985, le Foyer Ibn Zohr est un lieu chargé d'histoire,
                        né de la volonté d'offrir aux étudiants un cadre de vie propice à leur épanouissement.
                        Situé à Manouba, à deux pas des principaux campus universitaires, il allie commodité et tranquillité. Au fil des années,
                        le foyer s'est imposé comme un espace incontournable, porté par des valeurs fortes : solidarité, respect et ouverture d'esprit.
                        Son architecture moderne et ses espaces verts en font bien plus qu'un simple logement : un véritable chez-soi pour des générations d'étudiants.
                    </p>
                </div>
                <div class="content" id="vision">
                    <h4>Mission & Objectifs</h4>
                    <p>
                        Le Foyer Ibn Zohr a pour mission de simplifier la vie étudiante en proposant des solutions adaptées. Avec des chambres spacieuses, des salles d'étude équipées et une cafétéria conviviale, il répond aux besoins quotidiens des résidents. Des activités culturelles (conférences, expositions) et sportives (tournois, séances de fitness) y sont régulièrement organisées pour favoriser les échanges.
                    </p>
                    <ul>
                        <li>Offrir un logement confortable et sécurisé</li>
                        <li>Créer un environnement propice aux études</li>
                        <li>Favoriser les échanges culturels et sociaux</li>
                        <li>Promouvoir le bien-être des résidents</li>
                    </ul>
                </div>
                <div class="content" id="goals">
                    <h4>Vie au Foyer</h4>
                    <p>
                        Au Foyer Ibn Zohr, chaque journée est rythmée par une ambiance chaleureuse et des rencontres inoubliables.
                        Les résidents le décrivent comme un "chez-soi loin de chez soi", où l'entraide et la bonne humeur sont de mise.
                        "Ici, j'ai trouvé une deuxième famille", témoigne Inès, étudiante en médecine.
                    </p>
                    <p>
                        Prochainement, un espace coworking et un jardin partagé viendront renforcer cette dynamique.
                        Plus qu'un logement, le foyer est une expérience humaine qui marque durablement ses habitants.
                    </p>
                </div>
            </div>
        </article>
    </div>
    <div class="video-section section-center">
        <div class="title">
            <h2>Découvrez <span>notre foyer</span> en vidéo</h2>
            <p>Une visite immersive de notre établissement et de ses services</p>
        </div>
        <div class="video-container">
            <video controls poster>
                <source src="image/foyer-video.mp4" type="video/mp4">
                Votre navigateur ne supporte pas les vidéos HTML5.
            </video>
        </div>
    </div>
</section>
<section id="services" class="section">
    <div class="title">
        <h2>Nos <span>services</span></h2>
        <p>Découvrez les services que nous offrons à nos résidents</p>
    </div>
    <div class="services-center section-center">
        <article class="service">
            <span class="service-icon"><i class="fas fa-home"></i></span>
            <h4>Hébergement</h4>
            <p>Chambres individuelles et collectives confortables et bien équipées</p>
        </article>
        <article class="service">
            <span class="service-icon"><i class="fas fa-utensils"></i></span>
            <h4>Restauration</h4>
            <p>Cafétéria proposant des repas équilibrés à des prix étudiants</p>
        </article>
        <article class="service">
            <span class="service-icon"><i class="fas fa-book"></i></span>
            <h4>Étude</h4>
            <p>Salles de travail silencieuses et espaces de coworking</p>
        </article>
        <article class="service">
            <span class="service-icon"><i class="fas fa-wifi"></i></span>
            <h4>Connectivité</h4>
            <p>Accès Internet haut débit dans tout le foyer</p>
        </article>
    </div>
</section>
<section id="tours" class="section">
    <div class="title">
        <h2>Visite <span>virtuelle</span></h2>
        <p>Explorez les espaces de notre foyer</p>
    </div>
    <div class="gallery-center section-center">
        <article class="gallery-img">
            <img src="image/bedroom.png" alt="Chambre étudiante">
            <div class="img-info">
                <h4>Chambres</h4>
                <p>Espace personnel confortable</p>
            </div>
        </article>
        <article class="gallery-img">
            <img src="image/study-room.png" alt="Salle d'étude">
            <div class="img-info">
                <h4>Salle d'étude</h4>
                <p>Espace calme pour travailler</p>
            </div>
        </article>
        <article class="gallery-img">
            <img src="image/cafeteria.png" alt="Cafétéria">
            <div class="img-info">
                <h4>Cafétéria</h4>
                <p>Espace de détente et restauration</p>
            </div>
        </article>
    </div>
    <br><br>
    <div class="title">
        <p>Explorez les activitées de notre foyer</p>
    </div>
    <div class="gallery-center section-center">
        <article class="gallery-img">
            <img src="image/pingpong.png" alt="pingpong">
            <div class="img-info">
                <h4>Ping-Pong</h4>
                <p>Rapidité - Précision - Réflexes</p>
            </div>
        </article>
        <article class="gallery-img">
            <img src="image/volle.jpg" alt="Salle d'étude">
            <div class="img-info">
                <h4>Volleyball</h4>
                <p>Équipe - Saut - Smash</p>
            </div>
        </article>
        <article class="gallery-img">
            <img src="image/babyfoot.png" alt="Cafétéria">
            <div class="img-info">
                <h4>Babyfoot</h4>
                <p>Stratégie - Adresse - Rire</p>
            </div>
        </article>
    </div>
    <br><br>
    <br><br>
    <div class="title">
        <h2>Notre <span>Impact</span></h2>
        <p>Des années de service dédié aux étudiants</p>
    </div>
    <div class="stats-center section-center">
        <article class="stat-item">
            <span class="number" data-value="50">0</span>
            <p>Evenements</p>
        </article>
        <article class="stat-item">
            <span class="number" data-value="1500">0</span>
            <p>Etudiants Satisfaits</p>
        </article>
        <article class="stat-item">
            <span class="number" data-value="300">0</span>
            <p>Chambres</p>
        </article>
    </div>
</section>
<section id="localisation" class="section">
    <div class="title">
        <h2>Nous <span>trouver</span></h2>
        <p>Localisation du Foyer Ibn Zohr à Manouba</p>
    </div>

    <div class="map-container section-center">
        <div class="map-wrapper">
            <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3194.066040910022!2d10.0766232!3d36.8126182!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x12fd2d884ac81e51%3A0x1e45869040908d71!2sFoyer%20Universitaire%20Ibn%20Zohr%20Manouba!5e0!3m2!1sfr!2stn!4v1712345678901!5m2!1sfr!2stn"
                    width="100%"
                    height="450"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
<section id="contact" class="section">
    <div class="title">
        <h2>Nous <span>contacter</span></h2>
    </div>
    < FIND US
    <div class="contact-center section-center">
        <article class="contact-info">
            <h4>Coordonnées</h4>
            <p><i class="fas fa-map-marker-alt"></i> Rue de l'Université, Manouba, Tunisie</p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:ibnzoher@ouun.tn">ibnzoher@ouun.tn</a></p>
            <p><i class="fas fa-phone"></i> 75 757 575</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
        </article>
        <article class="contact-form">
            <h4>Formulaire de contact</h4>
            <form method="POST" action="">
                <?php if ($success_message): ?>
                    <h1 class="success-message"><?php echo htmlspecialchars($success_message); ?></h1>
                <?php endif; ?>
                <?php if ($error_message): ?>
                    <h1 class="error-message"><?php echo htmlspecialchars($error_message); ?></h1>
                <?php endif; ?>
                <div class="form-group">
                    <input type="text" name="name" placeholder="Nom complet" class="form-control" required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email" class="form-control" required>
                </div>
                <div class="form-group">
                    <textarea name="message" placeholder="Votre message" class="form-control" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn">Envoyer</button>
            </form>
        </article>
    </div>
</section>
<footer class="section">
    <p>
        Copyright © <span id="date"></span>. Tous droits réservés - Foyer Ibn Zohr Manouba
    </p>
    <p>Mail: <a href="mailto:ibnzoher@ouun.tn">ibnzoher@ouun.tn</a></p>
    <p>Tel: 75 757 575</p>
    <a href="#home" class="scroll-link top-link">
        <i class="fas fa-arrow-up"></i>
    </a>
</footer>
<script src="app.js"></script>
</body>
</html>