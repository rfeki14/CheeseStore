<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<body class="hold-transition skin-blue layout-top-nav">
    <link rel="stylesheet" href="dist/css/contact.css">

    <div class="contact-section">
        <div class="contact-container">
            <!-- Left: Contact Form -->
            <div class="contact-form">
                <h1 class="contact-title">Contactez-nous</h1>
                <p class="contact-description">Nous serions ravis de vous entendre. Remplissez le formulaire ci-dessous.</p>

                <form action="contact_submit.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" placeholder="Votre Nom" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Votre Email" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" class="form-control" rows="5" placeholder="Votre Message" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                </form>
            </div>

            <!-- Right: Contact Details -->
            <div class="contact-details">
                <h2>Nos Coordonnées</h2>
                <p><i class="fas fa-map-marker-alt"></i> 123 Rue Principale, Tunis, Tunisie</p>
                <p><i class="fas fa-phone"></i> +216 123 456 789</p>
                <p><i class="fas fa-envelope"></i> contact@coeurblanc.com</p>

                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="#" class="social-btn btn-facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn btn-instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-btn btn-twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Map -->
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3318.5789869140363!2d10.181502615288342!3d36.80197457995357"
            width="100%" height="450" style="border:0;" allowfullscreen="">
        </iframe>
    </div>
</body>
</html>
