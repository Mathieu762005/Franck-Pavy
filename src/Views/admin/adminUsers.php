<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/commandeAdmin.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <?php include_once __DIR__ . '/../template/navbarAdmin.php'; ?>
    </header>

    <main class="A-commande-partie1 d-flex flex-grow-1">

        <!-- SIDEBAR -->
        <aside class="A-commande-partie1__aside">
            <div>
                <ul class="navbar-nav mx-auto" style="width: 80%;">
                    <li class="nav-item mt-3">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminCommandes">
                            <i class="bi bi-basket2-fill me-3"></i> COMMANDES
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminUsers">
                            <i class="bi bi-person-circle me-3"></i> CLIENTS
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminProducts">
                            <i class="bi bi-box-seam me-3"></i> PRODUITS
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminMessages">
                            <i class="bi bi-chat-left-text-fill me-3"></i> MESSAGES
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="A-commande-partie1__centrale">
            <div class="A-commande-partie1__titre-marge">
                <h1 class="A-commande-partie1__titre text-white p-3 ms-4 mb-0">Gestion Utilisateurs</h1>
            </div>

            <div class="A-commande-partie1__contour">
                <?php if (!empty($users)): ?>
                    <table class="table table-striped custom-table">
                        <thead class="thead">
                            <tr class="text-center">
                                <th>id</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>E-mail</th>
                                <th>Commande effectué</th>
                                <th>Montant dépensé</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="text-center">
                                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                                    <td><?= htmlspecialchars($user['user_name']) ?></td>
                                    <td><?= htmlspecialchars($user['user_first_name']) ?></td>
                                    <td><?= htmlspecialchars($user['user_email']) ?></td>
                                    <td><?= htmlspecialchars($user['user_orders_count']) ?></td>
                                    <td><?= htmlspecialchars($user['user_total_spent']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucune commande.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>