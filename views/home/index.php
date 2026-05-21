<?php
$fluidMain = true;
require __DIR__ . '/../layouts/header.php';
?>

<section class="position-relative overflow-hidden" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; background: #0B0E17;">
    <div class="position-absolute top-0 start-0 w-100 h-100">
        <img src="<?= ASSETS_URL ?>images/hero-bg.jpg" alt="" class="w-100 h-100" style="object-fit: cover; object-position: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(11, 14, 23, 0.6);"></div>
    </div>

    <div class="position-relative text-center px-4 mx-auto" style="max-width: 56rem; z-index: 10;">
        <h1 class="mb-4 fw-bold" style="font-family: 'Cinzel', serif; font-size: clamp(2.25rem, 5vw, 4rem); line-height: 1.1; color: #D4AF37; letter-spacing: 0.05em;">
            GESTION DE PERSONNAGES 3D
        </h1>
        <p class="mb-5 mx-auto" style="max-width: 42rem; font-size: 1.125rem; color: #F5F0E6;">
            Plateforme MVC PHP/MySQL dediee a la creation, a la moderation
            et au partage de personnages Synty pour FantasyRealm Online.
        </p>
        <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
            <a href="/index.php?action=character-create" class="btn btn-lg px-4 py-3 fw-bold d-inline-flex align-items-center justify-content-center gap-2" style="background: #D4AF37; color: #0B0E17; font-size: 1.125rem; border: none; border-radius: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"></polyline><line x1="13" y1="19" x2="19" y2="13"></line><line x1="16" y1="16" x2="20" y2="20"></line><line x1="19" y1="21" x2="21" y2="19"></line></svg>
                Creer un personnage
            </a>
            <a href="/index.php?action=characters" class="btn btn-lg px-4 py-3 fw-bold d-inline-flex align-items-center justify-content-center gap-2" style="border: 1px solid #D4AF37; color: #D4AF37; background: transparent; font-size: 1.125rem; border-radius: 0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Explorer la galerie
            </a>
        </div>
    </div>
</section>

