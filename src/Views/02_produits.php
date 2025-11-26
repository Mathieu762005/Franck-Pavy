<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category->category_name) ?></title>
</head>

<body>
    <div class="container mt-4">
        <!-- Affichage de la catégorie -->
        <h1><?= htmlspecialchars($category->category_name) ?></h1>
        <p><?= htmlspecialchars($category->category_description) ?></p>

        <!-- Affichage des produits -->
        <?php if (!empty($products)): ?>
            <div class="row">
                <?php foreach ($products as $prod): ?>
                    <div class="col-md-4">
                        <div class="card mb-4 shadow-sm">
                            <!-- Image du produit -->
                            <img src="/assets/image/<?= htmlspecialchars($prod->product_image) ?>"
                                class="card-img-top" alt="<?= htmlspecialchars($prod->product_name) ?>">

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

</body>

</html>