<!DOCTYPE html>
<head><x-store-head title="Test3 - Real Components" /></head>
<body>
    <x-store-header-static />
    <main>Test conten111t</main>
    <x-store-footer-static />
    
    <script>
    fetch('/api/store-data').then(r=>r.json()).then(data => {
        // Update categories, cart, user, timer from data
    });
    </script>
</body>
</html>
