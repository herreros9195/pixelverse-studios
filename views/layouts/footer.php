</main>
<footer class="bg-dark text-light py-4 mt-auto">
    <div class="container text-center">
        <p class="mb-2">&copy; <?= date('Y') ?> PixelVerse Studios - FantasyRealm Online</p>
        <p class="mb-0">
            <a href="/index.php?action=legal" class="text-light text-decoration-underline">Mentions légales</a> |
            <a href="/index.php?action=cgv" class="text-light text-decoration-underline">Conditions générales de vente</a> |
            <a href="#" class="text-light text-decoration-underline" onclick="alert('Contactez-nous à contact@pixelverse.com pour exercer vos droits RGPD (accès, rectification, suppression).')">RGPD</a>
        </p>
    </div>
</footer>

<?php if (empty($_COOKIE['rgpd_accept'])): ?>
<div id="rgpd-banner" class="fixed-bottom bg-dark text-white p-3 border-top" style="z-index: 9999;">
    <div class="container d-flex justify-content-between align-items-center">
        <small>Ce site utilise des cookies pour améliorer votre expérience. En continuant, vous acceptez notre politique de confidentialité conforme au RGPD.</small>
        <button class="btn btn-sm btn-primary ms-3" onclick="document.cookie='rgpd_accept=1;path=/;max-age=31536000';document.getElementById('rgpd-banner').remove();">J'accepte</button>
    </div>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
