<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande réussie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/checkout-strip.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <main class="row flex-grow-1">
        <div class="strip-partie1 d-flex align-items-center justify-content-center">
            <h2 class="strip-partie1__titre text-center">Merci pour votre
                commande, <?= isset($user['username']) ? htmlspecialchars($user['username']) : '' ?>!</h2>
        </div>
        <div class="strip-partie2 mx-auto border border-black rounded-0 px-5 mb-3 row">
            <div class="strip-partie2__haute d-flex justify-content-center align-items-center ">
                <p class="strip-partie2__cmd text-center">Numéro de commande :
                    <strong><?= htmlspecialchars($order['order']['order_number'] ?? 'N/A') ?></strong>
                </p>
            </div>
            <div class="strip-partie2__basse d-flex justify-content-evenly">
                
                <p>Montant total : <strong><?= number_format((float) ($order['order']['order_total_price'] ?? 0), 2) ?>
                        €</strong></p>
                <p>Heure de retrait : <strong><?= htmlspecialchars($order['display_pickup_time']) ?></strong></p>
            </div>
        </div>
        <div class="strip-partie3 text-center">
            <a href="index.php?url=06_profil" class="btn border-black rounded-0 mt-3">Retour au Profil</a>
        </div>
    </main>

    <footer class="mt-auto">
        <?php include_once "template/footer.php"; ?>
    </footer>
</body>

</html>