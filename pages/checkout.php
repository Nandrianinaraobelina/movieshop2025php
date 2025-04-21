<?php
// Checkout page content

// Check if user is logged in, if not redirect to login
if (!isset($_SESSION['user_id'])) {
    // For demo, we'll proceed anyway but in a real app would redirect to login
    // header('Location: index.php?page=login&redirect=checkout');
    // exit;
}

// Get cart from session or localStorage (via JavaScript)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// If cart is empty, redirect to cart page
if (empty($cart)) {
    header('Location: index.php?page=cart');
    exit;
}

// Calculate totals
$subtotal = 0;
$shipping = 5.99; // Default shipping cost

foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

// If subtotal is above 50€, free shipping
if ($subtotal >= 50) {
    $shipping = 0;
}

$total = $subtotal + $shipping;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $errors = [];
    
    // Required fields
    $requiredFields = [
        'firstName' => 'Prénom',
        'lastName' => 'Nom',
        'email' => 'Email',
        'address' => 'Adresse',
        'city' => 'Ville',
        'zipCode' => 'Code postal',
        'country' => 'Pays'
    ];
    
    foreach ($requiredFields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[] = "Le champ {$label} est requis.";
        }
    }
    
    // Email validation
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }
    
    // If no errors, process order
    if (empty($errors)) {
        // For demo, we'll just redirect to invoice page
        // In a real app, would save order to database, process payment, etc.
        
        // Create a unique invoice ID
        $invoiceId = 'INV-' . date('YmdHis');
        
        // Store order data in session for invoice page
        $_SESSION['order'] = [
            'invoiceId' => $invoiceId,
            'date' => date('Y-m-d H:i:s'),
            'customer' => [
                'firstName' => $_POST['firstName'],
                'lastName' => $_POST['lastName'],
                'email' => $_POST['email'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'zipCode' => $_POST['zipCode'],
                'country' => $_POST['country'],
                'phone' => $_POST['phone']
            ],
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'paymentMethod' => $_POST['paymentMethod']
        ];
        
        // Redirect to invoice page
        header('Location: index.php?page=invoice');
        exit;
    }
}
?>

