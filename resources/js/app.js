import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global Toast & Interactive Cart/Wishlist Helper
window.savageNotify = function (message, type = 'success') {
    window.dispatchEvent(new CustomEvent('savage-toast', {
        detail: { message, type }
    }));
};

window.savageQuickAdd = async function (productId, buttonEl = null, size = null, color = null, quantity = 1) {
    if (buttonEl) {
        buttonEl.disabled = true;
        buttonEl.dataset.originalText = buttonEl.innerHTML;
        buttonEl.innerHTML = '<span class="inline-block animate-pulse tracking-widest uppercase text-xs">Adding...</span>';
    }

    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity,
                size: size,
                color: color,
            }),
        });

        const data = await response.json();
        if (response.ok && data.success) {
            document.querySelectorAll('[data-cart-count]').forEach(el => {
                el.textContent = data.cart_count;
            });
            window.savageNotify(data.message, 'success');
        } else {
            window.savageNotify(data.message || 'Unable to add item to bag.', 'error');
        }
    } catch (e) {
        window.savageNotify('Network error while updating bag.', 'error');
    } finally {
        if (buttonEl) {
            buttonEl.disabled = false;
            buttonEl.innerHTML = buttonEl.dataset.originalText;
        }
    }
};

window.savageToggleWishlist = async function (productId, buttonEl = null) {
    try {
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const response = await fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
        });

        if (response.status === 401) {
            window.location.href = '/login';
            return;
        }

        const data = await response.json();
        if (response.ok && data.success) {
            document.querySelectorAll('[data-wishlist-count]').forEach(el => {
                el.textContent = data.wishlist_count;
            });
            if (buttonEl) {
                if (data.in_wishlist) {
                    buttonEl.classList.add('text-[#B85435]', 'bg-[#0E0E0E]');
                } else {
                    buttonEl.classList.remove('text-[#B85435]', 'bg-[#0E0E0E]');
                }
            }
            window.savageNotify(data.message, 'success');
        }
    } catch (e) {
        window.location.href = '/login';
    }
};

Alpine.start();
