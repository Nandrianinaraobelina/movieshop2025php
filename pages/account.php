<?php
// Account page content

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login?redirect=account');
    exit;
}

// Get user data
$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

// For demo, we'll use dummy data
$user = [
    'id' => $userId,
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'john.doe@example.com',
    'phone' => '+33 6 12 34 56 78',
    'address' => '123 Rue de Paris',
    'city' => 'Paris',
    'zipCode' => '75001',
    'country' => 'France'
];

// Get user orders (dummy data)
$orders = [
    [
        'id' => 'ORD-123456',
        'date' => '2023-04-15',
        'total' => 45.99,
        'status' => 'Livré'
    ],
    [
        'id' => 'ORD-123457',
        'date' => '2023-03-20',
        'total' => 32.50,
        'status' => 'Livré'
    ],
    [
        'id' => 'ORD-123458',
        'date' => '2023-02-10',
        'total' => 29.99,
        'status' => 'Livré'
    ]
];
?>

<div class="container">
    <div class="page-header">
        <h1>Mon compte</h1>
    </div>
    
    <div class="account-container" style="display: grid; grid-template-columns: 250px 1fr; gap: 30px;">
        <!-- Account Navigation -->
        <div class="account-nav">
            <div class="account-welcome">
                <h3>Bonjour, <?php echo htmlspecialchars($user['firstName']); ?></h3>
            </div>
            
            <ul class="account-menu">
                <li class="active"><a href="#profile">Mon profil</a></li>
                <li><a href="#orders">Mes commandes</a></li>
                <li><a href="#addresses">Mes adresses</a></li>
                <li><a href="#wishlist">Ma liste de souhaits</a></li>
                <li><a href="index.php?page=logout">Déconnexion</a></li>
            </ul>
        </div>
        
        <!-- Account Content -->
        <div class="account-content">
            <!-- Profile Section -->
            <div id="profile" class="account-section active">
                <h2>Mon profil</h2>
                
                <div class="profile-info">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" class="form-input" value="<?php echo htmlspecialchars($user['firstName']); ?>">
                        </div>
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" class="form-input" value="<?php echo htmlspecialchars($user['lastName']); ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="tel" class="form-input" value="<?php echo htmlspecialchars($user['phone']); ?>">
                    </div>
                    
                    <button class="btn">Mettre à jour le profil</button>
                </div>
                
                <div class="change-password">
                    <h3>Changer le mot de passe</h3>
                    
                    <div class="form-group">
                        <label>Mot de passe actuel</label>
                        <input type="password" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" class="form-input">
                    </div>
                    
                    <div class="form-group">
                        <label>Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-input">
                    </div>
                    
                    <button class="btn">Changer le mot de passe</button>
                </div>
            </div>
            
            <!-- Orders Section -->
            <div id="orders" class="account-section">
                <h2>Mes commandes</h2>
                
                <?php if (empty($orders)): ?>
                    <p>Vous n'avez pas encore de commande.</p>
                <?php else: ?>
                    <div class="orders-list">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Commande</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['id']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($order['date'])); ?></td>
                                        <td><?php echo number_format($order['total'], 2, ',', ' '); ?> €</td>
                                        <td>
                                            <span class="order-status <?php echo strtolower($order['status']); ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="index.php?page=invoice&id=<?php echo $order['id']; ?>" class="btn-small">Voir la facture</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Addresses Section -->
            <div id="addresses" class="account-section">
                <h2>Mes adresses</h2>
                
                <div class="addresses-grid">
                    <div class="address-card">
                        <h3>Adresse de livraison</h3>
                        <p><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></p>
                        <p><?php echo $user['address']; ?></p>
                        <p><?php echo $user['zipCode'] . ' ' . $user['city']; ?></p>
                        <p><?php echo $user['country']; ?></p>
                        <div class="address-actions">
                            <button class="btn-small">Modifier</button>
                        </div>
                    </div>
                    
                    <div class="address-card">
                        <h3>Adresse de facturation</h3>
                        <p><?php echo $user['firstName'] . ' ' . $user['lastName']; ?></p>
                        <p><?php echo $user['address']; ?></p>
                        <p><?php echo $user['zipCode'] . ' ' . $user['city']; ?></p>
                        <p><?php echo $user['country']; ?></p>
                        <div class="address-actions">
                            <button class="btn-small">Modifier</button>
                        </div>
                    </div>
                    
                    <div class="address-card add-address">
                        <div class="add-address-content">
                            <i class="fas fa-plus-circle"></i>
                            <p>Ajouter une nouvelle adresse</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Wishlist Section -->
            <div id="wishlist" class="account-section">
                <h2>Ma liste de souhaits</h2>
                
                <p>Vous n'avez pas encore de produit dans votre liste de souhaits.</p>
                
                <a href="index.php?page=catalog" class="btn">Parcourir les films</a>
            </div>
        </div>
    </div>
