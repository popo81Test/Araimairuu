

document.addEventListener('DOMContentLoaded', function() {
    // Make sure element exists before adding listeners
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        // Also add change event to validate manually entered values
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value, 10);
            
            // If not a number or less than 1, reset to 1
            if (isNaN(value) || value < 1) {
                this.value = 1;
            }
            // If more than 10, limit to 10
            else if (value > 10) {
                this.value = 10;
            }
        });
    }
});

function decrementQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function incrementQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue < 10) {
        quantityInput.value = currentValue + 1;
    }
}

// Update form button styling when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Style quantity controls for better mobile experience
    const quantityBtns = document.querySelectorAll('.quantity-controls button');
    if (quantityBtns.length > 0) {
        quantityBtns.forEach(btn => {
            btn.style.display = 'flex';
            btn.style.alignItems = 'center';
            btn.style.justifyContent = 'center';
        });
    }
});