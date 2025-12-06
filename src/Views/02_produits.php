<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categories[0]['category_name'] ?? 'Catégorie') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php"; ?>
    </header>

    <div class="container mt-4">
        <!-- Affichage de la catégorie -->
        <h1><?= htmlspecialchars($categories[0]['category_name'] ?? '') ?></h1>
        <p><?= htmlspecialchars($categories[0]['category_description'] ?? '') ?></p>
        <img src="<?= htmlspecialchars($categories[0]['image'] ?? '') ?>"
            alt="<?= htmlspecialchars($categories[0]['category_name'] ?? '') ?>" class="img-fluid w-100 mb-4">

        <!-- Affichage des produits -->
        <?php if (!empty($categories[0]['products'])): ?>
                <?php foreach ($categories[0]['products'] as $product): ?>
                        <img src="/assets/image/<?= htmlspecialchars($product['product_image'] ?? 'default.png') ?>"
                            class="card-img-top" alt="<?= htmlspecialchars($product['product_name'] ?? '') ?>">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($product['product_name'] ?? '') ?></h5>
                            <p><?= htmlspecialchars($product['product_description'] ?? '') ?></p>
                        </div>
                <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit trouvé pour cette catégorie.</p>
        <?php endif; ?>
    </div>

    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end">
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>