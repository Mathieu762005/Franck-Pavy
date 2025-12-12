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

<body class="d-flex flex-column min-vh-100">
    <header>
        <div class="text-center py-4">
            <a href="http://localhost:8000/index.php?url=01_home"><img src="assets/image/Logo/Logo.png" alt=""></a>
        </div>
    </header>
    <main>
        <div class="formulaire mx-auto mt-4 border p-4 rounded-4">
            <h2 class="text-center mb-5">Se connecter</h2>
            <form method="POST" action="" novalidate class="mx-auto" style="max-width: 400px;">
                <!-- E-mail -->
                <div class="mb-2">
                    <label for="email" class="form-label small mb-1">Votre e-mail</label>
                    <small class="text-danger fst-italic"><?= $errors['email'] ?? '' ?></small>
                    <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>"
                        class="form-control form-control-sm py-1" id="email">
                </div>

                <!-- Mot de passe -->
                <div class="mb-2">
                    <label for="password" class="form-label small mb-1">Votre mot de passe</label>
                    <small class="text-danger fst-italic"><?= $errors['password'] ?? '' ?></small>
                    <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>"
                        class="form-control form-control-sm py-1" id="password">
                </div>

                <!-- Bouton -->
                <div class="text-center mt-3">
                    <button type="submit" class="btn px-5 rounded-4 border">Continuer</button>
                    <div>
                        <small class="text-danger fst-italic"><?= $errors['connexion'] ?? '' ?></small>
                    </div>
                </div>
            </form>
        </div>
        <div class="formulaire mx-auto mt-4 border py-2 rounded-5 text-center">
            <a class="text-black" href="index.php?url=register">Pas encore inscrit</a>
        </div>
    </main>
    <footer class="mt-auto">
        <?php include_once "template/footer.php" ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>