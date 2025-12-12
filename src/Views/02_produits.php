<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categories[0]['category_name'] ?? 'Catégorie') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/produit.css">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php"; ?>
    </header>
    <div class="produit-partie1 d-flex">
        <div class="produit-partie1__gauche">
            <img src="<?= htmlspecialchars($categories[0]['image'] ?? '') ?>"
                alt="<?= htmlspecialchars($categories[0]['category_name'] ?? '') ?>" class="img-fluid w-100 mb-4">‚
        </div>
        <div class="produit-partie1__droite px-5 py-4">
            <div class="produit-partie1__logo text-end">
                <img src="/assets/image/Logo/LogoArtisan.png"
                    class="produit-partie1__logoArtisan border border-black border-2" alt="">
            </div>
            <div class="produit-partie1__text">
                <h1 class="produit-partie1__titre mb-5">
                    <?= htmlspecialchars($categories[0]['category_description'] ?? '') ?>
                </h1>
                <button class="produit-partie1__btn rounded-5 py-1 px-4">Click & Collect</button>
            </div>
        </div>
    </div>
    <div class="produit-partie2 mx-auto">
        <div class="produit-partie2__haute text-center">
            <img src="/assets/image/Logo/Logo.png" class="logo mt-4" alt="">
            <h2><?= htmlspecialchars($categories[0]['category_name'] ?? '') ?></h2>
        </div>
        <?php if (!empty($categories[0]['products'])): ?>
            <?php foreach ($categories[0]['products'] as $index => $product): ?>
                <div class="produit-partie2__produit <?= $index % 2 !== 0 ? 'reverse' : '' ?>">
                    <div class="produit-partie2__image">
                        <img src="/assets/image/<?= htmlspecialchars($product['product_image'] ?? 'default.png') ?>"
                            alt="<?= htmlspecialchars($product['product_name'] ?? '') ?>" class="rounded-4">
                    </div>
                    <div class="produit-partie2__description">
                        <h3><?= htmlspecialchars($product['product_name'] ?? '') ?></h3>
                        <h4><?= htmlspecialchars($product['product_subtitle'] ?? '') ?></h4>
                        <p><?= htmlspecialchars($product['product_description'] ?? '') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun produit trouvé pour cette catégorie.</p>
        <?php endif; ?>
    </div>

    <footer>
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);

        /* --- Animation des produits --- */
        gsap.utils.toArray(".produit-partie2__produit").forEach((prod) => {
            gsap.from(prod, {
                opacity: 0,
                y: 80,
                scale: 0.97,
                duration: 1.1,
                ease: "power4.out",
                scrollTrigger: {
                    trigger: prod,
                    start: "top 85%",
                    toggleActions: "play none none reverse"
                }
            });
        });
    </script>
</body>

</html>