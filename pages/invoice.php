<?php
// Invoice page content

// Check if order data exists in session
if (!isset($_SESSION['order'])) {
    header('Location: index.php?page=cart');
    exit;
}

$order = $_SESSION['order'];

// Add logic to print the invoice
$printScript = "";
if (!empty($_GET['print'])) {
    $printScript = "window.print();";
}
?>

<div class="container">
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-logo">
                <h2>Movie<span>SHOP</span></h2>
                <p>123 rue du Cinéma, 75000 Paris</p>
                <p>contact@movieshop.fr</p>
                <p>+33 1 23 45 67 89</p>
            </div>
            
            <div class="invoice-details">
                <div class="invoice-id">Facture #<?php echo $order['invoiceId']; ?></div>
                <div class="invoice-date">Date: <?php echo date('d/m/Y', strtotime($order['date'])); ?></div>
            </div>
        </div>
        
        <div class="invoice-addresses">
            <div class="invoice-address">
                <h3>Facturé à</h3>
                <p><?php echo $order['customer']['firstName'] . ' ' . $order['customer']['lastName']; ?></p>
                <p><?php echo $order['customer']['address']; ?></p>
                <p><?php echo $order['customer']['zipCode'] . ' ' . $order['customer']['city']; ?></p>
                <p><?php echo $order['customer']['country']; ?></p>
                <p>Email: <?php echo $order['customer']['email']; ?></p>
                <?php if (!empty($order['customer']['phone'])): ?>
                    <p>Tél: <?php echo $order['customer']['phone']; ?></p>
                <?php endif; ?>
            </div>
            
            <div class="invoice-address">
                <h3>Livré à</h3>
                <p><?php echo $order['customer']['firstName'] . ' ' . $order['customer']['lastName']; ?></p>
                <p><?php echo $order['customer']['address']; ?></p>
                <p><?php echo $order['customer']['zipCode'] . ' ' . $order['customer']['city']; ?></p>
                <p><?php echo $order['customer']['country']; ?></p>
            </div>
        </div>
        
        <div class="invoice-products">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Prix unitaire</th>
                        <th>Quantité</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['cart'] as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['title']); ?></td>
                            <td><?php echo number_format($item['price'], 2, ',', ' '); ?> €</td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo number_format($item['price'] * $item['quantity'], 2, ',', ' '); ?> €</td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="3">Sous-total</td>
                        <td><?php echo number_format($order['subtotal'], 2, ',', ' '); ?> €</td>
                    </tr>
                    <tr>
                        <td colspan="3">Frais de livraison</td>
                        <td><?php echo number_format($order['shipping'], 2, ',', ' '); ?> €</td>
                    </tr>
                    <tr>
                        <td colspan="3">Total</td>
                        <td><?php echo number_format($order['total'], 2, ',', ' '); ?> €</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="invoice-payment-info">
            <h3>Informations de paiement</h3>
            <p>
                <strong>Méthode de paiement:</strong> 
                <?php 
                    $paymentMethod = $order['paymentMethod'];
                    if ($paymentMethod === 'card') {
                        echo 'Carte bancaire';
                    } elseif ($paymentMethod === 'paypal') {
                        echo 'PayPal';
                    } elseif ($paymentMethod === 'transfer') {
                        echo 'Virement bancaire';
                    }
                ?>
            </p>
            <p><strong>Statut du paiement:</strong> Payé</p>
            <p><strong>Date du paiement:</strong> <?php echo date('d/m/Y', strtotime($order['date'])); ?></p>
        </div>
        
        <div class="invoice-notes">
            <p>Merci pour votre commande! Nous espérons que vous apprécierez vos films.</p>
            <p>Pour toute question concernant votre commande, n'hésitez pas à nous contacter.</p>
        </div>
        
        <div class="invoice-footer">
            <p>MovieSHOP - SIRET 123 456 789 00012 - TVA FR12345678900</p>
        </div>
        
        <button class="print-button">
            <i class="fas fa-print"></i> Imprimer la facture
        </button>
    </div>
</div>

<style>
    @media print {
        header, footer, .print-button {
            display: none !important;
        }
        
        body {
            background-color: #fff !important;
        }
        
        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
        }
        
        .invoice-container {
            box-shadow: none !important;
            padding: 0 !important;
        }
    }
    
    .invoice-payment-info {
        margin-top: 30px;
        margin-bottom: 30px;
    }
    
    .invoice-payment-info h3 {
        font-size: 18px;
        margin-bottom: 15px;
        color: var(--primary-color);
    }
    
    .invoice-payment-info p {
        margin-bottom: 10px;
    }
    
    .invoice-notes {
        margin-bottom: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
</style>

<script>
    <?php echo $printScript; ?>
</script>