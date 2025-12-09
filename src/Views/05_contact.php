<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <header>
        <?php include_once "template/navbar.php" ?>
    </header>
    <main>
        <div class="container mt-4">
            <h1>Contactez-nous</h1>

            <form method="POST" action="" novalidate>
                <!-- Champ Sujet -->
                <div class="mb-3">
                    <label for="subject" class="form-label">Objet</label><span
                        class="ms-2 text-danger fst-italic fw-light"><?= $errors["subject"] ?? '' ?></span>
                    <input type="text" class="form-control" id="subject" name="subject" required>
                </div>

                <!-- Champ Message -->
                <div class="mb-3">
                    <label for="body" class="form-label">Votre message</label><span
                        class="ms-2 text-danger fst-italic fw-light"><?= $errors["body"] ?? '' ?></span>
                    <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
                </div>

                <!-- Bouton Envoyer -->
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>

    </main>
    <footer>
        <?php include_once "template/footer.php" ?>
    </footer>

    <?php if (!empty($errors['auth'])): ?>
        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="loginModalLabel">Retour a l'accueil</h5>
                        <a href="index.php?url=01_home" class="btn-close" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        Vous devez être connecté pour envoyer un message.
                    </div>
                    <div class="modal-footer">
                        <a href="index.php?url=login" class="btn btn-primary">Se connecter</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var myModal = new bootstrap.Modal(document.getElementById('loginModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                myModal.show();
            });
        </script>
    <?php endif; ?>

    <?php if (!empty($messageSent)): ?>
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successModalLabel">Message envoyé</h5>
                    </div>
                    <div class="modal-body">
                        Votre message a bien été envoyé. Merci de nous avoir contactés !
                    </div>
                    <div class="modal-footer">
                        <a href="index.php?url=01_home" class="btn btn-primary">Retour à l’accueil</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                var myModal = new bootstrap.Modal(document.getElementById('successModal'));
                myModal.show();
            });
        </script>
    <?php endif; ?>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>