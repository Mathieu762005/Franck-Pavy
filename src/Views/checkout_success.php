<?php
$orderData = $order['order'] ?? [];
$userName = $user['username'];
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande réussie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/clicketcollects.css">
</head>

<body>
    <header>
        <?php include_once "template/navbarC&C.php"; ?>
    </header>

    <main class="container mt-5">
        <div class="alert alert-success">
            <h2>Merci pour votre commande<?= isset($user['username']) ? ', ' . htmlspecialchars($user['username']) : '' ?> !</h2>
            <p>Numéro de commande : <strong><?= htmlspecialchars($orderData['order_number'] ?? 'N/A') ?></strong></p>
            <p>Montant total : <strong><?= number_format((float) ($orderData['order_total_price'] ?? 0), 2) ?> €</strong>
            </p>
            <p>Heure de retrait : <strong><?= htmlspecialchars($order['display_pickup_time']) ?></strong></p>
        </div>

        <a href="index.php?url=06_profil" class="btn btn-primary mt-3">Retour à l'accueil</a>
    </main>

    <footer class="mt-5">
        <?php include_once "template/footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>