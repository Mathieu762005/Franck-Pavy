<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>

<body>
    <header>
        <div>
            <?php include_once "template/navbar.php" ?>
        </div>
        <div class="text-center py-4">
            <img src="assets/image/Logo/Logo.png" alt="">
        </div>
    </header>
    <main>
        <div class="formulaire mx-auto mt-4 border p-4 rounded-4">
            <h2 class="text-center mb-5">Se connecter</h2>
            <form method="POST" action="" novalidate>
                <div class="mb-3">
                    <label for=" inputAddress" class="form-label">Votre e-mail</label><span
                        class="ms-2 text-danger fst-italic fw-light"><?= $errors["email"] ?? '' ?></span>
                    <input type="email" name="email" value="<?= $_POST["email"] ?? "" ?>" class="form-control"
                        id="inputAddress">
                </div>
                <div class="mb-3">
                    <label for=" inputAddress" class="form-label">Votre mot de passe</label><span
                        class="ms-2 text-danger fst-italic fw-light"><?= $errors["password"] ?? '' ?></span>
                    <input type="password" name="password" value="<?= $_POST["password"] ?? "" ?>" class="form-control"
                        id="inputAddress">
                </div>
                <div class="md-3 text-center mx-1 mt-4 row">
                    <button type="submit" class="btn mb-2 rounded-4 border">Continuer</button>
                    <span class="ms-2 text-danger fst-italic fw-light"><?= $errors["connexion"] ?? '' ?></span>
                </div>
            </form>
        </div>
        <div class="formulaire mx-auto mt-4 border py-2 rounded-5 text-center">
            <a class="text-black" href="index.php?url=register">Pas encore inscrit</a>
        </div>
    </main>
    <footer>
        <?php include_once "template/footer.php" ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>