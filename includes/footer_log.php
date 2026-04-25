    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-column branding">
                <div class="logo">
                    <span><img src="../assets/img/logo.png" alt="Bookly Logo"></span> 
                    Bookly
                </div>
                <p>Tu biblioteca personal digital. Organiza, lee y comparte tu pasión por los libros.</p>
                <div class="social-links">
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="#"><i class="fa-brands fa-facebook"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h4>Explorar</h4>
                <ul>
                    <li><a href="index.php">Inicio</a></li>
                    <li><a href="#">Catálogo Público</a></li>
                    <li><a href="#">Novedades</a></li>
                    <li><a href="#">Autores</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Comunidad</h4>
                <ul>
                    <li><a href="#">Aviso Legal</a></li>
                    <li><a href="#">Política de Privacidad</a></li>
                    <li><a href="#">Contacto</a></li>
                    <li><a href="#">Sobre el proyecto</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2026 <strong>Bookly</strong> | Diseñado para TFG por <span>Edelmira Cutillas Berná</span></p>
        </div>
    </footer>

    <div id="sidebarOverlay" class="sidebar-overlay"></div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    </script>
</body>
</html>