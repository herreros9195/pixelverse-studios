<?php require __DIR__ . '/../layouts/header.php'; ?>

<section class="page-intro">
    <div>
        <p class="eyebrow">Administration</p>
        <h1 class="page-title">Espace administrateur</h1>
        <p class="page-copy">Supervisez les comptes, les employes et les operations sensibles de la plateforme.</p>
    </div>
</section>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <p class="eyebrow mb-2">Vue globale</p>
                <h2 class="h5">Utilisateurs en base</h2>
                <p class="display-5 mb-0"><?= count($users) ?></p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm h-100">
            <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div>
                    <h2 class="h5 mb-1">Actions rapides</h2>
                    <p class="text-muted mb-0">Creez un compte employe ou consultez les journaux d'activite.</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="/index.php?action=admin-create-employee" class="btn btn-success">Creer un compte employe</a>
                    <a href="/index.php?action=admin-logs" class="btn btn-outline-primary">Voir les logs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="h5 mb-3">Gestion des comptes</h2>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?= (int) $u['id'] ?></td>
                            <td><?= htmlspecialchars($u['pseudo']) ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><?= htmlspecialchars(ucfirst($u['role'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>">
                                    <?= htmlspecialchars(ucfirst($u['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($u['role'] === 'employee'): ?>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="/index.php?action=admin-manage-employee&id=<?= (int) $u['id'] ?>&type=password&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-primary" onclick="return confirm('Generer un nouveau mot de passe ?')">Mot de passe</a>
                                        <?php if ($u['status'] === 'active'): ?>
                                            <a href="/index.php?action=admin-manage-employee&id=<?= (int) $u['id'] ?>&type=suspend&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-warning">Suspendre</a>
                                        <?php else: ?>
                                            <a href="/index.php?action=admin-manage-employee&id=<?= (int) $u['id'] ?>&type=activate&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-success">Activer</a>
                                        <?php endif; ?>
                                        <a href="/index.php?action=admin-manage-employee&id=<?= (int) $u['id'] ?>&type=delete&csrf=<?= urlencode(csrfToken()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer definitivement ce compte ?')">Supprimer</a>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">Aucune action admin directe</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
