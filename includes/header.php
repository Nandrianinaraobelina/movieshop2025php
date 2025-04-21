<?php
// Get current page for active menu highlighting
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'home';

// Get cart item count if cart exists
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cartCount += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieSHOP - Votre boutique de films en ligne</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="top-header">
            <div class="container">
                <div class="contact-info">
                    <span><i class="fas fa-phone"></i> +261.38.59.412.11</span>
                    <span><i class="fas fa-envelope"></i> raobelinaherynandrianina@movieshop.fr</span>
                </div>
                <div class="user-actions">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="index.php?page=account"><i class="fas fa-user"></i> Mon compte</a>
                        <a href="index.php?page=logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                    <?php else: ?>
                        <a href="index.php?page=login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
                        <a href="index.php?page=register"><i class="fas fa-user-plus"></i> Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="main-header">
            <div class="container">
                <div class="logo">
                    <a href="index.php">
                        <h1>Movie<span>SHOPLeka</span></h1>
                    </a>
                </div>
                <div class="search-bar">
                    <form action="index.php" method="GET">
                        <input type="hidden" name="page" value="catalog">
                        <input type="text" name="search" placeholder="Rechercher un film...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
                <div class="cart">
                    <a href="index.php?page=cart">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count"><?php echo $cartCount; ?></span>
                    </a>
                </div>
            </div>
        </div>
        <nav class="main-nav">
            <div class="container">
                <ul>
                    <li class="<?php echo $currentPage == 'home' ? 'active' : ''; ?>">
                        <a href="index.php">Accueil</a>
                    </li>
                    <li class="<?php echo $currentPage == 'catalog' ? 'active' : ''; ?>">
                        <a href="index.php?page=catalog">Catalogue</a>
                    </li>
                    <li class="dropdown">
                        <a href="#">Genres <i class="fas fa-chevron-down"></i></a>
                        <ul class="dropdown-content">
                            <?php
                            // Get genres from the database
                            $genres = getGenres();
                            foreach ($genres as $genre) {
                                echo '<li><a href="index.php?page=catalog&genre=' . urlencode($genre) . '">' . htmlspecialchars($genre) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="<?php echo $currentPage == 'nouveautes' ? 'active' : ''; ?>">
                        <a href="index.php?page=catalog&sort=DateSortie+DESC">Nouveautés</a>
                    </li>
                    <li class="<?php echo $currentPage == 'promotions' ? 'active' : ''; ?>">
                        <a href="index.php?page=catalog&promo=1">Promotions</a>
                    </li>
                    <li class="<?php echo $currentPage == 'contact' ? 'active' : ''; ?>">
                        <a href="index.php?page=contact">Contact</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <main>