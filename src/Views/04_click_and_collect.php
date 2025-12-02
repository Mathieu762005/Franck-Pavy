<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click And Collect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php" ?>

        <!-- Offcanvas du panier -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Panier de commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <?php
                $total = 0;
                ?>
                <h1>Mon Panier</h1>

                <?php if (!empty($cartItems)): ?>
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
                            <?php foreach ($cartItems as $item):
                                $total += $item['cart_items_total_price'];
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                                    <td>
                                        <form method="POST" action="cart_update">
                                            <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                            <input type="number" name="quantity" value="<?= $item['cart_items_quantity'] ?>"
                                                min="1">
                                            <input type="hidden" name="unit_price"
                                                value="<?= $item['cart_items_unit_price'] ?>">
                                            <button type="submit" class="btn btn-sm btn-primary">Modifier</button>
                                        </form>
                                    </td>
                                    <td><?= number_format($item['cart_items_unit_price'], 2) ?> €</td>
                                    <td><?= number_format($item['cart_items_total_price'], 2) ?> €</td>
                                    <td>
                                        <form method="POST" action="cart_remove">
                                            <input type="hidden" name="cart_item_id" value="<?= $item['cart_item_id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <h3>Total : <?= number_format($total, 2) ?> €</h3>
                    <a href="checkout" class="btn btn-success">Passer à la commande</a>

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
                        <img src="<?= htmlspecialchars($category['image']) ?>"
                            alt="<?= htmlspecialchars($category['category_name']) ?>" class="img-fluid">
                        <h2 class="mt-2"><?= htmlspecialchars($category['category_name']) ?></h2>
                    </div>

                    <?php if (!empty($category['products'])): ?>
                        <div class="row">
                            <?php foreach ($category['products'] as $product): ?>
                                <div class="col-md-3 mb-4">
                                    <div class="card h-100">
                                        <img src="/assets/image/<?= htmlspecialchars($product['product_image']) ?>" class="card-img-top"
                                            alt="<?= htmlspecialchars($product['product_name']) ?>"
                                            style="height:200px; object-fit:cover;">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                                            <p class="card-text"><?= htmlspecialchars($product['product_description']) ?></p>
                                            <p class="card-text"><strong><?= number_format($product['product_price'], 2) ?> €</strong>
                                            </p>
                                            <form method="POST" action="?url=cart_add">
                                                <input type="hidden" name="product_id"
                                                    value="<?= htmlspecialchars($product['product_id']) ?>">
                                                <label for="quantity_<?= $product['product_id'] ?>">Quantité :</label>
                                                <input type="number" id="quantity_<?= $product['product_id'] ?>" name="quantity"
                                                    value="1" min="1" style="width:50px;">
                                                <button type="submit" class="btn btn-primary mt-2">Ajouter au panier</button>
                                            </form>
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
        <?php include_once "template/footer.php" ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>