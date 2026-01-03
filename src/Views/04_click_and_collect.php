<?php
$connected = $_SESSION['user']['id'] ?? null;
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
    <link rel="stylesheet" href="../assets/css/clicketcollectss.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("pk_test_51SeEPH0So1rm7kaS8HWEgNEEiF2rlknkLqzFLzfMu0HgYZRdXpkYPpXuwNSoCfEkEd31Qi8wbBaaxw2lI1iRv25w00jdQ2tMNq"); // <-- ta clé publique ici
    </script>
</head>

<body>
    <header>
        <?php include_once "template/navbarC&C.php"; ?>

        <!-- Navbar catégories -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                    <ul class="navbar-nav text-center gap-lg-3">
                        <?php foreach ($categories as $category): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#category-<?= $category['category_id'] ?>">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Offcanvas Panier -->
        <div class="offcanvas offcanvas-end panier-offcanvas" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-body d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center border-bottom border-3 border-white pb-3">
                    <h2 class="m-0 text-white">Votre Commande</h2>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>

                <?php if (!empty($cartItems)): ?>
                    <form method="POST" action="?url=cart_update_all" class="d-flex flex-column flex-grow-1 text-white">

                        <!-- Liste des produits -->
                        <div class="cart-items flex-grow-1 overflow-auto mb-3">
                            <?php $total = 0; ?>
                            <?php foreach ($cartItems as $item):
                                $total += $item['cart_item_total_price']; ?>
                                <div class="cart-item d-flex align-items-center justify-content-between pt-3 pb-3 border-bottom border-white border-2">

                                    <!-- Quantité -->
                                    <div class="cart-item-qty me-3 d-flex justify-content-start">
                                        <input type="number" name="quantities[<?= $item['cart_item_id'] ?>]"
                                            class="cart-quantity form-control form-control-sm"
                                            data-price="<?= $item['cart_item_unit_price'] ?>"
                                            value="<?= $item['cart_item_quantity'] ?>" min="1" style="width:60px;">
                                        <input type="hidden" name="unit_prices[<?= $item['cart_item_id'] ?>]"
                                            value="<?= $item['cart_item_unit_price'] ?>">
                                    </div>

                                    <!-- Nom produit -->
                                    <div class="cart-item-name flex-fill d-flex justify-content-start">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                    </div>

                                    <!-- Total -->
                                    <div class="cart-item-total fw-semibold d-flex justify-content-start">
                                        <?= number_format($item['cart_item_total_price'], 2) ?> €
                                    </div>

                                    <!-- Supprimer -->
                                    <div class="cart-item-actions d-flex justify-content-end">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove" data-id="<?= $item['cart_item_id'] ?>">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Footer collé en bas -->
                        <div class="panier-footer text-white">
                            <div class="panier-total d-flex justify-content-between">
                                <span>Total :</span>
                                <span id="cart-grand-total"><?= number_format($total, 2) ?> €</span>
                            </div>
                            <button type="submit" class="btn btn-outline-light w-100 mb-2">Modifier le panier</button>
                            <button type="button" class="btn btn-light w-100" data-bs-toggle="modal" data-bs-target="#pickupTimeModal">
                                Passer à la commande
                            </button>
                        </div>

                    </form>
                <?php else: ?>
                    <p class="text-white pt-3 ps-3">Votre panier est vide.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Modal Choix Heure avec Stripe -->
        <div class="modal fade" id="pickupTimeModal" tabindex="-1" aria-labelledby="pickupTimeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content bg-custom-light">
                    <form id="checkout-form" method="POST" action="?url=checkout_stripe">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="pickupTimeModalLabel" style="color: #571065;">Choisir l'heure de
                                retrait</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <?php if (!empty($timeslots)): ?>
                                <select name="pickup_time" id="pickup_time" class="form-control" required>
                                    <?php foreach ($timeslots as $slot): ?>
                                        <option value="<?= htmlspecialchars($slot['time']) ?>" <?= ($slot['full'] || $slot['past']) ? 'disabled' : '' ?>>
                                            <?= htmlspecialchars($slot['time']) ?>
                                            <?= $slot['full'] ? '- Complet' : ($slot['past'] ? '- Passé' : '') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <p>Aucun créneau disponible pour aujourd'hui.</p>
                            <?php endif; ?>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="submit" class="btn btn-success w-100"
                                style="background-color: #571065; border: none;">
                                Payer avec Stripe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal connexion si non connecté -->
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
        <?php endif; ?>

    </header>

    <!-- Main Click & Collect -->
    <main>
        <h1 class="titre1 text-center">Click & Collect</h1>

        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <section id="category-<?= $category['category_id'] ?>" class="">
                    <?php if (!empty($category['image'])): ?>
                        <div class="category-banner-img d-flex justify-content-center"
                            style="background-image: url('<?= htmlspecialchars($category['image']) ?>');">
                            <h2 class="titre text-white"><?= htmlspecialchars($category['category_name']) ?></h2>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($category['products'])): ?>
                        <div class="partie1-product row mx-auto">
                            <?php foreach ($category['products'] as $product): ?>
                                <div class="col-md-3 mb-5 d-flex justify-content-center">
                                    <div class="card h-100 rounded-3">
                                        <div class="image-wrapper position-relative">
                                            <img src="/assets/image/<?= htmlspecialchars($product['product_image']) ?>"
                                                class="product-img" alt="<?= htmlspecialchars($product['product_name']) ?>">
                                            <?php if ($product['product_available'] <= 0): ?>
                                                <div class="overlay-image">Rupture de stock</div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body d-flex flex-column justify-content-end align-items-center">
                                            <h5 class="card-title text-center mt-1"><?= htmlspecialchars($product['product_name']) ?>
                                            </h5>
                                            <div class="mt-auto w-100 text-center">
                                                <p class="card-text mb-1"><strong><?= number_format($product['product_price'], 2) ?>
                                                        €</strong></p>
                                                <?php if ($product['product_available'] > 0): ?>
                                                    <form method="POST" action="?url=cart_add" class="add-to-cart-form mt-2">
                                                        <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="bouton">
                                                            <i class="ajout bi bi-plus-circle-fill"></i>
                                                            <div class="carre-blanc text-white">s</div>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            </div>
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

    <footer>
        <?php include_once "template/footer.php"; ?>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var offcanvasEl = document.getElementById('offcanvasRight');
            var pickupBtns = document.querySelectorAll('[data-bs-target="#pickupTimeModal"]');

            // --- Gestion Login Modal ---
            <?php if ($showLoginModal): ?>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                loginModal.show();
            <?php endif; ?>

            // --- Gestion Pickup Time Modal ---
            pickupBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Fermer le panier si ouvert
                    var offcanvas = bootstrap.Offcanvas.getInstance(offcanvasEl);
                    if (offcanvas) offcanvas.hide();

                    // Si l'utilisateur n'est pas connecté, ouvrir le loginModal
                    <?php if (!$connected): ?>
                        loginModal.show();
                    <?php endif; ?>
                    // Sinon, pickupTimeModal s'ouvre automatiquement grâce à data-bs-toggle
                });
            });

            // --- Calcul total dynamique du panier ---
            document.querySelectorAll('.cart-quantity').forEach(input => {
                input.addEventListener('input', function() {
                    const quantity = parseInt(this.value) || 0;
                    const price = parseFloat(this.dataset.price);
                    const rowTotalCell = this.closest('tr').querySelector('.cart-total');
                    rowTotalCell.textContent = (quantity * price).toFixed(2) + ' €';

                    let totalGlobal = 0;
                    document.querySelectorAll('.cart-total').forEach(cell => {
                        totalGlobal += parseFloat(cell.textContent.replace(' €', ''));
                    });
                    document.querySelector('#cart-grand-total').textContent = totalGlobal.toFixed(2) + ' €';
                });
            });

            // --- Supprimer un produit du panier ---
            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    const cartItemId = this.dataset.id;
                    fetch('?url=cart_remove', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'cart_item_id=' + cartItemId
                    }).then(() => {
                        // Changer tr par .cart-item
                        this.closest('.cart-item').remove();

                        // Recalcul du total
                        let totalGlobal = 0;
                        document.querySelectorAll('.cart-item-total').forEach(cell => {
                            totalGlobal += parseFloat(cell.textContent.replace(' €', ''));
                        });
                        document.querySelector('#cart-grand-total').textContent = totalGlobal.toFixed(2) + ' €';
                    });
                });
            });

        });
    </script>

</body>

</html>