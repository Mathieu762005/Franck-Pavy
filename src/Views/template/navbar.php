<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="http://localhost:8000/index.php?url=01_home">logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8000/index.php?url=01_home">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Link
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=02_produits&id=1">Les
                                Pains</a></li>
                        <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=02_produits&id=2">Les
                                Viennoiseries </a></li>
                        <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=02_produits&id=3">Pause
                                Déjeuner </a></li>
                        <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=02_produits&id=4">Les
                                Pâtisseries </a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8000/index.php?url=03_a_propos">A propos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8000/index.php?url=05_contact">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8000/index.php?url=04_click_and_collect">Click And
                        Collect</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Link
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=login">Connection</a>
                        </li>
                        <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=register">crée ton
                                compte</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="http://localhost:8000/index.php?url=06_profil">Profil</a>
                </li>
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Admin
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=adminUsers">Gestion des
                                    Gestion des utilisateurs</a></li>
                            <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=adminMessages">Gestion
                                    Gestion des messages</a></li>
                            <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=adminProducts">Gestion
                                    Gestion des produits</a></li>
                            <li><a class="dropdown-item" href="http://localhost:8000/index.php?url=adminCommandes">Gestion
                                    Gestion des commandes</a></li>
                        </ul>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>