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

    <!-- Main prend tout l'espace restant -->
    <main class="mx-auto mt-5">

        <h1 class="profil-partie1__titre text-center mb-5">Bienvenue
            <?= htmlspecialchars($user['user_first_name'] ?? 'Utilisateur') ?>
        </h1>
        <div class="profil-partie1 d-flex justify-content-between gap-4">

            <div class="profil-partie1__haute border border-black rounded-4 p-4">
                <!-- INFOS USER -->
                <h2 class="profil-partie1__sous-titre">Mes informations</h2>
                <p>Nom : <?= htmlspecialchars($user['user_name'] ?? '-') ?></p>
                <p>Prénom : <?= htmlspecialchars($user['user_first_name'] ?? '-') ?></p>
                <p>Email : <?= htmlspecialchars($user['user_email'] ?? '-') ?></p>
                <p>Total dépensé : <?= number_format($user['user_total_spent'] ?? 0, 2) ?> €</p>
                <p>Nombre de commandes : <?= $user['user_orders_count'] ?? 0 ?></p>
                <?php if (isset($_SESSION['user'])): ?>
                    <a class="btn border border-black mt-4" href="index.php?url=logout">Déconnexion</a>
                <?php endif; ?>
            </div>

            <div class="profil-partie1__basse d-flex justify-content-around">
                <div class="profil-partie1__commandes border border-black rounded-4 p-4">
                    <!-- LISTE DES COMMANDES -->
                    <h2 class="profil-partie1__sous-titre">Mes commandes</h2>
                    <?php if (!empty($userOrders)): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Détails</th>
                                    <th>Heure de retrait</th>
                                    <th>Statut</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userOrders as $order): ?>
                                    <tr>
                                        <td>
                                            <a href="index.php?url=06_profil&order_id=<?= $order['order_id'] ?>">
                                                <button class="profil-partie1__btn rounded-2">Détails</button>
                                            </a>
                                        </td>
                                        <td><?= htmlspecialchars(substr($order['order_pickup_time'], 0, 5)) ?>
                                        </td>
                                        <td><?= htmlspecialchars($order['order_status']) ?></td>
                                        <td><?= htmlspecialchars($order['order_total_price']) ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Aucune commande pour le moment.</p>
                    <?php endif; ?>
                </div>
                <div class="profil-partie1__detail border border-black rounded-4 p-4">
                    <h2 class="profil-partie1__sous-titre">Détails de la commande</h2>

                    <?php if (!empty($orderDetails)): ?>
                        <p><strong>Commande :</strong> <?= htmlspecialchars($orderDetails['order']['order_number']) ?></p>
                        <p><strong>Date :</strong> <?= htmlspecialchars($orderDetails['order']['order_date']) ?></p>
                        <p><strong>Statut :</strong> <?= htmlspecialchars($orderDetails['order']['order_status']) ?></p>
                        <?php if (!empty($orderDetails['order']['order_pickup_time'])): ?>
                            <?php $formattedTime = date("H:i", strtotime($orderDetails['order']['order_pickup_time'])); ?>
                            <p><strong>Heure de retrait :</strong> <?= htmlspecialchars($formattedTime) ?></p>
                        <?php else: ?>
                            <p><strong>Heure de retrait :</strong> Non définie</p>
                        <?php endif; ?>

                        <?php if (!empty($orderDetails['items'])): ?>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Prix Unitaire</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0; ?>
                                    <?php foreach ($orderDetails['items'] as $item): ?>
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

                            <h3>Total : <?= number_format($total, 2) ?> €</h3>

                        <?php else: ?>
                            <p>Aucun article dans cette commande.</p>
                        <?php endif; ?>

                    <?php else: ?>
                        <p>Aucune commande sélectionnée.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="mt-auto">
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>