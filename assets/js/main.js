/**
 * MovieSHOP - Main JavaScript file
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize cart functionality
    initCart();
    
    // Initialize product thumbnail gallery
    initProductGallery();
    
    // Initialize tabs on product page
    initTabs();
    
    // Initialize quantity controls
    initQuantityControls();
    
    // Initialize print invoice functionality
    initPrintInvoice();
});

/**
 * Initialize cart functionality
 */
function initCart() {
    // Add to cart buttons
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    
    if (addToCartButtons.length > 0) {
        addToCartButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const productId = this.dataset.id;
                const productTitle = this.dataset.title;
                const productPrice = parseFloat(this.dataset.price);
                const productImage = this.dataset.image;
                let quantity = 1;
                
                // If on product detail page, get quantity from input
                const quantityInput = document.querySelector('.quantity-input');
                if (quantityInput) {
                    quantity = parseInt(quantityInput.value);
                }
                
                addToCart(productId, productTitle, productPrice, quantity, productImage);
                updateCartCounter();
                
                // Show notification
                showNotification(`${productTitle} a été ajouté au panier`);
            });
        });
    }
    
    // Remove from cart buttons
    const removeButtons = document.querySelectorAll('.remove-item');
    
    if (removeButtons.length > 0) {
        removeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.id;
                removeFromCart(productId);
                
                // Remove the item row from the table
                this.closest('tr').remove();
                
                // Update cart totals
                updateCartTotals();
                updateCartCounter();
                
                // If cart is empty, show empty cart message
                const cartTable = document.querySelector('.cart-table');
                if (cartTable && cartTable.querySelectorAll('tbody tr').length === 0) {
                    const cartPage = document.querySelector('.cart-page');
                    cartPage.innerHTML = `
                        <div class="cart-header">
                            <h2>Votre panier</h2>
                        </div>
                        <div class="empty-cart">
                            <p>Votre panier est vide.</p>
                            <a href="index.php?page=catalog" class="btn">Continuer mes achats</a>
                        </div>
                    `;
                }
            });
        });
    }
    
    // Quantity change in cart
    const quantityInputs = document.querySelectorAll('.cart-table .quantity-input');
    
    if (quantityInputs.length > 0) {
        quantityInputs.forEach(input => {
            input.addEventListener('change', function() {
                const productId = this.dataset.id;
                const quantity = parseInt(this.value);
                
                if (quantity > 0) {
                    updateCartItemQuantity(productId, quantity);
                    
                    // Update item subtotal
                    const price = parseFloat(this.dataset.price);
                    const subtotalElement = this.closest('tr').querySelector('.subtotal');
                    subtotalElement.textContent = (price * quantity).toFixed(2) + ' €';
                    
                    // Update cart totals
                    updateCartTotals();
                    updateCartCounter();
                } else {
                    // If quantity is 0 or negative, reset to 1
                    this.value = 1;
                }
            });
        });
    }
}

/**
 * Add product to cart
 */
function addToCart(productId, title, price, quantity, image) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Check if product already exists in cart
    const existingItemIndex = cart.findIndex(item => item.id === productId);
    
    if (existingItemIndex !== -1) {
        // If exists, update quantity
        cart[existingItemIndex].quantity += quantity;
    } else {
        // If not, add new item
        cart.push({
            id: productId,
            title: title,
            price: price,
            quantity: quantity,
            image: image
        });
    }
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
}

/**
 * Remove product from cart
 */
function removeFromCart(productId) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Filter out the item
    cart = cart.filter(item => item.id !== productId);
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
}

/**
 * Update cart item quantity
 */
function updateCartItemQuantity(productId, quantity) {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Find the item and update quantity
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity = quantity;
    }
    
    // Save to localStorage
    localStorage.setItem('cart', JSON.stringify(cart));
}

/**
 * Update cart counter in header
 */
function updateCartCounter() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const cartCount = document.querySelector('.cart-count');
    
    if (cartCount) {
        let total = 0;
        cart.forEach(item => {
            total += item.quantity;
        });
        
        cartCount.textContent = total;
    }
}

/**
 * Update cart totals on cart page
 */
