<?php
$userId = $_SESSION['user']['id'] ?? null;
if (!$userId) {
    header("Location: index.php?url=login");
    exit;
}

// Récupérer les commandes de l'utilisateur avec leurs items
$userOrders = $orderController->getUserOrdersWithItems($userId);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/profil.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <?php include_once "template/navbar.php"; ?>
    </header>

    <main class="main d-flex flex-column flex-grow-1">
        <div>
            <h1 class="profil-partie1__titre text-center mb-4">
                Bienvenue <?= htmlspecialchars($user['user_first_name'] ?? 'Utilisateur') ?>
            </h1>
        </div>
        <div class="profil-partie1">

            <div class="d-flex gap-4 flex-wrap">

                <!-- INFOS UTILISATEUR -->
                <div class="row">
                    <div class="profil-partie1__marge row justify-content-between">
                        <h2 class="profil-partie1__sous-titre rounded-4">Mes informations</h2>
                        <div class="profil-partie1__information" style="flex: 1; min-width: 250px;">
                            <p><span>Nom : </span><?= htmlspecialchars($user['user_name'] ?? '-') ?></p>
                            <p><span>Prénom : </span><?= htmlspecialchars($user['user_first_name'] ?? '-') ?></p>
                            <p><span>Email : </span><?= htmlspecialchars($user['user_email'] ?? '-') ?></p>
                            <p><span>Total dépensé : </span><?= number_format($user['user_total_spent'] ?? 0, 2) ?> €</p>
                            <p><span>Nombre de commandes : </span><?= $user['user_orders_count'] ?? 0 ?></p>
                            <div>
                                <a class="profil-partie1__btn btn mt-3" href="index.php?url=logout">Déconnexion</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COMMANDES -->
                <div style="flex: 2; min-width: 400px;">
                    <h2 class="profil-partie1__sous-titre rounded-4">Mes commandes</h2>
                    <div class="profil-partie1__commandes">

                        <?php if (!empty($userOrders)): ?>
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center"
                                    style="border-radius: 12px; overflow: hidden; background-color: #f9f7f1;">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th scope="col">Détails</th>
                                            <th scope="col">Heure de retrait</th>
                                            <th scope="col">Statut</th>
                                            <th scope="col">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($userOrders as $order): ?>
                                            <tr class="align-middle">
                                                <td>
                                                    <button class="profil-partie1__btn-fermer btn btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#orderModal<?= $order['order_id'] ?>">
                                                        Détails
                                                    </button>
                                                </td>
                                                <td><?= htmlspecialchars(substr($order['order_pickup_time'], 0, 5)) ?></td>
                                                <td>
                                                    <?php
                                                    $status = strtolower($order['order_status']); // converti pour éviter les erreurs de majuscule
                                                    if ($status === 'prête'): ?>
                                                        <span
                                                            class="badge badge-prete"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php elseif ($status === 'confirmée'): ?>
                                                        <span
                                                            class="badge badge-confirmee"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php elseif ($status === 'en préparation'): ?>
                                                        <span
                                                            class="badge badge-en-preparation"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php else: ?>
                                                        <span
                                                            class="badge bg-secondary"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><strong><?= number_format($order['order_total_price'], 2) ?> €</strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- MODALS -->
                            <?php foreach ($userOrders as $order): ?>
                                <div class="modal fade" id="orderModal<?= $order['order_id'] ?>" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                                        <div class="modal-content rounded-5">

                                            <div class="modal-header">
                                                <h5 class="titre__5 modal-title">Commande
                                                    <?= htmlspecialchars($order['order_number']) ?>
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <p><strong>statut :
                                                    <?php
                                                    $status = strtolower($order['order_status']); // converti pour éviter les erreurs de majuscule
                                                    if ($status === 'prête'): ?>
                                                        <span
                                                            class="badge badge-prete"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php elseif ($status === 'confirmée'): ?>
                                                        <span
                                                            class="badge badge-confirmee"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php elseif ($status === 'en préparation'): ?>
                                                        <span
                                                            class="badge badge-en-preparation"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php else: ?>
                                                        <span
                                                            class="badge bg-secondary"><?= htmlspecialchars($order['order_status']) ?></span>
                                                    <?php endif; ?>
                                                    </strong></p>
                                                <p><strong>Heure de retrait :</strong>
                                                    <?= htmlspecialchars(substr($order['order_pickup_time'], 0, 5)) ?></p>
                                                <hr>
                                                <?php $items = $order['items'] ?? []; ?>
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
                                                                    <td><?= number_format($item['total_price'], 2) ?> €</td>
                                                                </tr>
                                                                <?php $total += $item['total_price']; ?>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                    <h5 class="text-end">Total : <?= number_format($total, 2) ?> €</h5>
                                                <?php else: ?>
                                                    <p>Aucun article dans cette commande.</p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="profil-partie1__btn-fermer btn"
                                                    data-bs-dismiss="modal">Fermer</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <p>Aucune commande pour le moment.</p>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer>
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>