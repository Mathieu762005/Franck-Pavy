<?php
// Vérifie si l'utilisateur est connecté
$connected = $_SESSION['user']['id'] ?? null;

// Détermine si on doit afficher le modal
$showLoginModal = !$connected;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click & Collect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>

<body>
<header>
    <?php include_once "template/navbarC&C.php"; ?>

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

                    <?php if ($connected): ?>
                        <a href="?url=checkout" class="btn btn-success">Passer à la commande</a>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary" disabled>Passer à la commande</button>
                    <?php endif; ?>
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
                                        <p class="card-text"><strong><?= number_format($product['product_price'], 2) ?> €</strong></p>

                                        <!-- Vérification stock -->
                                        <?php if ($product['product_available'] > 0): ?>
                                            <form method="POST" action="?url=cart_add" class="add-to-cart-form mt-auto">
                                                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-primary w-100 mt-2">Ajouter au panier</button>
                                            </form>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-secondary w-100 mt-2" disabled>Rupture de stock</button>
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

<!-- Modal pour inviter à se connecter -->
<?php if ($showLoginModal): ?>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Retour à l'accueil</h5>
                    <a href="index.php?url=01_home" class="btn-close" aria-label="Close"></a>
                </div>
                <div class="modal-body">
                    Vous devez être connecté pour passer commande.
                </div>
                <div class="modal-footer">
                    <a href="index.php?url=login" class="btn btn-primary">Se connecter</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var myModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        });
    </script>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script panier -->
<script>
    document.querySelectorAll('.cart-quantity').forEach(input => {
        input.addEventListener('input', function () {
            const quantity = parseInt(this.value) || 0;
            const price = parseFloat(this.dataset.price);
            const rowTotalCell = this.closest('tr').querySelector('.cart-total');
            const rowTotal = quantity * price;
            rowTotalCell.textContent = rowTotal.toFixed(2) + ' €';

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
                this.closest('tr').remove();

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