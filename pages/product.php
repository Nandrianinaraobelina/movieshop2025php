<?php
// Product detail page

// Get product ID from URL parameter
$productId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$productId) {
    // Redirect to catalog if no ID provided
    header('Location: index.php?page=catalog');
    exit;
}

// Get product details from database
$movie = getMovie($productId);

if (!$movie) {
    // Product not found
    echo '<div class="container"><div class="error-message">Film non trouvé.</div></div>';
    exit;
}

// Get similar movies (same genre)
$similarMoviesQuery = "SELECT * FROM PRODUIT WHERE GENRE = ? AND ID_PROD != ? LIMIT 4";
$stmt = $conn->prepare($similarMoviesQuery);
$stmt->execute([$movie['GENRE'], $productId]);
$similarMovies = $stmt->fetchAll();

// Arrays of actors and additional images (for demonstration)
$actors = [
    [
        'name' => 'Actor 1',
        'image' => 'https://images.pexels.com/photos/220453/pexels-photo-220453.jpeg'
    ],
    [
        'name' => 'Actor 2',
        'image' => 'https://images.pexels.com/photos/774909/pexels-photo-774909.jpeg'
    ],
    [
        'name' => 'Actor 3',
        'image' => 'https://images.pexels.com/photos/1222271/pexels-photo-1222271.jpeg'
    ],
    [
        'name' => 'Actor 4',
        'image' => 'https://images.pexels.com/photos/1239291/pexels-photo-1239291.jpeg'
    ]
];

$additionalImages = [
    $movie['PHOTO'],
    'https://images.pexels.com/photos/7991579/pexels-photo-7991579.jpeg',
    'https://images.pexels.com/photos/7991432/pexels-photo-7991432.jpeg'
];
?>

