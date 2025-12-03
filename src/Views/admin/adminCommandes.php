<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include_once __DIR__ . '/../template/navbar.php'; ?>
    </header>

    <main class="container mt-4">
        <h1>Gestion des commandes</h1>

        <h1>Commandes</h1>

        <?php if (!empty($commandes)): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Prix Total</th>
                        <th>Heure Retrait</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= $commande['order_id'] ?></td>
                            <td><?= $commande['order_number'] ?></td>
                            <td><?= $commande['order_date'] ?></td>
                            <td><?= number_format($commande['order_total_price'], 2) ?> €</td>
                            <td><?= $commande['order_pickup_time'] ?></td>
                            <td><?= $commande['order_status'] ?></td>
                            <td>
                                <form method="POST" action="?url=adminCommandes">
                                    <input type="hidden" name="order_id" value="<?= $commande['order_id'] ?>">
                                    <select name="status">
                                        <?php
                                        $statuses = ['brouillon', 'confirmée', 'en préparation', 'prête', 'terminée', 'annulée'];
                                        foreach ($statuses as $statusOption):
                                            ?>
                                            <option value="<?= $statusOption ?>" <?= $commande['order_status'] === $statusOption ? 'selected' : '' ?>>
                                                <?= $statusOption ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande.</p>
        <?php endif; ?>
    </main>

    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
        <?php include_once __DIR__ . '/../template/footer.php'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>