<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/commandeAdmin.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <header>
        <?php include_once __DIR__ . '/../template/navbarAdmin.php'; ?>
    </header>
    <main class="A-commande-partie1 d-flex flex-grow-1">
        <aside class="A-commande-partie1__aside border">
            <div>
                <ul class="navbar-nav d-flex align-items-center justify-content-start ms-3" style="width:30%;">
                    <li class="nav-item">
                        <a class="navbar-brand" href="http://localhost:8000/index.php?url=01_home">
                            COMMANDES
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a href="http://localhost:8000/index.php?url=01_home">
                            Clients
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a href="http://localhost:8000/index.php?url=01_home">
                            Produits
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="A-commande-partie1__centrale">
            <div class="A-commande-partie1__titre-marge border">
                <h1 class="A-commande-partie1__titre p-3 ms-4">Gestion des commandes</h1>
            </div>
            <div class="A-commande-partie1__contour border p-5">
                <?php if (!empty($commandes)): ?>
                    <table class="table table-light">
                        <thead>
                            <tr>
                                <th>Numéro</th>
                                <th>Date</th>
                                <th>Prix Total</th>
                                <th>Heure Retrait</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td><?= $commande['order_number'] ?></td>
                                    <td><?= substr($commande['order_date'], 5, 11) ?></td>
                                    <td><?= number_format($commande['order_total_price']) ?> €</td>
                                    <td><?= substr($commande['order_pickup_time'], 0, 5) ?></td>
                                    <td><?= $commande['order_status'] ?></td>
                                    <td>
                                        <form method="POST" action="?url=adminCommandes">
                                            <input type="hidden" name="order_id" value="<?= $commande['order_id'] ?>">
                                            <select name="status">
                                                <?php
                                                $statuses = ['brouillon', 'confirmée', 'en préparation', 'prête', 'terminée', 'annulée'];
                                                foreach ($statuses as $statusOption):
                                                    ?>
                                                    <option value="<?= $statusOption ?>"
                                                        <?= $commande['order_status'] === $statusOption ? 'selected' : '' ?>>
                                                        <?= $statusOption ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-primary">Mettre à jour</button>
                                        </form>
                                    </td>
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