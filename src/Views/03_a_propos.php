<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A propos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/Apropos.css">
</head>

<body>
    <header>
        <?php include_once "template/navbar.php" ?>
    </header>
    <main>
        <div class="partie1">
            <div class="textBouton d-flex flex-column justify-content-between">
                <h1 class="text-white titreh1">Artisanat et passion au quotidien.</h1>
            </div>
            <img src="/assets/image/Logo/LogoArtisan.png" class="logoArtisant border border-black border-2" alt="">
        </div>
        <div class="partie2 mx-auto">
            <div class="partie2Haute text-center">
                <img src="/assets/image/Logo/Logo.png" class="logo mt-5" alt="">
                <h3 class="titreh2">Bienvenue chez nous.</h3>
            </div>
            <div class="partie2Text d-flex">
                <div class="partie2Gauche me-5">
                    <p>Faire appel à une boulangerie comme Boulangerie À Toute Heure - Frank Pavy, c’est profiter de
                        produits frais préparés chaque jour, d’un service local et de recettes artisanales.</p>
                    <p>Parfait pour un repas rapide, une commande spéciale ou un simple moment gourmand. Profitez d’un
                        accueil chaleureux et d’une offre variée en boulangerie et pâtisserie artisanale.</p>
                </div>
                <div class="partie2Droite ms-5">
                    <p>Pour passer commande, réserver une pâtisserie ou connaître
                        les horaires, appelez dès maintenant Boulangerie À Toute Heure - Frank Pavy, situé à 62 Rue
                        Félix Faure 76290 Montivilliers (Centre Ville). Boulangerie À Toute Heure - Frank Pavy vous
                        accueille avec des produits faits maison, disponibles tout au long de la journée. Pour connaître
                        les prix ou produits disponibles, un simple appel suffit.</p>
                </div>
            </div>
        </div>
        <div class="partie3 pt-5">
            <div class="partie3Marge mx-auto">
                <div class="text-center">
                    <img src="/assets/image/Logo/LogoArtisan.png"
                        class="partie3logoArtisant border border-black border-2 mb-4" alt="">
                    <h3 class="titreh3 text-center mx-auto">À propos de notre boulangerie</h3>
                </div>
                <div class="partie3Haute d-flex">
                    <div class="partie3HauteGauche">
                        <img src="/assets/image/preparationPain1.png" class="partie3Image" alt="">
                    </div>
                    <div class="partie3HauteDroite">
                        <h4 class="titreh4 mb-5">La passion du pain</h4>
                        <p class="partie3Text">Avant même que le soleil ne pointe, notre équipe est déjà à l’œuvre. Le
                            pétrin tourne, les
                            fours chauffent, et les premières odeurs de pain chaud s’échappent dans la rue. Ce rythme
                            matinal, nous l’aimons : il raconte notre engagement, notre rigueur, et notre envie de bien
                            faire. Chaque produit est le fruit d’un travail patient, d’un savoir-faire transmis et d’une
                            passion qui ne dort jamais. Ici, le fait maison n’est pas une tendance — c’est notre
                            quotidien.</p>
                        <p class="partie3Text">Notre boulangerie, c’est aussi un lieu de rencontres. Les sourires
                            échangés, les habitudes
                            partagées, les petits mots du matin… Tout cela fait partie de notre métier. Nous connaissons
                            nos clients, leurs préférences, leurs histoires. Et c’est cette proximité qui nous pousse à
                            donner le meilleur, jour après jour. Derrière chaque croissant, chaque tarte, il y a une
                            envie simple : offrir du bon, du vrai, et créer du lien.</p>
                    </div>
                </div>
                <div class="partie3Basse d-flex">
                    <div class="partie3BasseGauche">
                        <h4 class="titreh4 mb-5">Une passion qui se lève tôt </h4>
                        <p class="partie3Text">Depuis les premières lueurs du jour, notre fournil s’anime au rythme des
                            gestes précis et
                            passionnés. Ici, chaque pain est pétri à la main, chaque viennoiserie façonnée avec soin, et
                            chaque recette pensée pour retrouver le goût du vrai.</p>
                        <p class="partie3Text">Mais notre boulangerie, c’est bien plus qu’un lieu de fabrication : c’est
                            un espace de vie,
                            de rencontres et de souvenirs. Chaque matin, les parfums de pain chaud et de brioche dorée
                            accueillent les habitués comme les curieux. Derrière le comptoir, nous partageons bien plus
                            que des produits : nous offrons une part de notre histoire, de notre région, et de notre
                            engagement pour une alimentation sincère. Que ce soit pour une baguette croustillante, un
                            croissant fondant ou une tarte de saison, vous trouverez ici le goût du fait maison, le
                            plaisir du partage, et l’envie de revenir.</p>
                    </div>
                    <div class="partie3BasseDroite text-end">
                        <img src="/assets/image/preparationPain-2.png" class="partie3Image" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="partie4 mx-auto mt-5">
            <div class="partie4Marge mx-auto">
                <div class="partie4Haute text-center pt-5 mx-auto mb-5">
                    <img src="/assets/image/Logo/Logo.png" class="logo" alt="">
                    <h3 class="avis">Vos avis</h3>
                </div>
                <div class="partie4Basse mx-auto border border-black border-2 rounded-5">
                    <p class="text-center mt-3">les avis vont etre affichez plus tard</p>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include_once "template/footer.php" ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>