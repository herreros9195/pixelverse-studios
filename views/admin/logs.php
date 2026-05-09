<?php require __DIR__ . '/../layouts/header.php'; ?>

<h1>Logs d'activité</h1>
<p class="lead">Suivi des modifications apportées aux personnages et aux comptes.</p>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Date</th>
                <th>Action</th>
                <th>Utilisateur</th>
                <th>Détails</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logs)): ?>
                <tr><td colspan="5" class="text-center text-muted">Aucun log disponible.</td></tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log['created_at']->toDateTime()->format('d/m/Y H:i:s') ?></td>
                        <td><?= htmlspecialchars($log['action']) ?></td>
                        <td><?= $log['user_id'] ?? 'Anonyme' ?></td>
                        <td><pre class="mb-0" style="white-space: pre-wrap;"><?= json_encode($log['details'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) ?></pre></td>
                        <td><?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
