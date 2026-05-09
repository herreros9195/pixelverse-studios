<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Espace Administrateur</h1>

<div class="row mt-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Utilisateurs</h5>
                <p class="card-text display-6"><?= count($users) ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <a href="/index.php?action=admin-create-employee" class="btn btn-success w-100 mb-3">+ Créer un compte employé</a>
        <a href="/index.php?action=admin-logs" class="btn btn-info w-100 text-white">Voir les logs</a>
    </div>
</div>

<h3 class="mt-4">Gestion des comptes</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Pseudo</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><?= htmlspecialchars($u['pseudo']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= ucfirst($u['role']) ?></td>
                <td><span class="badge bg-<?= $u['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($u['status']) ?></span></td>
                <td>
                    <?php if ($u['role'] === 'employee'): ?>
                        <a href="/index.php?action=admin-manage-employee&id=<?= $u['id'] ?>&type=password" class="btn btn-sm btn-outline-primary" onclick="return confirm('Générer un nouveau mot de passe ?')">MDP</a>
                        <?php if ($u['status'] === 'active'): ?>
                            <a href="/index.php?action=admin-manage-employee&id=<?= $u['id'] ?>&type=suspend" class="btn btn-sm btn-outline-warning">Suspendre</a>
                        <?php else: ?>
                            <a href="/index.php?action=admin-manage-employee&id=<?= $u['id'] ?>&type=activate" class="btn btn-sm btn-outline-success">Activer</a>
                        <?php endif; ?>
                        <a href="/index.php?action=admin-manage-employee&id=<?= $u['id'] ?>&type=delete" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer définitivement ?')">Supprimer</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
