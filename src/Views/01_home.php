<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/home.css">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php" ?>
    </header>
    <main>
        <div class="partie1 baniere">
            <div class="textBouton d-flex flex-column justify-content-between">
                <h1 class="text-white AbhayaLibre titre">Du bon, du frais, du Pavy. Bienvenue A Toute Heure.</h1>
                <div>
                    <button class="btnBeige rounded-5 py-1 px-4">Click & Collect</button>
                </div>
            </div>
            <img src="/assets/image/Logo/LogoArtisan.png" class="border border-black border-2" alt="">
        </div>
        <div class="partie2">
            <div class="text-center pt-5 mx-auto mx">
                <img src="/assets/image/Logo/Logo.png" class="logo" alt="">
                <h2 class="AbhayaLibre titreH2">Fait maison, fait avec cœur, fait pour vous.</h2>
            </div>
            <div class="d-flex flex-wrap justify-content-evenly mx-auto boite my-5">

                <a href="index.php?url=02_produits&id=1" class="categorie pains">
                    <span class="titre">Pains</span>
                    <p class="description">Nos pains, saveur artisanale garantie</p>
                </a>

                <a href="index.php?url=02_produits&id=2" class="categorie viennoiseries">
                    <span class="titre">Viennoiseries</span>
                    <p class="description">Nos viennoiseries, le plaisir croustillant</p>
                </a>

                <a href="index.php?url=02_produits&id=3" class="categorie dejeuner">
                    <span class="titre">Déjeuner</span>
                    <p class="description">Pause déjeuner, fraîcheur et gourmandise</p>
                </a>

                <a href="index.php?url=02_produits&id=4" class="categorie patisseries">
                    <span class="titre">Pâtisseries</span>
                    <p class="description">Nos pâtisseries, douceur pour chaque occasion</p>
                </a>

            </div>
        </div>
        <div class="partie3 mx-auto d-flex py-5 mb-5">
            <div class="partie3Marge mx-auto d-flex py-5">
                <div class="partie3-gauche">
                    <h3 class="pb-5">Des ingrédients choisis avec soin, savoir-faire maîtrisé.</h3>
                    <p class="partie3Description pb-5">Faire appel à une boulangerie comme Boulangerie
                        À Toute Heure - Frank Pavy, c’est profiter
                        de produits frais préparés chaque jour,
                        d’un service local et de recettes artisanales. Parfait
                        pour un repas rapide, une commande spéciale ou un simple moment gourmand.</p>
                    <div class="d-flex justify-content-center">
                        <img src="/assets/image/Logo/Logo.png" alt="">
                    </div>
                </div>
                <div class="partie3-droite d-flex justify-content-end">
                    <img src="/assets/image/preparation-3.png" class="partie3-droiteImg rounded-5" alt="">
                </div>
            </div>
        </div>
        <div class="partie4 mx-auto mb-5">
            <div class="partie4-haute d-flex justify-content-center">
                <img src="/assets/image/Logo/LogoArtisan.png" class="partie4Logo border border-black border-2 my-5"
                    alt="">
            </div>
            <div class="partie4-basse d-flex pt-4">
                <div class="textGauche d-flex">
                    <p class="text-start">À Toute Heure, Boulangerie Pâtisserie en plein centre de Montivilliers. Vous y
                        retrouverez toutes
                        sortes de Pains et Viennoiseries, ainsi que les pâtisseries créations de Frank Pavy.
                        Tous nos
                        produits sont fabriqués maison, directement dans notre laboratoire.</p>
                </div>
                <div class="textDroite d-flex">
                    <p>Toute l'équipe de Boulangerie À Toute Heure - Frank Pavy est ravie de vous
                        accueillir pour vous
                        offrir une expérience exceptionnelle. Profitez de notre savoir-faire et de nos
                        services professionnels.</p>
                </div>
            </div>
        </div>
        <div class="partie5">
            <div class="partie5Marge mx-auto">
                <div class="partie5Haute d-flex justify-content-center">
                    <img src="/assets/image/commande.png" class="imageCommande" alt="">
                </div>
                <h4 class="jeCommande">
                    Je commande
                </h4>
                <div class="partie5Basse d-flex justify-content-between">
                    <div class="partie5BasseGauche">
                        <h4 class="mb-3">On prépare</h4>
                        <img src="/assets/image/preparation.png" class="imageCommande" alt="">
                    </div>
                    <div class="partie5BasseDroite row justify-content-end align-items-end">
                        <div class="boiteImageText">
                            <h4 class="mb-3 text-center">je récupère</h4>
                            <img src="/assets/image/livraison.png" class="imageCommande" alt="">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-5">
                    <button class="btnNoire rounded-5 py-1 px-4">Click & Collect</button>
                </div>
            </div>
        </div>
        <div class="partie6">
            <div class="partie6Marge mx-auto">
                <div class="text-center pt-5 mx-auto mb-5">
                    <img src="/assets/image/Logo/Logo.png" class="logo" alt="">
                    <h3 class="avis">Vos avis</h3>
                </div>
                <div class="partie6Avis mx-auto border border-black border-2 rounded-5">
                    <p class="text-center mt-3">les avis vont etre affichez plus tard</p>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer text-white text-end pe-3 py-3 d-flex align-items-center justify-content-end border border-top border-black border-2">
        <?php include_once "template/footer.php" ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>