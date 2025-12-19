<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Commandes</title>
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
            <div class="A-commande-partie1__titre-marge">
                <h1 class="A-commande-partie1__titre text-white p-3 ms-4 mb-0">Gestion des commandes</h1>
            </div>

            <div class="A-commande-partie1__contour">
                <?php if (!empty($commandes)): ?>
                    <table class="table table-striped custom-table text-center align-middle">
                        <thead class="thead">
                            <tr>
                                <th>N° commande</th>
                                <th>Date</th>
                                <th>Prix Total</th>
                                <th>Heure Retrait</th>
                                <th>Commande</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($commandes as $commande): ?>
                                <tr>
                                    <td><?= htmlspecialchars($commande['order_number']) ?></td>
                                    <td><?= htmlspecialchars(substr($commande['order_date'], 5, 11)) ?></td>
                                    <td><?= htmlspecialchars($commande['order_total_price']) ?> €</td>
                                    <td><?= htmlspecialchars(substr($commande['order_pickup_time'], 0, 5)) ?></td>
                                    <td>
                                        <button class="A-commande-partie1__btn btn btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#orderModal<?= $commande['order_id'] ?>">
                                            Détails
                                        </button>
                                    </td>
                                    <td>
                                        <form method="POST" class="d-flex align-items-center justify-content-between"
                                            action="?url=adminCommandes">
                                            <input type="hidden" name="order_id" value="<?= $commande['order_id'] ?>">
                                            <select name="status" class="form-select form-select-sm me-2">
                                                <?php
                                                $statuses = ['brouillon', 'confirmée', 'en préparation', 'prête', 'terminée', 'annulée'];
                                                foreach ($statuses as $statusOption): ?>
                                                    <option value="<?= $statusOption ?>"
                                                        <?= ($commande['order_status'] === $statusOption) ? 'selected' : '' ?>>
                                                        <?= $statusOption ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button type="submit" class="A-commande-partie1__btn btn btn-sm ">modifier</button>
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

    <!-- MODALS -->
    <?php foreach ($commandes as $commande): ?>
        <div class="modal fade" id="orderModal<?= $commande['order_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content rounded-5">

                    <div class="modal-header">
                        <h5 class="modal-title">Commande <?= htmlspecialchars($commande['order_number']) ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Heure de retrait :</strong>
                            <?= htmlspecialchars(substr($commande['order_pickup_time'] ?? '', 0, 5)) ?></p>
                        <hr>

                        <?php $items = $commande['details'] ?? []; ?>
                        <?php if (!empty($items)): ?>
                            <table class="table table-striped">
                                <thead class="table-secondary">
                                    <tr>
                                        <th>Produit</th>
                                        <th>Qté</th>
                                        <th>Prix</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    <?php foreach ($items as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td><?= $item['quantity'] ?></td>
                                            <td><?= number_format($item['unit_price'], 2) ?> €</td>
                                            <td><?= number_format($item['total_line'], 2) ?> €</td>
                                        </tr>
                                        <?php $total += $item['total_line']; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <h5 class="text-end">Total : <?= number_format($total, 2) ?> €</h5>
                        <?php else: ?>
                            <p>Aucun article dans cette commande.</p>
                        <?php endif; ?>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>