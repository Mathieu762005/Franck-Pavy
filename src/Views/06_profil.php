<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php" ?>
    </header>
    <h1>Profil</h1>
    <?php
    var_dump($_SESSION["user"])
        ?>
    <div class="container mt-5">
        <h1>Détails de la commande #<?= htmlspecialchars($orderId) ?></h1>

        <?php if (!empty($details['items'])): ?>
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
                    <?php foreach ($details['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= $item['cart_items_quantity'] ?></td>
                            <td><?= number_format($item['cart_items_unit_price'], 2) ?> €</td>
                            <td><?= number_format($item['cart_items_total_price'], 2) ?> €</td>
                        </tr>
                        <?php $total += $item['cart_items_total_price']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total de la commande : <?= number_format($total, 2) ?> €</h3>
        <?php else: ?>
            <p>Aucun produit dans cette commande.</p>
        <?php endif; ?>
    </div>
    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
        <?php include_once "template/footer.php" ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>