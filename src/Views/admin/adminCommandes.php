<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <header>
        <?php include_once __DIR__ . '/../template/navbar.php'; ?>
    </header>
    <h1>Gestion des commandes</h1>

    <?php
    // $details = ['order' => ..., 'items' => ...]
    $order = $details['order'];
    $items = $details['items'];
    ?>

    <h1>Détails de la commande #<?= $order['order_number'] ?></h1>
    <p>Statut : <?= $order['order_status'] ?></p>
    <p>Retrait prévu à : <?= $order['order_pickup_time'] ?></p>
    <p>Date : <?= $order['order_date'] ?></p>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($item['unit_price'], 2) ?> €</td>
                    <td><?= number_format($item['total_price'], 2) ?> €</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total commande : <?= number_format($order['order_total_price'], 2) ?> €</h3>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>