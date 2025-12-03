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
    <?php include_once "template/navbar.php" ?>
</header>

<div class="container mt-5">
    <h1>Bienvenue <?= htmlspecialchars($_SESSION['user']['firstname'] ?? 'Utilisateur') ?></h1>

    <h2>Mes informations</h2>
    <p>Nom : <?= htmlspecialchars($_SESSION['user']['username'] ?? '-') ?></p>
    <p>Prénom : <?= htmlspecialchars($_SESSION['user']['firstname'] ?? '-') ?></p>
    <p>Email : <?= htmlspecialchars($_SESSION['user']['email'] ?? '-') ?></p>
    <p>Total dépensé : <?= number_format($_SESSION['user']['total_spent'] ?? 0, 2) ?> €</p>
    <p>Nombre de commandes : <?= $_SESSION['user']['orders_count'] ?? 0 ?></p>

    <h2>Mes commandes</h2>
    <?php if (!empty($userOrders)): ?>
        <ul class="list-group mb-4">
            <?php foreach ($userOrders as $order): ?>
                <li class="list-group-item">
                    <a href="index.php?url=06_profil&id=<?= $order['order_id'] ?>">
                        <?= htmlspecialchars($order['order_number']) ?> -
                        <?= htmlspecialchars($order['order_date']) ?> -
                        <?= number_format($order['order_total_price'], 2) ?> € -
                        <?= htmlspecialchars($order['order_status']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune commande pour le moment.</p>
    <?php endif; ?>

    <h2>Détails de la commande</h2>
    <?php if (!empty($orderDetails['items'])): ?>
        <table class="table">
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
        <h3>Total de la commande : <?= number_format($total, 2) ?> €</h3>
    <?php else: ?>
        <p>Aucune commande sélectionnée. Cliquez sur une commande pour voir les détails.</p>
    <?php endif; ?>
</div>

<footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
    <?php include_once "template/footer.php" ?>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>