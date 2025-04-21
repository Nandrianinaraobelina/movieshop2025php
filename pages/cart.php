<?php
// Cart page content

// Get cart from session or localStorage (via JavaScript)
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Flag to know if cart is empty
$isCartEmpty = empty($cart);

// Calculate totals
$subtotal = 0;
$shipping = 5.99; // Default shipping cost
$total = 0;

if (!$isCartEmpty) {
    foreach ($cart as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    
    // If subtotal is above 50€, free shipping
    if ($subtotal >= 50) {
        $shipping = 0;
    }
    
    $total = $subtotal + $shipping;
}
?>

<div class="container">
    <?php if ($isCartEmpty): ?>
        <div class="cart-page">
            <div class="cart-header">
                <h2>Votre panier</h2>
            </div>
            <div class="empty-cart">
                <p>Votre panier est vide.</p>
                <a href="index.php?page=catalog" class="btn">Continuer mes achats</a>
            </div>
        </div>
    <?php else: ?>
        <div class="cart-page">
            <div class="cart-header">
                <h2>Votre panier</h2>
            </div>
            
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix</th>
                        <th>Quantité</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td>
                                <div class="cart-product">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                    <div class="cart-product-info">
                                        <h4><?php echo htmlspecialchars($item['title']); ?></h4>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                            <td>
                                <div class="quantity-control">
                                    <button class="quantity-btn quantity-decrease"><i class="fas fa-minus"></i></button>
                                    <input type="number" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">
                                    <button class="quantity-btn quantity-increase"><i class="fas fa-plus"></i></button>
                                </div>
                            </td>
                            <td class="subtotal"><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</td>
                            <td>
                                <button class="remove-item" data-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-actions" style="display: flex; justify-content: space-between; margin-top: 30px;">
                <a href="index.php?page=catalog" class="btn btn-outline">Continuer mes achats</a>
                <button id="update-cart" class="btn">Mettre à jour le panier</button>
            </div>
            
            <div class="cart-bottom" style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 40px;">
                <div class="coupon-section">
                    <h3>Code promo</h3>
                    <p>Si vous avez un code promo, veuillez l'entrer ci-dessous.</p>
                    <form class="coupon-form" style="display: flex; margin-top: 15px;">
                        <input type="text" placeholder="Code promo" style="flex-grow: 1; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 4px 0 0 4px;">
                        <button type="submit" class="btn" style="border-radius: 0 4px 4px 0;">Appliquer</button>
                    </form>
                </div>
                
                <div class="cart-summary">
                    <h3>Récapitulatif de la commande</h3>
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
                    <a href="index.php?page=checkout" class="checkout-btn">Procéder au paiement</a>
                </div>
            </div>
        </div>
        
        <script>
            // Initialize cart from localStorage
            document.addEventListener('DOMContentLoaded', function() {
                // Load cart from localStorage
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                
                // If cart is empty but we're on the cart page with products, something is wrong
                // This could happen if user clears localStorage but has items in session
                // In this case, we'll repopulate localStorage from the displayed items
                if (cart.length === 0 && document.querySelectorAll('.cart-table tbody tr').length > 0) {
                    const rows = document.querySelectorAll('.cart-table tbody tr');
                    const newCart = [];
                    
                    rows.forEach(row => {
                        const productId = row.querySelector('.remove-item').dataset.id;
                        const productTitle = row.querySelector('.cart-product-info h4').textContent;
                        const productPrice = parseFloat(row.querySelector('.quantity-input').dataset.price);
                        const productQuantity = parseInt(row.querySelector('.quantity-input').value);
                        const productImage = row.querySelector('.cart-product img').src;
                        
                        newCart.push({
                            id: productId,
                            title: productTitle,
                            price: productPrice,
                            quantity: productQuantity,
                            image: productImage
                        });
                    });
                    
                    localStorage.setItem('cart', JSON.stringify(newCart));
                }
                
                // Update cart button
                document.getElementById('update-cart').addEventListener('click', function() {
                    location.reload();
                });
            });
        </script>
    <?php endif; ?>
</div>