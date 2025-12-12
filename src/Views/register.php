<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>register</title>
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
            <h2 class="text-center mb-3">Crée votre compte</h2>
            <form method="POST" action="" novalidate class="mx-auto" style="max-width: 500px;">
                <div class="row g-2 mb-2">
                    <div class="col">
                        <label for="username" class="form-label small mb-1">Nom</label>
                        <small class="text-danger fst-italic"><?= $errors['username'] ?? '' ?></small>
                        <input type="text" name="username" value="<?= $_POST['username'] ?? '' ?>"
                            class="form-control form-control-sm py-1" id="username">
                    </div>
                    <div class="col">
                        <label for="firstname" class="form-label small mb-1">Prénom</label>
                        <small class="text-danger fst-italic"><?= $errors['firstname'] ?? '' ?></small>
                        <input type="text" name="firstname" value="<?= $_POST['firstname'] ?? '' ?>"
                            class="form-control form-control-sm py-1" id="firstname">
                    </div>
                </div>

                <div class="mb-2">
                    <label for="email" class="form-label small mb-1">E-mail</label>
                    <small class="text-danger fst-italic"><?= $errors['email'] ?? '' ?></small>
                    <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>"
                        class="form-control form-control-sm py-1" id="email">
                </div>

                <div class="mb-2">
                    <label for="password" class="form-label small mb-1">Mot de passe</label>
                    <small class="text-danger fst-italic"><?= $errors['password'] ?? '' ?></small>
                    <input type="password" name="password" class="form-control form-control-sm py-1" id="password">
                </div>

                <div class="mb-2">
                    <label for="confirmPassword" class="form-label small mb-1">Confirmer le mot de passe</label>
                    <small class="text-danger fst-italic"><?= $errors['confirmPassword'] ?? '' ?></small>
                    <input type="password" name="confirmPassword" class="form-control form-control-sm py-1"
                        id="confirmPassword">
                </div>

                <div class="form-check mb-2">
                    <label class="form-check-label small" for="cgu">CGU</label>
                    <small class="text-danger fst-italic"><?= $errors['cgu'] ?? '' ?></small>
                    <input type="checkbox" name="cgu" class="form-check-input" id="cgu">
                </div>

                <div class="md-3 text-center mt-3">
                    <button type="submit" class="btn px-5 rounded-4 border">Continuer</button>
                </div>
            </form>
            </form>
        </div>
        <div class="formulaire mx-auto mt-4 border py-2 rounded-5 text-center">
            <a class="text-black" href="index.php?url=login">Se connecter à A Tout Heure</a>
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