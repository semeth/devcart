/**
 * Custom JavaScript for DevCart
 */

// Update cart count
(function() {
    'use strict';
    
    function updateCartCount() {
        const cartCountElement = document.getElementById('cart-count');
        if (!cartCountElement) {
            return;
        }

        // Use relative path for cart count
        fetch('/cart/count')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                cartCountElement.textContent = data.count || 0;
            })
            .catch(() => {
                cartCountElement.textContent = '0';
            });
    }

    // Update cart count when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateCartCount);
    } else {
        updateCartCount();
    }
})();

// Toggle shipping address fields in checkout
function toggleShipping(checkbox) {
    const shippingFields = document.getElementById('shipping-fields');
    if (shippingFields) {
        shippingFields.style.display = checkbox.checked ? 'none' : 'block';
    }
}
