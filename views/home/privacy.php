<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="page-intro">
    <div>
        <p class="eyebrow">Confidentialite</p>
        <h1 class="page-title">Politique de confidentialite</h1>
        <p class="page-copy">Resume des donnees traitees, des finalites associees et des moyens de suppression ou de rectification.</p>
    </div>
</section>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 mb-3">Donnees collecte es</h2>
                <p>Email, pseudo, mots de passe haches, personnages crees, avis, demandes de contact et journaux d'activite.</p>

                <h2 class="h5 mt-4 mb-3">Finalites</h2>
                <p>Authentification, moderation, administration, restitution des personnages, suivi des demandes de contact et securite applicative.</p>

                <h2 class="h5 mt-4 mb-3">Conservation</h2>
                <p>Les donnees restent stockees dans la base MySQL locale ou de production tant que le compte et les contenus associes sont actifs.</p>

                <h2 class="h5 mt-4 mb-3">Droits</h2>
                <p>Acces, rectification, suppression et limitation peuvent etre demandes via le formulaire de contact de la plateforme.</p>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <p class="eyebrow">Cookies</p>
                <h2 class="h5 mb-3">Usage</h2>
                <p class="text-muted mb-0">Le projet utilise un cookie de session PHP et un cookie de confort pour memoriser l'acceptation du bandeau RGPD.</p>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
