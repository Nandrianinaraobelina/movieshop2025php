</main>
    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="footer-column">
                    <h3>À propos de MovieSHOP</h3>
                    <p>MovieSHOP est votre boutique en ligne spécialisée dans la vente de films en DVD et Blu-ray. Nous proposons un large catalogue pour tous les amateurs de cinéma.</p>
                </div>
                <div class="footer-column">
                    <h3>Informations</h3>
                    <ul>
                        <li><a href="index.php?page=about">Qui sommes-nous</a></li>
                        <li><a href="index.php?page=cgv">Conditions générales de vente</a></li>
                        <li><a href="index.php?page=legal">Mentions légales</a></li>
                        <li><a href="index.php?page=privacy">Politique de confidentialité</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Mon compte</h3>
                    <ul>
                        <li><a href="index.php?page=login">Connexion</a></li>
                        <li><a href="index.php?page=register">Inscription</a></li>
                        <li><a href="index.php?page=orders">Mes commandes</a></li>
                        <li><a href="index.php?page=wishlist">Ma liste de souhaits</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h3>Contact</h3>
                    <p><i class="fas fa-map-marker-alt"></i> 123 rue du Cinéma, 75000 Paris</p>
                    <p><i class="fas fa-phone"></i> +33 1 23 45 67 89</p>
                    <p><i class="fas fa-envelope"></i> contact@movieshop.fr</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; <?php echo date('Y'); ?> MovieSHOP - Tous droits réservés</p>
                <div class="payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-paypal"></i>
                    <i class="fab fa-cc-amex"></i>
                </div>
            </div>
        </div>
    </footer>

    <script src="assets/js/main.js"></script>
    <?php if (isset($pageScripts) && is_array($pageScripts)): ?>
        <?php foreach ($pageScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>