<div class="container">
    <div class="page-header">
        <h1>Finaliser votre commande</h1>
    </div>
    
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="error-messages">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <div class="checkout-container">
        <div class="checkout-form">
            <form method="POST" action="index.php?page=checkout">
                <h2>Informations de livraison</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">Prénom*</label>
                        <input type="text" id="firstName" name="firstName" class="form-input" value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Nom*</label>
                        <input type="text" id="lastName" name="lastName" class="form-input" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email*</label>
                    <input type="email" id="email" name="email" class="form-input" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="address">Adresse*</label>
                    <input type="text" id="address" name="address" class="form-input" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">Ville*</label>
                        <input type="text" id="city" name="city" class="form-input" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="zipCode">Code postal*</label>
                        <input type="text" id="zipCode" name="zipCode" class="form-input" value="<?php echo isset($_POST['zipCode']) ? htmlspecialchars($_POST['zipCode']) : ''; ?>" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="country">Pays*</label>
                    <select id="country" name="country" class="form-input" required>
                        <option value="France" <?php echo (isset($_POST['country']) && $_POST['country'] === 'France') ? 'selected' : ''; ?>>France</option>
                        <option value="Belgique" <?php echo (isset($_POST['country']) && $_POST['country'] === 'Belgique') ? 'selected' : ''; ?>>Belgique</option>
                        <option value="Suisse" <?php echo (isset($_POST['country']) && $_POST['country'] === 'Suisse') ? 'selected' : ''; ?>>Suisse</option>
                        <option value="Canada" <?php echo (isset($_POST['country']) && $_POST['country'] === 'Canada') ? 'selected' : ''; ?>>Canada</option>
                    </select>
                </div>
                
                <h2>Mode de paiement</h2>
                
                <div class="payment-methods-radio">
                    <div class="payment-method">
                        <input type="radio" id="payment-card" name="paymentMethod" value="card" checked>
                        <label for="payment-card">
                            <span class="radio-icon"></span>
                            <span class="payment-name">Carte bancaire</span>
                            <span class="payment-icons">
                                <i class="fab fa-cc-visa"></i>
                                <i class="fab fa-cc-mastercard"></i>
                                <i class="fab fa-cc-amex"></i>
                            </span>
                        </label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="payment-paypal" name="paymentMethod" value="paypal">
                        <label for="payment-paypal">
                            <span class="radio-icon"></span>
                            <span class="payment-name">PayPal</span>
                            <span class="payment-icons">
                                <i class="fab fa-cc-paypal"></i>
                            </span>
                        </label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="payment-transfer" name="paymentMethod" value="transfer">
                        <label for="payment-transfer">
                            <span class="radio-icon"></span>
                            <span class="payment-name">Virement bancaire</span>
                        </label>
                    </div>
                </div>
                
                <div id="payment-card-fields" class="payment-fields">
                    <div class="form-group">
                        <label for="card-number">Numéro de carte</label>
                        <input type="text" id="card-number" name="cardNumber" class="form-input" placeholder="1234 5678 9012 3456">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card-expiry">Date d'expiration</label>
                            <input type="text" id="card-expiry" name="cardExpiry" class="form-input" placeholder="MM/AA">
                        </div>
                        <div class="form-group">
                            <label for="card-cvc">CVC</label>
                            <input type="text" id="card-cvc" name="cardCvc" class="form-input" placeholder="123">
                        </div>
                    </div>
                </div>
                
                <div id="payment-paypal-fields" class="payment-fields" style="display: none;">
                    <p>Vous serez redirigé vers PayPal pour finaliser votre paiement.</p>
                </div>
                
                <div id="payment-transfer-fields" class="payment-fields" style="display: none;">
                    <p>Informations pour le virement bancaire:</p>
                    <p>IBAN: FR76 3000 4000 0400 0012 3456 789</p>
                    <p>BIC: BNPAFRPPXXX</p>
                    <p>Banque: BNP Paribas</p>
                    <p>Bénéficiaire: MovieSHOP SA</p>
                    <p>Référence: Votre email</p>
                </div>
                
                <div class="terms-checkbox">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">J'accepte les <a href="#">conditions générales de vente</a> et la <a href="#">politique de confidentialité</a>*</label>
                </div>
                
                <button type="submit" class="btn checkout-btn">Valider la commande</button>
            </form>
        </div>
        
        <div class="checkout-summary">
            <h3>Récapitulatif de la commande</h3>
            
            <div class="checkout-products">
                <?php foreach ($cart as $item): ?>
                    <div class="checkout-product">
                        <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                        <div class="checkout-product-info">
                            <h4 class="checkout-product-title"><?php echo htmlspecialchars($item['title']); ?></h4>
                            <p class="checkout-product-quantity">Quantité: <?php echo $item['quantity']; ?></p>
                        </div>
                        <div class="checkout-product-price"><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="summary-item summary-subtotal">
                <span>Sous-total</span>
                <span class="amount"><?php echo number_format($subtotal, 2, ',', ' '); ?> €</span>
            </div>
            <div class="summary-item summary-shipping">
                <span>Frais de livraison</span>
                <span class="amount"><?php echo number_format($shipping, 2, ',', ' '); ?> €</span>
            </div>
            <div class="summary-item summary-total">
                <span>Total</span>
                <span class="amount"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
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
    
    .error-messages {
        background-color: #f8d7da;
        color: #721c24;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    
    .error-messages ul {
        list-style-type: disc;
        margin-left: 20px;
    }
    
    .payment-methods-radio {
        margin-bottom: 30px;
    }
    
    .payment-method {
        margin-bottom: 15px;
    }
    
    .payment-method input[type="radio"] {
        display: none;
    }
    
    .payment-method label {
        display: flex;
        align-items: center;
        cursor: pointer;
    }
    
    .radio-icon {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-color);
        border-radius: 50%;
        margin-right: 10px;
        position: relative;
    }
    
    .payment-method input[type="radio"]:checked + label .radio-icon:after {
        content: '';
        width: 10px;
        height: 10px;
        background-color: var(--primary-color);
        border-radius: 50%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .payment-name {
        flex-grow: 1;
    }
    
    .payment-icons {
        font-size: 24px;
        color: #666;
    }
    
    .payment-fields {
        margin-bottom: 30px;
        padding: 20px;
        background-color: var(--light-bg);
        border-radius: 4px;
    }
    
    .terms-checkbox {
        margin-bottom: 30px;
    }
    
    .terms-checkbox a {
        color: var(--primary-color);
        text-decoration: underline;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payment method toggle
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        const paymentFields = document.querySelectorAll('.payment-fields');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                // Hide all payment fields
                paymentFields.forEach(field => {
                    field.style.display = 'none';
                });
                
                // Show selected payment field
                const selectedField = document.getElementById(`payment-${this.value}-fields`);
                if (selectedField) {
                    selectedField.style.display = 'block';
                }
            });
        });
    });
</script>