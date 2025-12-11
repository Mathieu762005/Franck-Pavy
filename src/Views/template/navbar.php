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
            <ul class="navbar-nav mx-auto justify-content-evenly" style="width:40%;">
                <li class="nav-item">
                    <a class="nav-link color" href="index.php?url=01_home">Accueil</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle color" href="#" data-bs-toggle="dropdown">
                        Produits
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="index.php?url=02_produits&id=1">Les Pains</a></li>
                        <li><a class="dropdown-item" href="index.php?url=02_produits&id=2">Les Viennoiseries</a></li>
                        <li><a class="dropdown-item" href="index.php?url=02_produits&id=3">Pause Déjeuner</a></li>
                        <li><a class="dropdown-item" href="index.php?url=02_produits&id=4">Les Pâtisseries</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link color" href="index.php?url=03_a_propos">A propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link color" href="index.php?url=05_contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link color" href="index.php?url=04_click_and_collect">Click & Collect</a>
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