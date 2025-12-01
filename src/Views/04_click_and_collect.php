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

        <!-- Offcanvas placé juste après la navbar -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Panier de commande</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
            </div>
        </div>
    </header>

    <main>
        <?php foreach ($categories as $categoryName => $categoryData): ?>
            <h2><?= htmlspecialchars($categoryName) ?></h2>
            <img src="<?= htmlspecialchars($categoryData['image']) ?>" alt="<?= htmlspecialchars($categoryName) ?>"
                class="img-fluid mb-3">

            <div class="row">
                <?php if (!empty($categoryData['products'])): ?>
                    <?php foreach ($categoryData['products'] as $product): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="/assets/image/<?= htmlspecialchars($product->product_image) ?>" class="card-img-top"
                                    alt="<?= htmlspecialchars($product->product_name) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product->product_name) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($product->product_description) ?></p>
                                    <?php if (property_exists($product, 'product_price')): ?>
                                        <p class="card-text"><strong><?= number_format($product->product_price, 2) ?> €</strong></p>
                                    <?php endif; ?>
                                    <!-- Formulaire qui envoie vers ton contrôleur -->
                                    <form method="post" action="index.php?url=cart/add">
                                        <input type="hidden" name="product_id" value="<?= $product->product_id ?>">
                                        <input type="hidden" name="product_name" value="<?= $product->product_name ?>">
                                        <input type="hidden" name="unit_price" value="<?= $product->product_price ?>">
                                        <input type="number" name="quantity" value="1" min="1" class="form-control mb-2">
                                        <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucun produit disponible pour cette catégorie.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </main>

    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
        <?php include_once "template/footer.php" ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>