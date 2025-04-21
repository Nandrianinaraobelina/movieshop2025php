<?php
// Home page content
?>

<div class="hero">
    <div class="container">
        <h2>Bienvenue sur MovieSHOP</h2>
        <p>Découvrez notre large sélection de films en DVD et Blu-ray. Des classiques aux nouveautés, trouvez le film parfait pour votre soirée cinéma.</p>
        <a href="index.php?page=catalog" class="btn">Découvrir notre catalogue</a>
    </div>
</div>

<div class="container">
    <!-- New Releases Section -->
    <section class="new-releases">
        <h2 class="section-title">Nouveautés</h2>
        <div class="products-grid">
            <?php
            // Get latest movies (limit to 4)
            $latestMovies = getMovies(4, 0, null, 'DateSortie DESC');
            
            foreach ($latestMovies as $movie) {
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="index.php?page=product&id=<?php echo $movie['ID_PROD']; ?>">
                            <img src="<?php echo htmlspecialchars($movie['PHOTO']); ?>" alt="<?php echo htmlspecialchars($movie['Titre']); ?>">
                        </a>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="index.php?page=product&id=<?php echo $movie['ID_PROD']; ?>">
                                <?php echo htmlspecialchars($movie['Titre']); ?>
                            </a>
                        </h3>
                        <p class="product-director">Réalisé par <?php echo htmlspecialchars($movie['Realisateur']); ?></p>
                        <p class="product-price"><?php echo number_format($movie['Prix_unitaire'], 2, ',', ' '); ?> €</p>
                        <div class="product-actions">
                            <button class="add-to-cart" 
                                    data-id="<?php echo $movie['ID_PROD']; ?>" 
                                    data-title="<?php echo htmlspecialchars($movie['Titre']); ?>" 
                                    data-price="<?php echo $movie['Prix_unitaire']; ?>"
                                    data-image="<?php echo htmlspecialchars($movie['PHOTO']); ?>">
                                <i class="fas fa-shopping-cart"></i> Ajouter
                            </button>
                            <button class="wishlist-btn" data-id="<?php echo $movie['ID_PROD']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="view-all">
            <a href="index.php?page=catalog&sort=DateSortie+DESC" class="btn btn-outline">Voir toutes les nouveautés</a>
        </div>
    </section>

    <!-- Popular Categories Section -->
    <section class="categories">
        <h2 class="section-title">Catégories populaires</h2>
        <div class="categories-grid">
            <?php
            // Predefined popular genres with images
            $popularGenres = [
                [
                    'name' => 'Action',
                    'image' => 'https://images.pexels.com/photos/2873486/pexels-photo-2873486.jpeg'
                ],
                [
                    'name' => 'Comédie',
                    'image' => 'https://images.pexels.com/photos/7991579/pexels-photo-7991579.jpeg'
                ],
                [
                    'name' => 'Drame',
                    'image' => 'https://images.pexels.com/photos/274937/pexels-photo-274937.jpeg'
                ],
                [
                    'name' => 'Science-Fiction',
                    'image' => 'https://images.pexels.com/photos/1117132/pexels-photo-1117132.jpeg'
                ]
            ];
            ?>
            <div class="categories-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
                <?php foreach ($popularGenres as $genre): ?>
                    <a href="index.php?page=catalog&genre=<?php echo urlencode($genre['name']); ?>" class="category-card" style="position: relative; height: 200px; border-radius: 8px; overflow: hidden; display: block;">
                        <img src="<?php echo $genre['image']; ?>" alt="<?php echo $genre['name']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="category-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.7)); display: flex; align-items: center; justify-content: center;">
                            <h3 style="color: #fff; font-size: 24px; text-align: center;"><?php echo $genre['name']; ?></h3>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products">
        <h2 class="section-title">Films populaires</h2>
        <div class="products-grid">
            <?php
            // In a real app, you'd have a way to determine popular products
            // For now, just get some random products
            $popularMovies = getMovies(4, 4);
            
            foreach ($popularMovies as $movie) {
                ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="index.php?page=product&id=<?php echo $movie['ID_PROD']; ?>">
                            <img src="<?php echo htmlspecialchars($movie['PHOTO']); ?>" alt="<?php echo htmlspecialchars($movie['Titre']); ?>">
                        </a>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="index.php?page=product&id=<?php echo $movie['ID_PROD']; ?>">
                                <?php echo htmlspecialchars($movie['Titre']); ?>
                            </a>
                        </h3>
                        <p class="product-director">Réalisé par <?php echo htmlspecialchars($movie['Realisateur']); ?></p>
                        <p class="product-price"><?php echo number_format($movie['Prix_unitaire'], 2, ',', ' '); ?> €</p>
                        <div class="product-actions">
                            <button class="add-to-cart" 
                                    data-id="<?php echo $movie['ID_PROD']; ?>" 
                                    data-title="<?php echo htmlspecialchars($movie['Titre']); ?>" 
                                    data-price="<?php echo $movie['Prix_unitaire']; ?>"
                                    data-image="<?php echo htmlspecialchars($movie['PHOTO']); ?>">
                                <i class="fas fa-shopping-cart"></i> Ajouter
                            </button>
                            <button class="wishlist-btn" data-id="<?php echo $movie['ID_PROD']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="view-all">
            <a href="index.php?page=catalog" class="btn btn-outline">Voir tous les films</a>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter" style="background-color: var(--primary-color); color: white; padding: 60px 0; text-align: center; margin-top: 40px; border-radius: 8px;">
        <div class="container">
            <h2 style="margin-bottom: 20px; font-size: 28px;">Restez informé des dernières sorties</h2>
            <p style="margin-bottom: 30px; max-width: 700px; margin-left: auto; margin-right: auto;">Inscrivez-vous à notre newsletter pour recevoir des informations sur les nouveaux films, les promotions et les événements exclusifs.</p>
            
            <form class="newsletter-form" style="display: flex; max-width: 500px; margin: 0 auto;">
                <input type="email" placeholder="Votre adresse email" required style="flex-grow: 1; padding: 12px 15px; border: none; border-radius: 4px 0 0 4px; font-size: 16px;">
                <button type="submit" class="btn" style="border-radius: 0 4px 4px 0; background-color: var(--secondary-color); padding: 12px 20px;">S'inscrire</button>
            </form>
        </div>
    </section>
</div>