<div class="container">
    <div class="product-detail">
        <div class="product-gallery">
            <img src="<?php echo htmlspecialchars($movie['PHOTO']); ?>" alt="<?php echo htmlspecialchars($movie['Titre']); ?>" class="main-image">
            <div class="thumbnail-gallery">
                <?php foreach ($additionalImages as $index => $image): ?>
                    <img src="<?php echo $image; ?>" alt="Image <?php echo $index + 1; ?>" class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="product-details">
            <h1><?php echo htmlspecialchars($movie['Titre']); ?></h1>
            
            <div class="product-meta">
                <p class="director">Réalisateur: <span><?php echo htmlspecialchars($movie['Realisateur']); ?></span></p>
                <p class="release-date">Date de sortie: <span><?php echo date('d/m/Y', strtotime($movie['DateSortie'])); ?></span></p>
                <p class="duration">Durée: <span><?php echo htmlspecialchars($movie['Durée']); ?> minutes</span></p>
                <p class="genre">Genre: <span><?php echo htmlspecialchars($movie['GENRE']); ?></span></p>
            </div>
            
            <div class="product-description">
                <p>Un film captivant réalisé par <?php echo htmlspecialchars($movie['Realisateur']); ?>, mettant en vedette <?php echo htmlspecialchars($movie['ActeursPrincipaux']); ?>. Une histoire qui vous transportera dans un univers unique et vous fera vivre des émotions intenses.</p>
                <p>Origine: <?php echo htmlspecialchars($movie['PaysOrigine']); ?> | Langue: <?php echo htmlspecialchars($movie['Langue']); ?></p>
            </div>
            
            <div class="product-price-large"><?php echo number_format($movie['Prix_unitaire'], 2, ',', ' '); ?> €</div>
            
            <div class="add-to-cart-box">
                <div class="quantity-selector">
                    <button class="quantity-btn quantity-decrease"><i class="fas fa-minus"></i></button>
                    <input type="number" value="1" min="1" class="quantity-input">
                    <button class="quantity-btn quantity-increase"><i class="fas fa-plus"></i></button>
                </div>
                
                <button class="btn add-to-cart add-to-cart-large" 
                        data-id="<?php echo $movie['ID_PROD']; ?>" 
                        data-title="<?php echo htmlspecialchars($movie['Titre']); ?>" 
                        data-price="<?php echo $movie['Prix_unitaire']; ?>"
                        data-image="<?php echo htmlspecialchars($movie['PHOTO']); ?>">
                    <i class="fas fa-shopping-cart"></i> Ajouter au panier
                </button>
            </div>
            
            <div class="product-actions-secondary">
                <button class="wishlist-btn" data-id="<?php echo $movie['ID_PROD']; ?>">
                    <i class="far fa-heart"></i> Ajouter aux favoris
                </button>
            </div>
        </div>
    </div>
    
    <div class="product-additional">
        <div class="tabs">
            <div class="tab active" data-tab="tab-description">Description</div>
            <div class="tab" data-tab="tab-actors">Acteurs</div>
            <div class="tab" data-tab="tab-details">Détails</div>
            <div class="tab" data-tab="tab-reviews">Avis</div>
        </div>
        
        <div id="tab-description" class="tab-content active">
            <h3>Synopsis</h3>
            <p>Le film raconte l'histoire de personnages extraordinaires confrontés à des défis inattendus. Un récit captivant qui vous tiendra en haleine du début à la fin.</p>
            <p>Avec des scènes mémorables et des dialogues percutants, ce film est un incontournable pour tous les amateurs de <?php echo htmlspecialchars($movie['GENRE']); ?>.</p>
            <p>Ne manquez pas cette œuvre magistrale de <?php echo htmlspecialchars($movie['Realisateur']); ?>, qui a su créer un univers à la fois fascinant et réaliste, où chaque personnage est développé avec une profondeur remarquable.</p>
        </div>
        
        <div id="tab-actors" class="tab-content">
            <h3>Acteurs principaux</h3>
            <div class="actors-list">
                <?php foreach ($actors as $actor): ?>
                    <div class="actor">
                        <img src="<?php echo $actor['image']; ?>" alt="<?php echo $actor['name']; ?>">
                        <p class="actor-name"><?php echo $actor['name']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div id="tab-details" class="tab-content">
            <h3>Informations techniques</h3>
            <table class="details-table">
                <tr>
                    <th>Titre original</th>
                    <td><?php echo htmlspecialchars($movie['Titre']); ?></td>
                </tr>
                <tr>
                    <th>Réalisateur</th>
                    <td><?php echo htmlspecialchars($movie['Realisateur']); ?></td>
                </tr>
                <tr>
                    <th>Date de sortie</th>
                    <td><?php echo date('d/m/Y', strtotime($movie['DateSortie'])); ?></td>
                </tr>
                <tr>
                    <th>Durée</th>
                    <td><?php echo htmlspecialchars($movie['Durée']); ?> minutes</td>
                </tr>
                <tr>
                    <th>Pays d'origine</th>
                    <td><?php echo htmlspecialchars($movie['PaysOrigine']); ?></td>
                </tr>
                <tr>
                    <th>Langue</th>
                    <td><?php echo htmlspecialchars($movie['Langue']); ?></td>
                </tr>
                <tr>
                    <th>Genre</th>
                    <td><?php echo htmlspecialchars($movie['GENRE']); ?></td>
                </tr>
                <tr>
                    <th>Acteurs principaux</th>
                    <td><?php echo htmlspecialchars($movie['ActeursPrincipaux']); ?></td>
                </tr>
            </table>
        </div>
        
        <div id="tab-reviews" class="tab-content">
            <h3>Avis des clients</h3>
            <div class="reviews-container">
                <div class="review">
                    <div class="review-header">
                        <div class="review-author">Jean Dupont</div>
                        <div class="review-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                        <div class="review-date">15/06/2023</div>
                    </div>
                    <div class="review-content">
                        <p>Un excellent film avec une réalisation impeccable. Les acteurs sont tous très convaincants et l'histoire est prenante du début à la fin.</p>
                    </div>
                </div>
                
                <div class="review">
                    <div class="review-header">
                        <div class="review-author">Marie Martin</div>
                        <div class="review-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="review-date">03/05/2023</div>
                    </div>
                    <div class="review-content">
                        <p>Un chef-d'œuvre absolu ! La photographie est magnifique et la bande originale sublime. Je le recommande vivement à tous les cinéphiles.</p>
                    </div>
                </div>
            </div>
            
            <div class="write-review">
                <h4>Laisser un avis</h4>
                <form class="review-form">
                    <div class="form-group">
                        <label for="review-rating">Note</label>
                        <div class="rating-select">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="review-content">Votre avis</label>
                        <textarea id="review-content" rows="5" placeholder="Partagez votre expérience avec ce film..."></textarea>
                    </div>
                    
                    <button type="submit" class="btn">Soumettre</button>
                </form>
            </div>
        </div>
    </div>
    
    <section class="similar-products">
        <h2 class="section-title">Films similaires</h2>
        <div class="products-grid">
            <?php foreach ($similarMovies as $similarMovie): ?>
                <div class="product-card">
                    <div class="product-image">
                        <a href="index.php?page=product&id=<?php echo $similarMovie['ID_PROD']; ?>">
                            <img src="<?php echo htmlspecialchars($similarMovie['PHOTO']); ?>" alt="<?php echo htmlspecialchars($similarMovie['Titre']); ?>">
                        </a>
                    </div>
                    <div class="product-info">
                        <h3 class="product-title">
                            <a href="index.php?page=product&id=<?php echo $similarMovie['ID_PROD']; ?>">
                                <?php echo htmlspecialchars($similarMovie['Titre']); ?>
                            </a>
                        </h3>
                        <p class="product-director">Réalisé par <?php echo htmlspecialchars($similarMovie['Realisateur']); ?></p>
                        <p class="product-price"><?php echo number_format($similarMovie['Prix_unitaire'], 2, ',', ' '); ?> €</p>
                        <div class="product-actions">
                            <button class="add-to-cart" 
                                    data-id="<?php echo $similarMovie['ID_PROD']; ?>" 
                                    data-title="<?php echo htmlspecialchars($similarMovie['Titre']); ?>" 
                                    data-price="<?php echo $similarMovie['Prix_unitaire']; ?>"
                                    data-image="<?php echo htmlspecialchars($similarMovie['PHOTO']); ?>">
                                <i class="fas fa-shopping-cart"></i> Ajouter
                            </button>
                            <button class="wishlist-btn" data-id="<?php echo $similarMovie['ID_PROD']; ?>">
                                <i class="far fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<style>
    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .details-table th, .details-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    
    .details-table th {
        width: 200px;
        font-weight: 600;
        color: #333;
    }
    
    .reviews-container {
        margin-bottom: 40px;
    }
    
    .review {
        background-color: var(--light-bg);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .review-author {
        font-weight: 600;
    }
    
    .review-rating {
        color: #f5c518;
    }
    
    .review-date {
        color: #666;
        font-size: 14px;
    }
    
    .write-review h4 {
        margin-bottom: 20px;
    }
    
    .rating-select {
        display: flex;
        gap: 5px;
        font-size: 24px;
        color: #f5c518;
        cursor: pointer;
    }
    
    .review-form textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        resize: vertical;
    }
    
    .product-actions-secondary {
        margin-top: 20px;
    }
    
    .product-actions-secondary .wishlist-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        padding: 10px 0;
        font-size: 16px;
    }
    
    .product-actions-secondary .wishlist-btn:hover {
        color: var(--secondary-color);
    }
</style>