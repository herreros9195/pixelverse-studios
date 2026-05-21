</main>

<footer style="background: #111827; border-top: 1px solid rgba(212,175,55,0.1); margin-top: 4rem;">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: #D4AF37;"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"></polyline><line x1="13" y1="19" x2="19" y2="13"></line><line x1="16" y1="16" x2="20" y2="20"></line><line x1="19" y1="21" x2="21" y2="19"></line></svg>
                    <span style="font-family: 'Cinzel', serif; font-size: 1.125rem; font-weight: 700; color: #D4AF37;">FantasyRealm</span>
                </div>
                <p style="color: #A39B8B; font-size: 0.875rem;">
                    Plateforme de creation, de moderation et de partage de personnages
                    3D pour FantasyRealm Online.
                </p>
            </div>

            <div class="col-md-4">
                <h3 style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #F5F0E6; margin-bottom: 1rem;">Navigation</h3>
                <ul class="list-unstyled" style="margin: 0; padding: 0;">
                    <li class="mb-2"><a href="/index.php?action=home" style="color: #A39B8B; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#A39B8B'">Accueil</a></li>
                    <li class="mb-2"><a href="/index.php?action=characters" style="color: #A39B8B; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#A39B8B'">Personnages</a></li>
                    <li class="mb-2"><a href="/index.php?action=contact" style="color: #A39B8B; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#A39B8B'">Contact</a></li>
                </ul>
            </div>

            <div class="col-md-4">
                <h3 style="font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #F5F0E6; margin-bottom: 1rem;">Cadre legal</h3>
                <ul class="list-unstyled" style="margin: 0; padding: 0;">
                    <li class="mb-2"><a href="/index.php?action=legal" style="color: #A39B8B; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#A39B8B'">Mentions legales</a></li>
                    <li class="mb-2"><a href="/index.php?action=cgv" style="color: #A39B8B; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#A39B8B'">Conditions generales</a></li>
                    <li class="mb-2"><a href="/index.php?action=confidentialite" style="color: #A39B8B; font-size: 0.875rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#A39B8B'">Politique de confidentialite</a></li>
                </ul>
            </div>
        </div>

        <div style="border-top: 1px solid rgba(255,255,255,0.1); margin-top: 2rem; padding-top: 2rem; text-align: center;">
            <p style="color: #A39B8B; font-size: 0.75rem; margin: 0;">
                Copyright 2026 PixelVerse Studios. Projet d'evaluation pour la gestion de personnages FantasyRealm.
            </p>
        </div>
    </div>
</footer>

<?php if (empty($_COOKIE['rgpd_accept'])): ?>
    <div id="rgpd-banner" class="cookie-banner">
        <div class="container d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            <small>Cookies de session et de confort utilises pour la navigation, l'authentification et la conservation des preferences d'interface.</small>
            <button class="btn btn-sm btn-primary" type="button" onclick="document.cookie='rgpd_accept=1;path=/;max-age=31536000';document.getElementById('rgpd-banner').remove();">Accepter</button>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
