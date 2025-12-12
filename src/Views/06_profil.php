<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php"; ?>
    </header>

    <div class="container mt-5">

        <h1>Bienvenue <?= htmlspecialchars($user['user_first_name'] ?? 'Utilisateur') ?></h1>

        <!-- INFOS USER -->
        <h2>Mes informations</h2>
        <p>Nom : <?= htmlspecialchars($user['user_name'] ?? '-') ?></p>
        <p>Prénom : <?= htmlspecialchars($user['user_first_name'] ?? '-') ?></p>
        <p>Email : <?= htmlspecialchars($user['user_email'] ?? '-') ?></p>
        <p>Total dépensé : <?= number_format($user['user_total_spent'] ?? 0, 2) ?> €</p>
        <p>Nombre de commandes : <?= $user['user_orders_count'] ?? 0 ?></p>


        <!-- LISTE DES COMMANDES -->
        <h2 class="mt-4">Mes commandes</h2>

        <?php if (!empty($userOrders)): ?>
            <ul class="list-group mb-4">
                <?php foreach ($userOrders as $order): ?>
                    <li class="list-group-item">
                        <a href="index.php?url=06_profil&order_id=<?= $order['order_id'] ?>">
                            N° <?= htmlspecialchars($order['order_number']) ?> —
                            <?= htmlspecialchars($order['order_date']) ?> —
                            <?= number_format($order['order_total_price'], 2) ?> € —
                            <?= htmlspecialchars($order['order_status']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucune commande pour le moment.</p>
        <?php endif; ?>

        <h2>Détails de la commande</h2>

        <?php if (!empty($orderDetails)): ?>

            <p><strong>Commande :</strong> <?= htmlspecialchars($orderDetails['order']['order_number']) ?></p>
            <p><strong>Date :</strong> <?= htmlspecialchars($orderDetails['order']['order_date']) ?></p>
            <p><strong>Statut :</strong> <?= htmlspecialchars($orderDetails['order']['order_status']) ?></p>

            <?php if (!empty($orderDetails['items'])): ?>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($orderDetails['items'] as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= number_format($item['unit_price'], 2) ?> €</td>
                                <td><?= number_format($item['total_price'], 2) ?> €</td>
                            </tr>
                            <?php $total += $item['total_price']; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <h3>Total : <?= number_format($total, 2) ?> €</h3>

            <?php else: ?>
                <p>Aucun article dans cette commande.</p>
            <?php endif; ?>

        <?php else: ?>
            <p>Aucune commande sélectionnée.</p>
        <?php endif; ?>


        <?php if (isset($_SESSION['user'])): ?>
            <a class="btn border border-black mt-4" href="index.php?url=logout">Déconnexion</a>
        <?php endif; ?>

    </div>

    <footer>
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>