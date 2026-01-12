        </div>
    </main>

    <footer class="bg-dark text-light mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> DevCart. All rights reserved.</p>
        </div>
    </footer>


    <script src="<?= base_url('assets/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        // Update cart count
        fetch('<?= site_url('cart/count') ?>')
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