function updateCartTotals() {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    const subtotalElement = document.querySelector('.summary-subtotal .amount');
    const shippingElement = document.querySelector('.summary-shipping .amount');
    const totalElement = document.querySelector('.summary-total .amount');
    
    if (subtotalElement && totalElement) {
        let subtotal = 0;
        
        cart.forEach(item => {
            subtotal += item.price * item.quantity;
        });
        
        const shipping = subtotal > 0 ? parseFloat(shippingElement.textContent) : 0;
        const total = subtotal + shipping;
        
        subtotalElement.textContent = subtotal.toFixed(2) + ' €';
        totalElement.textContent = total.toFixed(2) + ' €';
    }
}

/**
 * Show notification
 */
function showNotification(message) {
    // Check if notification container exists, create if not
    let notifContainer = document.querySelector('.notification-container');
    
    if (!notifContainer) {
        notifContainer = document.createElement('div');
        notifContainer.className = 'notification-container';
        document.body.appendChild(notifContainer);
        
        // Style the notification container
        notifContainer.style.position = 'fixed';
        notifContainer.style.top = '20px';
        notifContainer.style.right = '20px';
        notifContainer.style.zIndex = '1000';
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.textContent = message;
    
    // Style the notification
    notification.style.backgroundColor = 'var(--primary-color)';
    notification.style.color = 'white';
    notification.style.padding = '12px 20px';
    notification.style.borderRadius = '4px';
    notification.style.marginBottom = '10px';
    notification.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
    notification.style.transition = 'all 0.3s ease';
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(50px)';
    
    // Add to container
    notifContainer.appendChild(notification);
    
    // Trigger animation
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(50px)';
        
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

/**
 * Initialize product thumbnail gallery
 */
function initProductGallery() {
    const thumbnails = document.querySelectorAll('.thumbnail');
    const mainImage = document.querySelector('.main-image');
    
    if (thumbnails.length > 0 && mainImage) {
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                // Update main image source
                mainImage.src = thumbnail.src;
                
                // Update active thumbnail
                thumbnails.forEach(thumb => thumb.classList.remove('active'));
                thumbnail.classList.add('active');
            });
        });
    }
}

/**
 * Initialize tabs on product page
 */
function initTabs() {
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');
    
    if (tabs.length > 0 && tabContents.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Get the tab id
                const tabId = this.dataset.tab;
                
                // Remove active class from all tabs and contents
                tabs.forEach(t => t.classList.remove('active'));
                tabContents.forEach(c => c.classList.remove('active'));
                
                // Add active class to current tab and content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });
    }
}

/**
 * Initialize quantity controls
 */
function initQuantityControls() {
    const decreaseButtons = document.querySelectorAll('.quantity-decrease');
    const increaseButtons = document.querySelectorAll('.quantity-increase');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    
    if (decreaseButtons.length > 0 && increaseButtons.length > 0) {
        decreaseButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const input = quantityInputs[index];
                let value = parseInt(input.value);
                
                if (value > 1) {
                    value--;
                    input.value = value;
                    
                    // If in cart page, trigger change event to update totals
                    if (this.closest('.cart-table')) {
                        const event = new Event('change');
                        input.dispatchEvent(event);
                    }
                }
            });
        });
        
        increaseButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                const input = quantityInputs[index];
                let value = parseInt(input.value);
                
                value++;
                input.value = value;
                
                // If in cart page, trigger change event to update totals
                if (this.closest('.cart-table')) {
                    const event = new Event('change');
                    input.dispatchEvent(event);
                }
            });
        });
    }
}

/**
 * Initialize print invoice functionality
 */
function initPrintInvoice() {
    const printButton = document.querySelector('.print-button');
    
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
}

/**
 * Sync cart between PHP session and localStorage
 */
function syncCart() {
    // This function would be called from PHP via inline script
    // to synchronize the cart data between localStorage and PHP session
    const cartData = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Create a form with the cart data and submit it to a PHP script
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'index.php?page=sync-cart';
    form.style.display = 'none';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'cart_data';
    input.value = JSON.stringify(cartData);
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}