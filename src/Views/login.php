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
        <div class="text-center py-4">
            <img src="assets/image/Logo/Logo.png" alt="">
        </div>
    </header>
    <main>
        <div class="formulaire mx-auto mt-4 border p-4 rounded-4">
            <h2 class="text-center mb-5">Crée votre compte</h2>
            <form>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Votre nom</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["username"] ?? '' ?></span>
                    <input type="text" name="username" value="<?= $_POST["username"] ?? "" ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Votre prénom</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["firstname"] ?? '' ?></span>
                    <input type="text" name="firstname" value="<?= $_POST["firstname"] ?? "" ?>" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="mb-3">
                    <label for=" inputAddress" class="form-label">Votre e-mail</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["email"] ?? '' ?></span>
                    <input type="email" name="email" value="<?= $_POST["email"] ?? "" ?>" class="form-control"
                        id="inputAddress">
                </div>
                <div class="mb-3">
                    <label for=" inputAddress" class="form-label">Votre mot de passe</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["password"] ?? '' ?></span>
                    <input type="password" name="password" value="<?= $_POST["password"] ?? "" ?>" class="form-control"
                        id="inputAddress">
                </div>
                <div class="mb-3">
                    <label for="inputCity" class="form-label">Entrez le mot de passe a nouveau</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["confirmPassword"] ?? '' ?></span>
                    <input type="password" name="confirmPassword" value="<?= $_POST["confirmPassword"] ?? "" ?>" class="form-control"
                        id="inputCity">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" name="cgu" class="form-check-input" id="exampleCheck1">
                    <label class="form-check-label" for="exampleCheck1">CGU</label><span class="ms-2 text-danger fst-italic fw-light"><?= $errors["cgu"] ?? '' ?></span>
                </div>
                <div class="md-3 text-center mt-3">
                    <button type="submit" class="btn px-5 rounded-4 border">Continuer</button>
                </div>
            </form>
        </div>
        <div class="formulaire mx-auto mt-4 border py-2 rounded-5 text-center">
            <a class="text-black" href="index.php?url=register">Se connecter à A Tout Heure</a>
        </div>
    </main>
</body>

</html>