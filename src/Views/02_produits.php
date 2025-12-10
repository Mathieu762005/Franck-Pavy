<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($categories[0]['category_name'] ?? 'Catégorie') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/produits.css">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php"; ?>
    </header>
    <div class="partie1 d-flex">
        <div class="partie1Gauche">
            <img src="<?= htmlspecialchars($categories[0]['image'] ?? '') ?>"
                alt="<?= htmlspecialchars($categories[0]['category_name'] ?? '') ?>" class="img-fluid w-100 mb-4">‚
        </div>
        <div class="partie1Droite px-5 py-4">
            <div class="text-end">
                <img src="/assets/image/Logo/LogoArtisan.png" class="logoArtisan border border-black border-2" alt="">
            </div>
            <div>
                <h1 class="h1 mb-5"><?= htmlspecialchars($categories[0]['category_description'] ?? '') ?></h1>
                <button class="bouton rounded-5 py-1 px-4">Click & Collect</button>
            </div>
        </div>
    </div>
    <div class="partie2 mx-auto">
        <div class="text-center">
            <img src="/assets/image/Logo/Logo.png" class="logo mt-5" alt="">
            <h2><?= htmlspecialchars($categories[0]['category_name'] ?? '') ?></h2>
        </div>
    </div>
    <div class="partie3Margee mx-auto">
        <?php if (!empty($categories[0]['products'])): ?>
            <?php foreach ($categories[0]['products'] as $index => $product): ?>
                <div class="partieProduit <?= $index % 2 !== 0 ? 'reverse' : '' ?>">
                    <div class="imageProduitContainer">
                        <img src="/assets/image/<?= htmlspecialchars($product['product_image'] ?? 'default.png') ?>"
                            alt="<?= htmlspecialchars($product['product_name'] ?? '') ?>" class="rounded-4">
                    </div>
                    <div class="descriptionProduit">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.13.0/ScrollTrigger.min.js"></script>
    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray('.partieProduit').forEach((element) => {
            gsap.from(element, {
                y: 50,              // décalage vertical depuis le bas
                opacity: 0,          // invisible au départ
                duration: 1,         // durée de l'animation
                ease: "power3.out",  // type de mouvement
                scrollTrigger: {
                    trigger: element,  // élément déclencheur
                    start: "top 80%",  // quand l'élément entre dans la fenêtre
                    toggleActions: "play none none none"
                }
            });
        });
    </script>
</body>

</html>