<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test3 - Cacheable Page</title>
</head>
<body>
    <h1>Cacheable Test Page</h1>
    <p>Current time (server): {{ now()->format('Y-m-d H:i:s') }}</p>
    
    <!-- User-specific data loaded via JS -->
    <div id="promo-banner">Loading promotions...</div>
    <div>Cart: <span id="cart-count">0</span> items</div>
    
    <script>
    // This will load AFTER the cached HTML
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Page loaded from cache at:', new Date().toISOString());
        
        // Simulate loading user data (we'll make this real later)
        setTimeout(() => {
            document.getElementById('promo-banner').textContent = '✓ User data loaded!';
            document.getElementById('cart-count').textContent = '3';
        }, 100);
    });
    </script>
</body>
</html>