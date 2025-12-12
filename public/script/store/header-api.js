document.addEventListener('DOMContentLoaded', async () => {
    try {
        const res = await fetch('/api/header-data', { credentials: 'include' });

        if (!res.ok) {
            console.error('Header API HTTP error', res.status);
            return;
        }

        const contentType = res.headers.get('content-type') || '';
        if (!contentType.includes('application/json')) {
            const text = await res.text();
            console.error('Header API did not return JSON, got:', contentType);
            console.error('Body preview:', text.slice(0, 200));
            return;
        }

        const data = await res.json();
        // Desktop categories (top nav)
        const desktopUl = document.getElementById('header-categories');
        if (desktopUl && Array.isArray(data.categories)) {
            desktopUl.innerHTML = '';

            data.categories.forEach(category => {
                const li = document.createElement('li');

                if (category.has_children && category.children && category.children.length) {
                    // Dropdown category (with children)
                    li.innerHTML = `
                        <div class="dropdown">
                            <a class="dropdown__button"
                               href="/storeproducts/${category.seo_id || category.id}">
                                ${category.name}
                                <svg>
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </a>
                            <ul class="dropdown__list"></ul>
                        </div>
                    `;

                    const list = li.querySelector('.dropdown__list');

                    category.children.forEach(sub => {
                        const subLi = document.createElement('li');
                        subLi.className = 'dropdown__item';

                        subLi.innerHTML = `
                            <a class="dropdown__item--button"
                               href="/storeproducts/${sub.seo_id || sub.id}">
                                ${sub.name}
                                ${sub.has_children && sub.children && sub.children.length ? `
                                    <svg>
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                ` : ''}
                            </a>
                            ${sub.has_children && sub.children && sub.children.length ? `
                                <div class="dropdown__item--list"></div>
                            ` : ''}
                        `;

                        if (sub.has_children && sub.children && sub.children.length) {
                            const subList = subLi.querySelector('.dropdown__item--list');
                            sub.children.forEach(subsub => {
                                const a = document.createElement('a');
                                a.className = 'dropdown__item--link';
                                a.href = `/storeproducts/${subsub.seo_id || subsub.id}`;
                                a.innerHTML = subsub.name;
                                subList.appendChild(a);
                            });
                        }

                        list.appendChild(subLi);
                    });
                } else {
                    // Simple category
                    li.innerHTML = `
                        <a class="navbar__link"
                           href="/storeproducts/${category.seo_id || category.id}">
                            ${category.name}
                        </a>
                    `;
                }

                desktopUl.appendChild(li);
            });
        }

        // TODO: mobile categories, wishlistCount, cartCount if needed
        const wishEl = document.getElementById('wishlist-quantity');
        const cartEl = document.getElementById('cart-quantity');
        if (wishEl && typeof data.wishlistCount !== 'undefined') {
            wishEl.textContent = data.wishlistCount;
        }
        if (cartEl && typeof data.cartCount !== 'undefined') {
            cartEl.textContent = data.cartCount;
        }

        if (typeof window.initHeaderJs === 'function') {
            window.initHeaderJs();
        }

    } catch (e) {
        console.error('Failed to load header data', e);
    }

    
});
