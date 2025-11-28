<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($category->category_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php" ?>
    </header>

    <div class="container mt-4">
        <!-- Affichage de la catégorie -->
        <h1><?= htmlspecialchars($category->category_name) ?></h1>
        <p><?= htmlspecialchars($category->category_description) ?></p>
        <img src="/assets/image/Categories/<?= $banner ?>" alt="<?= htmlspecialchars($category->category_name) ?>"
            class="img-fluid w-100">

        <!-- Affichage des produits -->
        <?php if (!empty($products)): ?>
            <div class="row">
                <?php foreach ($products as $prod): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <!-- Image du produit -->
                            <img src="/assets/image/<?= htmlspecialchars($prod->product_image) ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($prod->product_name) ?>">

                            <div class="card-body">
                                <!-- Nom du produit -->
                                <h5 class="card-title"><?= htmlspecialchars($prod->product_name) ?></h5>

                                <!-- Description -->
                                <p class="card-text"><?= htmlspecialchars($prod->product_description) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun produit trouvé pour cette catégorie.</p>
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