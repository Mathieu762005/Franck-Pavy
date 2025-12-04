<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click & Collect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php"; ?>

        <!-- Offcanvas du panier -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Panier de commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <h2>Mon Panier</h2>

                <?php if (!empty($cartItems)): ?>
                    <form method="POST" action="?url=cart_update_all">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Prix Unitaire</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $total = 0; ?>
                                <?php foreach ($cartItems as $item):
                                    $total += $item['cart_items_total_price'];
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td>
                                            <input type="number" name="quantities[<?= $item['cart_item_id'] ?>]"
                                                class="cart-quantity" data-price="<?= $item['cart_items_unit_price'] ?>"
                                                value="<?= $item['cart_items_quantity'] ?>" min="1" style="width:50px;">
                                            <input type="hidden" name="unit_prices[<?= $item['cart_item_id'] ?>]"
                                                value="<?= $item['cart_items_unit_price'] ?>">
                                        </td>
                                        <td><?= number_format($item['cart_items_unit_price'], 2) ?> €</td>
                                        <td class="cart-total"><?= number_format($item['cart_items_total_price'], 2) ?> €</td>
                                        <td>
                                            <form method="POST" action="?url=cart_remove" style="display:inline;">
                                                <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove"
                                                    data-id="<?= $item['cart_item_id'] ?>">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <h3>Total : <span id="cart-grand-total"><?= number_format($total, 2) ?> €</span></h3>
                        <button type="submit" class="btn btn-primary">Modifier le panier</button>
                        <a href="?url=checkout" class="btn btn-success">Passer à la commande</a>
                    </form>
                <?php else: ?>
                    <p>Votre panier est vide.</p>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container mt-4">
        <h1>Click & Collect</h1>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <section class="my-5">
                    <div class="category-banner mb-3">
                        <?php if (!empty($category['image'])): ?>
                            <img src="<?= htmlspecialchars($category['image']) ?>"
                                alt="<?= htmlspecialchars($category['category_name']) ?>" class="img-fluid">
                        <?php endif; ?>
                        <h2 class="mt-2"><?= htmlspecialchars($category['category_name']) ?></h2>
                    </div>

                    <?php if (!empty($category['products'])): ?>
                        <div class="row">
                            <?php foreach ($category['products'] as $product): ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <?php if (!empty($product['product_image'])): ?>
                                            <img src="/assets/image/<?= htmlspecialchars($product['product_image']) ?>" class="card-img-top"
                                                alt="<?= htmlspecialchars($product['product_name']) ?>"
                                                style="height:200px; object-fit:cover;">
                                        <?php endif; ?>
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($product['product_description']) ?></p>
                                            <p class="card-text"><strong><?= number_format($product['product_price'], 2) ?> €</strong>
                                            </p>

                                            <?php if (isset($_SESSION['user']['id'])): ?>
                                                <form method="POST" action="?url=cart_add" class="add-to-cart-form mt-auto">
                                                    <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="btn btn-primary w-100 mt-2">Ajouter au panier</button>
                                                </form>
                                            <?php else: ?>
                                                <p class="text-danger">Connectez-vous pour ajouter au panier</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Aucun produit disponible pour cette catégorie.</p>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune catégorie disponible.</p>
        <?php endif; ?>
    </main>

    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.cart-quantity').forEach(input => {
            input.addEventListener('input', function () {
                const quantity = parseInt(this.value) || 0;
                const price = parseFloat(this.dataset.price);
                const rowTotalCell = this.closest('tr').querySelector('.cart-total');
                const rowTotal = quantity * price;
                rowTotalCell.textContent = rowTotal.toFixed(2) + ' €';

                // Recalcul total global
                let totalGlobal = 0;
                document.querySelectorAll('.cart-total').forEach(cell => {
                    totalGlobal += parseFloat(cell.textContent.replace(' €', ''));
                });
                document.querySelector('#cart-grand-total').textContent = totalGlobal.toFixed(2) + ' €';
            });
        });
        document.querySelectorAll('.btn-remove').forEach(btn => {
            btn.addEventListener('click', function () {
                const cartItemId = this.dataset.id;

                fetch('?url=cart_remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'cart_item_id=' + cartItemId
                })
                    .then(response => response.text())
                    .then(() => {
                        // Supprimer la ligne du tableau côté front
                        this.closest('tr').remove();

                        // Recalculer le total
                        let totalGlobal = 0;
                        document.querySelectorAll('.cart-total').forEach(cell => {
                            totalGlobal += parseFloat(cell.textContent.replace(' €', ''));
                        });
                        document.querySelector('#cart-grand-total').textContent = totalGlobal.toFixed(2) + ' €';
                    });
            });
        });
    </script>
</body>

</html>