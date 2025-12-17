<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
</head>

<nav class="navbar navbar-expand-lg background py-3">
    <div class="container-fluid">

        <div class="collapse navbar-collapse d-flex align-items-center justify-content-between" id="navbarScroll">

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

                <li class="nav-item me-3">
                    <a class="nav-link d-flex align-items-center">
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