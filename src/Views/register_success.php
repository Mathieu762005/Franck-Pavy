<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription réussie</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="../assets/css/register-success.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <main class="flex-grow-1 d-flex align-items-center justify-content-center">
        <div class="s-register-partie1 mx-auto text-center rounded-4">
            <div class="s-register-partie1__centrale alert border border-2 rounded-4 border-black rounded-0 py-5 m-0">
                <h2 class="s-register-partie1__titre">Félicitations,
                    <?= htmlspecialchars($_SESSION['user']['username']) ?> !
                </h2>
                <p class="s-register-partie1__description">Votre compte a été créé avec succès.</p>
                <div>
                    <a href="index.php?url=login" class="btn border-black rounded-1 mt-3">Se connecter</a>
                </div>
            </div>
        </div>
    </main>
    <footer>
        <?php include_once "template/footer.php"; ?>
    </footer>
</body>

</html>