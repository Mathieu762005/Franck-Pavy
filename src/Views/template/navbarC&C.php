<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>

<nav class="navbar navbar-expand-lg background">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center" id="navbarScroll">

            <!-- GAUCHE 30% -->
            <ul class="navbar-nav d-flex align-items-center justify-content-start ms-3" style="width:30%;">
                <li class="nav-item">
                    <a class="navbar-brand" href="http://localhost:8000/index.php?url=01_home">
                        <img src="assets/image/Logo/Logo.png" alt="">
                    </a>
                </li>
                <li class="nav-item d-lg-none">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarScroll">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </li>
            </ul>

            <!-- CENTRE 40% -->
            <ul class="navbar-nav mx-auto flex-row align-items-center gap-3">
                <li class="nav-item d-flex align-items-center gap-1 color">
                    <i class="bi bi-phone-fill" style="font-size: 1.5rem;"></i>
                    <a href="tel:0235302758" class="mon-lien">02 35 30 27 58</a>
                </li>

                <li class="nav-item">
                    <span class="mx-2 colorBarre" style="color: #571065; font-size: 1.5rem;"> | </span>
                </li>

                <li class="nav-item d-flex align-items-center gap-1 color">
                    <i class="bi bi-geo-alt-fill" style="font-size: 1.5rem;"></i>
                    <span class="mon-lien" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Montivilliers</span>
                </li>
            </ul>

            <!-- DROITE 30% -->
            <ul class="navbar-nav d-flex align-items-center justify-content-end" style="width:30%;">
                <?php if (!isset($_SESSION['user']['id'])): ?>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle color" href="#" data-bs-toggle="dropdown">
                            Compte
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php?url=login">Connexion</a></li>
                            <li><a class="dropdown-item" href="index.php?url=register">Créer un compte</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle color" href="#" data-bs-toggle="dropdown">Admin</a>
                        <ul class="dropdown-menu dropdown-menu-start">
                            <li><a class="dropdown-item" href="index.php?url=adminUsers">Gestion utilisateurs</a></li>
                            <li><a class="dropdown-item" href="index.php?url=adminMessages">Gestion messages</a></li>
                            <li><a class="dropdown-item" href="index.php?url=adminProducts">Gestion produits</a></li>
                            <li><a class="dropdown-item" href="index.php?url=adminCommandes">Gestion commandes</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

                <li class="nav-item me-3">
                    <a class="nav-link d-flex align-items-center" href="index.php?url=06_profil">
                        <i class="bi bi-person-circle color" style="font-size: 1.5rem;"></i>
                        <?php if (isset($_SESSION['user']['firstname']) && !empty($_SESSION['user']['firstname'])): ?>
                            <span class="mon-lien ms-1">
                                <?= htmlspecialchars($_SESSION['user']['firstname']) ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if (isset($_GET['url']) && $_GET['url'] === '04_click_and_collect'): ?>
                    <li class="nav-item me-3">
                        <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight">
                            <i class="bi bi-basket2-fill color" style="font-size: 1.5rem;"></i>
                        </button>
                    </li>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</nav>


<!-- Vertically centered modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">La boulangerie À Toute Heure a Montivilliers</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2588.75778905509!2d0.19044397724887885!3d49.545723352090796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e03abf207c051f%3A0x19f908d7f9b7272!2s62%20Rue%20F%C3%A9lix%20Faure%2C%2076290%20Montivilliers!5e0!3m2!1sfr!2sfr!4v1765131683511!5m2!1sfr!2sfr"
                    width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>