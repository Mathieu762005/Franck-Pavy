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

        <?php if (!empty($commandes)): ?>
            <table class="table table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Numéro</th>
                        <th>Date</th>
                        <th>Prix total</th>
                        <th>Heure retrait</th>
                        <th>Utilisateur</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $statusOptions = ['brouillon','confirmée','en préparation','prête','terminée','annulée'];
                    foreach ($commandes as $commande): 
                    ?>
                        <tr>
                            <td><?= $commande['order_id'] ?></td>
                            <td><?= htmlspecialchars($commande['order_number']) ?></td>
                            <td><?= $commande['order_date'] ?></td>
                            <td><?= number_format($commande['order_total_price'], 2) ?> €</td>
                            <td><?= $commande['order_pickup_time'] ?></td>
                            <td><?= $commande['user_id'] ?></td>
                            <td>
                                <form method="POST" action="?url=adminUpdateStatus">
                                    <input type="hidden" name="order_id" value="<?= $commande['order_id'] ?>">
                                    <select name="order_status" class="form-select">
                                        <?php foreach ($statusOptions as $status): ?>
                                            <option value="<?= $status ?>" <?= $commande['order_status'] === $status ? 'selected' : '' ?>>
                                                <?= ucfirst($status) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                            </td>
                            <td>
                                    <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande trouvée.</p>
        <?php endif; ?>
    </main>

    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
        <?php include_once __DIR__ . '/../template/footer.php'; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>