</div>

<style>
    .page-header h1 {
        font-size: 32px;
        margin-bottom: 30px;
        color: var(--primary-color);
    }
    
    .account-nav {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    .account-welcome {
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .account-welcome h3 {
        font-size: 18px;
        color: var(--primary-color);
    }
    
    .account-menu {
        list-style: none;
    }
    
    .account-menu li {
        margin-bottom: 10px;
    }
    
    .account-menu li a {
        display: block;
        padding: 10px;
        border-radius: 4px;
        transition: background-color 0.3s ease;
    }
    
    .account-menu li a:hover {
        background-color: var(--light-bg);
    }
    
    .account-menu li.active a {
        background-color: var(--primary-color);
        color: white;
    }
    
    .account-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    .account-section {
        display: none;
    }
    
    .account-section.active {
        display: block;
    }
    
    .account-section h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: var(--primary-color);
    }
    
    .profile-info {
        margin-bottom: 40px;
    }
    
    .change-password h3 {
        font-size: 18px;
        margin-bottom: 20px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
    
    .orders-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .orders-table th, .orders-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    
    .orders-table th {
        background-color: var(--light-bg);
        font-weight: 600;
    }
    
    .order-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }
    
    .order-status.livré {
        background-color: #e8f5e9;
        color: #2e7d32;
    }
    
    .order-status.en-transit {
        background-color: #e3f2fd;
        color: #1565c0;
    }
    
    .order-status.en-préparation {
        background-color: #fff8e1;
        color: #ff8f00;
    }
    
    .btn-small {
        display: inline-block;
        padding: 6px 12px;
        background-color: var(--primary-color);
        color: white;
        border-radius: 4px;
        font-size: 14px;
        text-align: center;
        transition: background-color 0.3s ease;
    }
    
    .btn-small:hover {
        background-color: var(--secondary-color);
        color: white;
    }
    
    .addresses-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .address-card {
        background-color: var(--light-bg);
        padding: 20px;
        border-radius: 8px;
        position: relative;
    }
    
    .address-card h3 {
        font-size: 16px;
        margin-bottom: 15px;
        color: var(--primary-color);
    }
    
    .address-card p {
        margin-bottom: 5px;
    }
    
    .address-actions {
        margin-top: 15px;
    }
    
    .add-address {
        border: 2px dashed var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: border-color 0.3s ease;
    }
    
    .add-address:hover {
        border-color: var(--primary-color);
    }
    
    .add-address-content {
        text-align: center;
    }
    
    .add-address-content i {
        font-size: 24px;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    @media (max-width: 991.98px) {
        .account-container {
            grid-template-columns: 1fr;
        }
        
        .account-nav {
            margin-bottom: 30px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Account tabs
        const menuItems = document.querySelectorAll('.account-menu li a');
        const sections = document.querySelectorAll('.account-section');
        
        menuItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get the section ID from href
                const sectionId = this.getAttribute('href').substring(1);
                
                // Remove active class from all menu items and sections
                menuItems.forEach(menuItem => {
                    menuItem.parentElement.classList.remove('active');
                });
                
                sections.forEach(section => {
                    section.classList.remove('active');
                });
                
                // Add active class to clicked menu item and corresponding section
                this.parentElement.classList.add('active');
                document.getElementById(sectionId).classList.add('active');
            });
        });
    });
</script>