<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click And Collect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php" ?>
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
                                        <p class="card-text"><strong><?= number_format($product->product_price, 2) ?> €</strong>
                                        </p>
                                    <?php endif; ?>
                                    <a href="index.php?url=add_to_cart&id=<?= $product->product_id ?>"
                                        class="btn btn-primary">Ajouter au panier</a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>