<!DOCTYPE html>
<html lang="fr">

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
        <div class="home-partie1">
            <div class="home-partie1__baniere d-flex flex-column justify-content-between">
                <h1 class="home-partie1__titre text-white">Du bon, du frais, du Pavy. Bienvenue A Toute Heure.</h1>
                <div>
                    <a href="index.php?url=04_click_and_collect">
                        <button class="home-partie1__btn rounded-5 py-1 px-4">Click & Collect</button>
                    </a>
                </div>
            </div>
            <img src="/assets/image/Logo/LogoArtisan.png" class="home-partie1__logo border border-black border-2"
                alt="Logo artisan">
        </div>
        <div class="home-partie2 mx-auto">
            <div class="home-partie2__haute text-center mx-auto">
                <img src="/assets/image/Logo/Logo.png" class="home-partie2__logo" alt="Logo principal">
                <h2 class="home-partie2__titre abhayaLibre">Fait maison, fait avec cœur, fait pour vous.</h2>
            </div>
            <div class="home-partie2__categories d-flex flex-wrap justify-content-evenly mx-auto my-5">

                <a href="index.php?url=02_produits&id=1" class="home-partie2__categorie home-partie2__categorie--pains">
                    <span class="home-partie2__categorie-titre">Pains</span>
                    <p class="home-partie2__categorie-description">Nos pains, saveur artisanale garantie</p>
                </a>

                <a href="index.php?url=02_produits&id=2"
                    class="home-partie2__categorie home-partie2__categorie--viennoiseries">
                    <span class="home-partie2__categorie-titre">Viennoiseries</span>
                    <p class="home-partie2__categorie-description">Nos viennoiseries, le plaisir croustillant</p>
                </a>

                <a href="index.php?url=02_produits&id=3"
                    class="home-partie2__categorie home-partie2__categorie--dejeuner">
                    <span class="home-partie2__categorie-titre">Déjeuner</span>
                    <p class="home-partie2__categorie-description">Pause déjeuner, fraîcheur et gourmandise</p>
                </a>

                <a href="index.php?url=02_produits&id=4"
                    class="home-partie2__categorie home-partie2__categorie--patisseries">
                    <span class="home-partie2__categorie-titre">Pâtisseries</span>
                    <p class="home-partie2__categorie-description">Nos pâtisseries, douceur pour chaque occasion</p>
                </a>

            </div>
        </div>
        <div class="home-partie3 d-flex">
            <div class="home-partie3__marge mx-auto d-flex">
                <div class="home-partie3__gauche">
                    <h3 class="home-partie3__titre abhaya-libre-bold pb-5">Des ingrédients choisis avec soin,
                        savoir-faire maîtrisé.</h3>
                    <p class="home-partie3__description pb-5">Faire appel à une boulangerie comme Boulangerie
                        À Toute Heure - Frank Pavy, c’est profiter
                        de produits frais préparés chaque jour,
                        d’un service local et de recettes artisanales. Parfait
                        pour un repas rapide, une commande spéciale ou un simple moment gourmand.</p>
                    <div class="d-flex justify-content-center">
                        <img src="/assets/image/Logo/Logo.png" alt="">
                    </div>
                </div>
                <div class="home-partie3__droite d-flex justify-content-end">
                    <img src="/assets/image/preparation-3.png" class="home-partie3__img rounded-5" alt="">
                </div>
            </div>
        </div>
        <div class="home-partie4 mx-auto">
            <div class="home-partie4__haute d-flex justify-content-center mt-5">
                <img src="/assets/image/Logo/LogoArtisan.png"
                    class="home-partie4__logo border border-black border-2 my-5" alt="">
            </div>
            <div class="home-partie4__basse d-flex pt-4">
                <div class="home-partie4__textGauche d-flex">
                    <p class="text-start">À Toute Heure, Boulangerie Pâtisserie en plein centre de Montivilliers.
                        Vous y
                        retrouverez toutes
                        sortes de Pains et Viennoiseries, ainsi que les pâtisseries créations de Frank Pavy.
                        Tous nos
                        produits sont fabriqués maison, directement dans notre laboratoire.</p>
                </div>
                <div class="home-partie4__textDroite d-flex">
                    <p>Toute l'équipe de Boulangerie À Toute Heure - Frank Pavy est ravie de vous
                        accueillir pour vous
                        offrir une expérience exceptionnelle. Profitez de notre savoir-faire et de nos
                        services professionnels.</p>
                </div>
            </div>
        </div>
        <div class="home-partie5">
            <div class="home-partie5__marge mx-auto">
                <div class="home-partie5__haute d-flex justify-content-center">
                    <img src="/assets/image/commande.png" class="home-partie5__image" alt="">
                </div>
                <h4 class="home-partie5__titre home-partie5__commande abhaya-libre-bold">
                    Je commande
                </h4>
                <div class="home-partie5__centrale d-flex justify-content-between">
                    <div class="home-partie5__gauche">
                        <h4 class="home-partie5__titre abhaya-libre-bold mb-3">On prépare</h4>
                        <img src="/assets/image/preparation.png" class="home-partie5__image" alt="">
                    </div>
                    <div class="home-partie5__droite row justify-content-end align-items-end">
                        <div class="home-partie5__decalage">
                            <h4 class="home-partie5__titre abhaya-libre-bold mb-3 text-center">je récupère</h4>
                            <img src="/assets/image/livraison.png" class="home-partie5__image" alt="">
                        </div>
                    </div>
                </div>
                <div class="home-partie5__basse text-center">
                    <a href="index.php?url=04_click_and_collect"><button
                            class="home-partie5__btn rounded-5 py-1 px-4">Click & Collect</button></a>
                </div>
            </div>
        </div>
        <div class="home-partie6 mx-auto">
            <div class="home-partie6__haute text-center mx-auto">
                <img src="/assets/image/Logo/Logo.png" class="home-partie6__logo" alt="">
                <h3 class="home-partie6__titre abhayaLibre">Saveurs artisanales</h3>
            </div>
            <div class="home-partie6__basse text-center row row-cols-1 row-cols-md-3 g-4">
                <div class="col">
                    <div class="card">
                        <img src="/assets/image/home1.png" class="card-img-top rounded-5" alt="...">
                        <div class="card-body">
                            <h5 class="card-title abhaya-libre-bold">Notre engagement</h5>
                            <p class="card-text">Chaque jour, nous choisissons des ingrédients frais et de qualité pour
                                créer des produits artisanaux qui allient tradition et innovation.</p>
                            <div class="home-partie6__basse text-center">
                                <a href="index.php?url=03_a_propos"><button
                                        class="home-partie6__btn rounded-5 py-1 px-4">Notre histoire</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="/assets/image/home2.png" class="card-img-top rounded-5" alt="...">
                        <div class="card-body">
                            <h5 class="card-title abhaya-libre-bold">Commandez en ligne</h5>
                            <p class="card-text">Commandez en ligne, récupérez en boutique : rapide, pratique,
                                et
                                toujours avec la même qualité artisanale.</p>
                            <div class="home-partie6__basse text-center">
                                <a href="index.php?url=04_click_and_collect"><button
                                        class="home-partie6__btn rounded-5 py-1 px-4">Click & Collect</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card">
                        <img src="/assets/image/home3.png" class="card-img-top rounded-5" alt="...">
                        <div class="card-body">
                            <h5 class="card-title abhaya-libre-bold">Contactez-nous en un instant</h5>
                            <p class="card-text">Laissez-nous un message ou passez directement en boutique :
                                nous
                                sommes toujours prêts à vous répondre.</p>
                            <div class="home-partie6__basse text-center">
                                <a href="index.php?url=05_contact"><button
                                        class="home-partie6__btn rounded-5 py-1 px-4">Contactez-nous</button></a>
                            </div>
                        </div>
                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        gsap.registerPlugin(ScrollTrigger);
        /* ------------------------------------------
   PARTIE 1 — Logo bannière
------------------------------------------- */
        gsap.from(".home-partie1__logo", {
            opacity: 0,
            y: 40,
            duration: 1.1,
            ease: "power3.out",
            delay: 0.4
        });

        /* ------------------------------------------
           PARTIE 2 — Logo + Titre + Catégories
        ------------------------------------------- */

        gsap.utils.toArray(".home-partie2__categorie").forEach((card, i) => {
            // Animation d'apparition au scroll
            gsap.from(card, {
                opacity: 0,
                y: 60,
                scale: 0.95,
                duration: 1.1,
                delay: i * 0.1,
                ease: "power4.out",
                scrollTrigger: {
                    trigger: card,
                    start: "top 90%"
                }
            });

            // Animation hover
            card.addEventListener("mouseenter", () => {
                gsap.to(card, {
                    scale: 1.05,
                    y: -5,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });

            card.addEventListener("mouseleave", () => {
                gsap.to(card, {
                    scale: 1,
                    y: 0,
                    duration: 0.3,
                    ease: "power2.out"
                });
            });
        });


        /* ------------------------------------------
           PARTIE 3 — Texte + Image
        ------------------------------------------- */
        gsap.from(".home-partie3__gauche", {
            opacity: 0,
            y: 70,
            duration: 1.1,
            ease: "power4.out",
            scrollTrigger: {
                trigger: ".home-partie3__gauche",
                start: "top 85%"
            }
        });

        gsap.from(".home-partie3__droite", {
            opacity: 0,
            x: 80,    // arrivée douce depuis la droite
            y: 20,    // léger mouvement vertical pour plus de finesse
            duration: 1.3,
            ease: "power3.out",
            scrollTrigger: {
                trigger: ".home-partie3__droite",
                start: "top 85%",
                once: true
            }
        });


        /* ------------------------------------------
           PARTIE 4 — Bloc texte
        ------------------------------------------- */
        gsap.from(".home-partie4__haute", {
            opacity: 0,
            y: 60,
            duration: 1,
            ease: "power4.out",
            scrollTrigger: {
                trigger: ".home-partie4__haute",
                start: "top 85%"
            }
        });

        gsap.from(".home-partie4__basse", {
            opacity: 0,
            y: 70,
            duration: 1,
            ease: "power4.out",
            scrollTrigger: {
                trigger: ".home-partie4__basse",
                start: "top 85%"
            }
        });


        /* ------------------------------------------
           PARTIE 5 — Je commande (3 images + titres)
        ------------------------------------------- */
        gsap.from(".home-partie5__haute", {
            opacity: 0,
            y: 70,
            duration: 1,
            ease: "power4.out",
            scrollTrigger: {
                trigger: ".home-partie5__haute",
                start: "top 85%"
            }
        });

        gsap.utils.toArray(".home-partie5__gauche").forEach((bloc, i) => {
            gsap.from(bloc, {
                opacity: 0,
                x: i % 2 === 0 ? -80 : 80, // gauche → droite / droite → gauche
                y: 20, // petit mouvement vertical subtil pour plus de fluidité
                duration: 1.3,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: bloc,
                    start: "top 85%",
                    once: true
                }
            });
        });

        gsap.utils.toArray(".home-partie5__decalage").forEach((bloc) => {
            gsap.from(bloc, {
                opacity: 0,
                x: 60,     // slide latéral plus fin
                y: 10,     // léger flottement vertical
                duration: 1.2,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: bloc,
                    start: "top 85%",
                    once: true
                }
            });
        });

        gsap.from(".home-partie5__btn", {
            opacity: 0,
            y: 40,
            duration: 1,
            ease: "power4.out",
            scrollTrigger: {
                trigger: ".home-partie5__btn",
                start: "top 85%"
            }
        });


        /* ------------------------------------------
           PARTIE 6 — Slogans + cartes
        ------------------------------------------- */
        gsap.from(".home-partie6__haute", {
            opacity: 0,
            y: 60,
            duration: 1,
            ease: "power4.out",
            scrollTrigger: {
                trigger: ".home-partie6__haute",
                start: "top 85%"
            }
        });

        gsap.utils.toArray(".home-partie6 .card").forEach((card, i) => {
            gsap.from(card, {
                opacity: 0,
                y: 60,
                scale: 0.97,
                duration: 1.1,
                delay: i * 0.12,
                ease: "power4.out",
                scrollTrigger: {
                    trigger: card,
                    start: "top 90%"
                }
            });
        });
    </script>
</body>

</html>