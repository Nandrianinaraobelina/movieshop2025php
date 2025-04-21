<?php
/**
 * Database connection file
 * Establishes connection to MySQL database
 */

// Database credentials
$host = "localhost";
$dbname = "movieshop";
$username = "root";
$password = "";

// Create connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

/**
 * Utility functions for database operations
 */

// Get movies with optional filters
function getMovies($limit = 10, $offset = 0, $genre = null, $sort = 'DateSortie DESC') {
    global $conn;
    
    $sql = "SELECT * FROM PRODUIT WHERE 1=1";
    $params = [];
    
    if ($genre) {
        $sql .= " AND GENRE = ?";
        $params[] = $genre;
    }
    
    $sql .= " ORDER BY " . $sort . " LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    
    return $stmt->fetchAll();
}

// Get single movie by ID
function getMovie($id) {
    global $conn;
    
    $sql = "SELECT * FROM PRODUIT WHERE ID_PROD = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    
    return $stmt->fetch();
}

// Get all genres
function getGenres() {
    global $conn;
    
    $sql = "SELECT DISTINCT GENRE FROM PRODUIT ORDER BY GENRE";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get client by ID
function getClient($id) {
    global $conn;
    
    $sql = "SELECT * FROM CLIENT WHERE ID_CLIENT = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    
    return $stmt->fetch();
}

// Create new client
function createClient($nom, $prenom, $email, $adresse, $ville, $pays, $preferences, $telephone, $photo = null) {
    global $conn;
    
    $sql = "INSERT INTO CLIENT (NomCli, PrenomCli, EmailCli, AdresseCli, Ville, Pays, Preferences, TelephoneCli, PhotoCli) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nom, $prenom, $email, $adresse, $ville, $pays, $preferences, $telephone, $photo]);
    
    return $conn->lastInsertId();
}

// Create purchase record
function createPurchase($clientId, $productId, $date, $price, $quantity) {
    global $conn;
    
    $sql = "INSERT INTO ACHETER (ID_CLIENT, ID_PROD, DateAchat, Prix_unitaire, Quantite) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$clientId, $productId, $date, $price, $quantity]);
    
    return true;
}

// Get purchases for a client
function getClientPurchases($clientId) {
    global $conn;
    
    $sql = "SELECT a.*, p.Titre, p.PHOTO FROM ACHETER a
            JOIN PRODUIT p ON a.ID_PROD = p.ID_PROD
            WHERE a.ID_CLIENT = ?
            ORDER BY a.DateAchat DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$clientId]);
    
    return $stmt->fetchAll();
}
?>