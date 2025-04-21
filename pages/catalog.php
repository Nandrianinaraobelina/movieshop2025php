<?php
// Catalog page content

// Get filter parameters from URL
$genre = isset($_GET['genre']) ? $_GET['genre'] : null;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'DateSortie DESC';
$search = isset($_GET['search']) ? $_GET['search'] : null;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$limit = 12;
$offset = ($page - 1) * $limit;

// Get total movies count for pagination
$totalMoviesQuery = "SELECT COUNT(*) as total FROM PRODUIT WHERE 1=1";
$params = [];

if ($genre) {
    $totalMoviesQuery .= " AND GENRE = ?";
    $params[] = $genre;
}

if ($search) {
    $totalMoviesQuery .= " AND (Titre LIKE ? OR Realisateur LIKE ? OR ActeursPrincipaux LIKE ?)";
    $searchTerm = "%{$search}%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$stmt = $conn->prepare($totalMoviesQuery);
$stmt->execute($params);
$totalMovies = $stmt->fetch()['total'];
$totalPages = ceil($totalMovies / $limit);

// Get movies with filters
$moviesQuery = "SELECT * FROM PRODUIT WHERE 1=1";
$moviesParams = [];

if ($genre) {
    $moviesQuery .= " AND GENRE = ?";
    $moviesParams[] = $genre;
}

if ($search) {
    $moviesQuery .= " AND (Titre LIKE ? OR Realisateur LIKE ? OR ActeursPrincipaux LIKE ?)";
    $searchTerm = "%{$search}%";
    $moviesParams[] = $searchTerm;
    $moviesParams[] = $searchTerm;
    $moviesParams[] = $searchTerm;
}

$moviesQuery .= " ORDER BY {$sort} LIMIT {$limit} OFFSET {$offset}";
$stmt = $conn->prepare($moviesQuery);
$stmt->execute($moviesParams);
$movies = $stmt->fetchAll();

// Get all genres for filter
$genres = getGenres();
?>

<div class="container">
    <div class="page-header">
        <h1>
            <?php if ($search): ?>
                Résultats de recherche pour "<?php echo htmlspecialchars($search); ?>"
            <?php elseif ($genre): ?>
                Films - <?php echo htmlspecialchars($genre); ?>
            <?php else: ?>
                Tous les films
            <?php endif; ?>
        </h1>
    </div>

    <div class="catalog-container" style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        <!-- Filters Sidebar -->
        <div class="filters-sidebar">
            <div class="filter-section">
                <h3>Genres</h3>
                <ul class="filter-list">
                    <li><a href="index.php?page=catalog" class="<?php echo !$genre ? 'active' : ''; ?>">Tous les genres</a></li>
                    <?php foreach ($genres as $g): ?>
                        <li>
                            <a href="index.php?page=catalog&genre=<?php echo urlencode($g); ?>" 
                               class="<?php echo $genre === $g ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($g); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="filter-section">
                <h3>Tri</h3>
                <ul class="filter-list">
                    <li>
                        <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?>&sort=DateSortie+DESC" 
                           class="<?php echo $sort === 'DateSortie DESC' ? 'active' : ''; ?>">
                            Plus récents
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?>&sort=DateSortie+ASC" 
                           class="<?php echo $sort === 'DateSortie ASC' ? 'active' : ''; ?>">
                            Plus anciens
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?>&sort=Prix_unitaire+ASC" 
                           class="<?php echo $sort === 'Prix_unitaire ASC' ? 'active' : ''; ?>">
                            Prix croissant
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?>&sort=Prix_unitaire+DESC" 
                           class="<?php echo $sort === 'Prix_unitaire DESC' ? 'active' : ''; ?>">
                            Prix décroissant
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?>&sort=Titre+ASC" 
                           class="<?php echo $sort === 'Titre ASC' ? 'active' : ''; ?>">
                            Titre A-Z
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?>&sort=Titre+DESC" 
                           class="<?php echo $sort === 'Titre DESC' ? 'active' : ''; ?>">
                            Titre Z-A
                        </a>
                    </li>
                </ul>
            </div>

            <div class="filter-section">
                <h3>Prix</h3>
                <form action="index.php" method="GET" class="price-filter-form">
                    <input type="hidden" name="page" value="catalog">
                    <?php if ($genre): ?>
                        <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
                    <?php endif; ?>
                    <?php if ($sort): ?>
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($sort); ?>">
                    <?php endif; ?>
                    
                    <div class="price-inputs">
                        <input type="number" name="min_price" placeholder="Min" min="0" step="0.01" value="<?php echo isset($_GET['min_price']) ? htmlspecialchars($_GET['min_price']) : ''; ?>">
                        <span>à</span>
                        <input type="number" name="max_price" placeholder="Max" min="0" step="0.01" value="<?php echo isset($_GET['max_price']) ? htmlspecialchars($_GET['max_price']) : ''; ?>">
                    </div>
                    <button type="submit" class="filter-btn">Appliquer</button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="catalog-content">
            <?php if (empty($movies)): ?>
                <div class="no-results">
                    <p>Aucun film ne correspond à vos critères de recherche.</p>
                    <a href="index.php?page=catalog" class="btn">Voir tous les films</a>
                </div>
            <?php else: ?>
                <div class="catalog-header">
                    <div class="results-count">
                        <?php echo $totalMovies; ?> résultat<?php echo $totalMovies > 1 ? 's' : ''; ?> trouvé<?php echo $totalMovies > 1 ? 's' : ''; ?>
                    </div>
                </div>

                <div class="products-grid">
                    <?php foreach ($movies as $movie): ?>
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
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?><?php echo $sort ? '&sort=' . urlencode($sort) : ''; ?>&p=<?php echo $page - 1; ?>" class="pagination-prev">
                                <i class="fas fa-chevron-left"></i> Précédent
                            </a>
                        <?php endif; ?>
                        
                        <div class="pagination-numbers">
                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?><?php echo $sort ? '&sort=' . urlencode($sort) : ''; ?>&p=<?php echo $i; ?>" 
                                   class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                        </div>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="index.php?page=catalog<?php echo $genre ? '&genre=' . urlencode($genre) : ''; ?><?php echo $sort ? '&sort=' . urlencode($sort) : ''; ?>&p=<?php echo $page + 1; ?>" class="pagination-next">
                                Suivant <i class="fas fa-chevron-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .page-header h1 {
        font-size: 32px;
        margin-bottom: 30px;
        color: var(--primary-color);
    }
    
    .filters-sidebar {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        align-self: start;
    }
    
    .filter-section {
        margin-bottom: 30px;
    }
    
    .filter-section h3 {
        font-size: 18px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .filter-list li {
        margin-bottom: 10px;
    }
    
    .filter-list a {
        display: block;
        transition: transform 0.3s ease;
    }
    
    .filter-list a:hover {
        transform: translateX(5px);
    }
    
    .filter-list a.active {
        color: var(--secondary-color);
        font-weight: 600;
    }
    
    .price-inputs {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .price-inputs input {
        width: 80px;
        padding: 8px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
    }
    
    .price-inputs span {
        margin: 0 10px;
    }
    
    .filter-btn {
        width: 100%;
        padding: 10px;
        background-color: var(--primary-color);
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    
    .filter-btn:hover {
        background-color: var(--secondary-color);
    }
    
    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .results-count {
        font-size: 16px;
        color: #666;
    }
    
    .no-results {
        text-align: center;
        padding: 60px 0;
    }
    
    .no-results p {
        font-size: 18px;
        margin-bottom: 20px;
    }
    
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 40px;
    }
    
    .pagination-prev, .pagination-next {
        padding: 10px 15px;
        background-color: var(--light-bg);
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }
    
    .pagination-prev:hover, .pagination-next:hover {
        background-color: var(--border-color);
    }
    
    .pagination-numbers {
        display: flex;
        gap: 10px;
        margin: 0 15px;
    }
    
    .pagination-number {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: var(--light-bg);
        transition: background-color 0.3s ease, color 0.3s ease;
    }
    
    .pagination-number:hover {
        background-color: var(--border-color);
    }
    
    .pagination-number.active {
        background-color: var(--primary-color);
        color: white;
    }
    
    @media (max-width: 991.98px) {
        .catalog-container {
            grid-template-columns: 1fr;
        }
        
        .filters-sidebar {
            margin-bottom: 30px;
        }
    }
</style>