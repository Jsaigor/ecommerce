<?php
function footer() { ?>
<footer class="footer" id="footer">
    <div class="footer-container">
    <div class="footer-section footer-contacto" style="text-align: right; max-width: 300px; float: right;">
    <form action="suscribirse.php" method="POST">
        <label for="email">Recibí ofertas y promociones</label><br>
        <input type="email" name="correo" placeholder="Ingresá tu email" required class="form-control mb-2">
        <button type="submit" class="btn btn-sm" style="background-color: #4B0082; color: white; font-weight: bold;">
            SUSCRIBIRME
        </button>
    </form>
    </div>
    <div class="footer-section  footer-contacto">
    <h4>Contacto</h4>
    <address class="col-md-8">
        Email: <a href="mailto:ElectroSTORE@gmail.com">Contactanos</a><br>
        <h3>0810-123-4567890</h3>
    </address>
    </div>

    <div class="footer-section  footer-redes">
    <h4>Seguinos</h4>
    <div class="iconos-redes">
        <a href="https://www.facebook.com/" target="_blank"><i class="fi fi-brands-facebook"></i></a>
        <a href="https://www.instagram.com/" target="_blank"><i class="fi fi-brands-instagram"></i></a>
        <a href="https://www.youtube.com/" target="_blank"><i class="fi fi-brands-youtube"></i></a>
        <a href="https://www.whatsapp.com/" target="_blank"><i class="fi fi-brands-whatsapp"></i></a>
    </div>
    </div>

    <div class="footer-section footer-copy">
    <p>&copy; 2025 EcommerceaR. Todos los derechos reservados.</p>
    <a href="#top" class="volver-arriba">Volver arriba</a>
    </div>

    </div>
</footer>
<?php } ?> 