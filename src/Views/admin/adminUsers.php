<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/commandeAdmine.css">
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
                            <i class="bi bi-person-circle me-3"></i> UTILISATEURS
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
            <div class="A-commande-partie1__titre-marge d-flex align-items-center justify-content-between">
                <h1 class="A-commande-partie1__titre text-white p-3 ms-4 mb-0">Gestion Utilisateurs</h1>
                <form method="GET" role="search" action="index.php" class="d-flex me-5">
                    <input type="hidden" name="url" value="adminUsers">
                    <input type="text"
                        id="userSearch"
                        name="search"
                        class="form-control"
                        placeholder="recherche un utilisateur"
                        autocomplete="off"
                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                    <div id="suggestions" class="list-group position-absolute mt-5"></div>

                    <button type="submit" class="btn btn-outline-secondary ms-2">
                        recherche
                    </button>
                </form>
            </div>

            <div class="A-commande-partie1__contour">
                <?php if (!empty($users)): ?>
                    <table class="table table-striped custom-table align-middle">
                        <thead class="thead">
                            <tr class="text-center">
                                <th>Role</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>E-mail</th>
                                <th>Commande effectué</th>
                                <th>Montant dépensé</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr class="text-center">
                                    <td><?= htmlspecialchars($user['user_role']) ?></td>
                                    <td><?= htmlspecialchars($user['user_name']) ?></td>
                                    <td><?= htmlspecialchars($user['user_first_name']) ?></td>
                                    <td><?= htmlspecialchars($user['user_email']) ?></td>
                                    <td><?= htmlspecialchars($user['user_orders_count']) ?></td>
                                    <td><?= htmlspecialchars($user['user_total_spent']) ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $user['user_id'] ?>">
                                            <i class="icone-suprimée bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun utilisateur.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>


    <!-- Modal -->
    <?php foreach ($users as $user): ?>
        <div class="modal fade" id="deleteModal<?= $user['user_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Veux-tu vraiment supprimer <?= htmlspecialchars($user['user_first_name']) ?> <?= htmlspecialchars($user['user_name']) ?> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_user_id" value="<?= $user['user_id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const input = document.getElementById('userSearch');
        const suggestions = document.getElementById('suggestions');

        input.addEventListener('input', () => {
            const query = input.value.trim();

            if (query.length < 1) {
                suggestions.innerHTML = '';
                return;
            }

            fetch(`index.php?url=searchUsers&query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    suggestions.innerHTML = '';

                    data.forEach(user => {
                        const item = document.createElement('a');
                        item.className = 'list-group-item list-group-item-action suggestions'; // <- ajouté
                        item.textContent = `${user.user_name} ${user.user_first_name}`;
                        item.onclick = () => {
                            input.value = `${user.user_name} ${user.user_first_name}`;
                            suggestions.innerHTML = '';
                        };
                        suggestions.appendChild(item);
                    });
                });
        });
    </script>
</body>

</html>