<section style="background: #0B0E17; padding: 5rem 0;">
    <div class="container" style="max-width: 80rem;">
        <div class="text-center mb-5">
            <h2 style="font-family: 'Cinzel', serif; font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 700; color: #D4AF37; margin-bottom: 1rem;">
                FONCTIONS PRINCIPALES
            </h2>
            <p class="mx-auto" style="max-width: 42rem; font-size: 1.125rem; color: #A39B8B;">
                Parcours complet de la creation a la publication, avec moderation
                employee, administration et rendu 3D temps reel.
            </p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 text-center p-4" style="background: #111827; border: 1px solid rgba(212,175,55,0.1); border-radius: 0.75rem;">
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 3.5rem; height: 3.5rem; background: rgba(212,175,55,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="14.5 17.5 3 6 3 3 6 3 17.5 14.5"></polyline><line x1="13" y1="19" x2="19" y2="13"></line><line x1="16" y1="16" x2="20" y2="20"></line><line x1="19" y1="21" x2="21" y2="19"></line></svg>
                    </div>
                    <h3 style="font-family: 'Cinzel', serif; font-size: 1.25rem; font-weight: 700; color: #F5F0E6; margin-bottom: 0.75rem;">Createur Synty modulaire</h3>
                    <p style="color: #A39B8B; line-height: 1.6; margin: 0;">
                        Corps, visage, cheveux, accessoires et classes relies aux assets reels exportes depuis le pack Synty gratuit.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4" style="background: #111827; border: 1px solid rgba(212,175,55,0.1); border-radius: 0.75rem;">
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 3.5rem; height: 3.5rem; background: rgba(212,175,55,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"></path></svg>
                    </div>
                    <h3 style="font-family: 'Cinzel', serif; font-size: 1.25rem; font-weight: 700; color: #F5F0E6; margin-bottom: 0.75rem;">Moderation et suivi</h3>
                    <p style="color: #A39B8B; line-height: 1.6; margin: 0;">
                        Validation employee des personnages et avis, journalisation des actions et partage public reserve aux contenus approuves.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 text-center p-4" style="background: #111827; border: 1px solid rgba(212,175,55,0.1); border-radius: 0.75rem;">
                    <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle" style="width: 3.5rem; height: 3.5rem; background: rgba(212,175,55,0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"></path></svg>
                    </div>
                    <h3 style="font-family: 'Cinzel', serif; font-size: 1.25rem; font-weight: 700; color: #F5F0E6; margin-bottom: 0.75rem;">Documentation et livrables</h3>
                    <p style="color: #A39B8B; line-height: 1.6; margin: 0;">
                        README, documentation technique, manuel d'utilisation, charte graphique, gestion de projet et export Trello synchronises.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="background: #111827; padding: 5rem 0;">
    <div class="container" style="max-width: 80rem;">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="<?= ASSETS_URL ?>images/character-warrior.jpg" alt="Guerrier" class="w-100 rounded-4" style="aspect-ratio: 2/3; object-fit: cover; border: 2px solid rgba(212,175,55,0.2); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                    </div>
                    <div class="col-6 d-flex flex-column gap-3">
                        <img src="<?= ASSETS_URL ?>images/character-mage.jpg" alt="Mage" class="w-100 rounded-4 flex-grow-1" style="object-fit: cover; border: 2px solid rgba(212,175,55,0.2); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                        <img src="<?= ASSETS_URL ?>images/character-rogue.jpg" alt="Ronin" class="w-100 rounded-4 flex-grow-1" style="object-fit: cover; border: 2px solid rgba(212,175,55,0.2); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <span style="font-size: 0.75rem; font-weight: 500; text-transform: uppercase; letter-spacing: 0.2em; color: #D4AF37; display: block; margin-bottom: 0.5rem;">Structure</span>
                <h2 style="font-family: 'Cinzel', serif; font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 700; color: #F5F0E6; margin-bottom: 1.5rem;">Architecture du projet</h2>
                <p style="color: #A39B8B; line-height: 1.7; margin-bottom: 1.5rem;">
                    Front controller PHP, separation MVC, base MySQL, sessions, controle CSRF, helper mail, moderation multi-role et rendu Three.js pour l'assemblage 3D.
                </p>
                <p style="color: #A39B8B; line-height: 1.7; margin-bottom: 2rem;">
                    Le projet couvre la creation de comptes, la gestion des personnages, la galerie publique, les avis, le contact, la moderation employee et les outils d'administration.
                </p>
                <div class="row g-4 text-center">
                    <div class="col-4">
                        <div style="font-family: monospace; font-size: 1.5rem; font-weight: 700; color: #D4AF37;">4</div>
                        <div style="color: #A39B8B; font-size: 0.875rem;">Etapes createur</div>
                    </div>
                    <div class="col-4">
                        <div style="font-family: monospace; font-size: 1.5rem; font-weight: 700; color: #D4AF37;">3</div>
                        <div style="color: #A39B8B; font-size: 0.875rem;">Roles metier</div>
                    </div>
                    <div class="col-4">
                        <div style="font-family: monospace; font-size: 1.5rem; font-weight: 700; color: #D4AF37;">100%</div>
                        <div style="color: #A39B8B; font-size: 0.875rem;">Flux Synty</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section style="background: linear-gradient(180deg, #0B0E17 0%, #1F2937 100%); padding: 5rem 0;">
    <div class="container text-center" style="max-width: 48rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mb-4"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        <h2 style="font-family: 'Cinzel', serif; font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 700; color: #F5F0E6; margin-bottom: 1.5rem;">
            ACCES RAPIDE AU CREATEUR
        </h2>
        <p class="mx-auto mb-5" style="max-width: 36rem; color: #A39B8B;">
            Entree directe vers le createur Synty modulaire, la galerie partagee et le parcours complet de moderation.
        </p>
        <a href="/index.php?action=character-create" class="btn btn-lg px-5 py-3 fw-bold" style="background: #D4AF37; color: #0B0E17; font-size: 1.125rem; border: none; border-radius: 0.5rem;">
            Ouvrir le createur
        </a>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
