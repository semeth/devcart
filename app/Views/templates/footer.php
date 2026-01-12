        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> DevCart. All rights reserved.</p>
        </div>
    </footer>

    <script>
        // Update cart count
        fetch('/cart/count')
            .then(response => response.json())
            .then(data => {
                document.getElementById('cart-count').textContent = data.count || 0;
            })
            .catch(() => {
                document.getElementById('cart-count').textContent = '0';
            });
    </script>
</body>
</html>
