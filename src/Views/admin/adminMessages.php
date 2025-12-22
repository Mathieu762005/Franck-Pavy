<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/commandeAdmine.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <?php include_once __DIR__ . '/../template/navbarAdmin.php'; ?>
    </header>

    <main class="A-commande-partie1 d-flex flex-grow-1">

        <!-- SIDEBAR -->
        <aside class="A-commande-partie1__aside">
            <div>
                <ul class="navbar-nav mx-auto" style="width: 80%;">
                    <li class="nav-item mt-3">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminCommandes">
                            <i class="bi bi-basket2-fill me-3"></i> COMMANDES
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminUsers">
                            <i class="bi bi-person-circle me-3"></i> UTILISATEURS
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminProducts">
                            <i class="bi bi-box-seam me-3"></i> PRODUITS
                        </a>
                    </li>
                    <hr>
                    <li class="nav-item">
                        <a class="navbar-brand d-flex align-items-center" href="?url=adminMessages">
                            <i class="bi bi-chat-left-text-fill me-3"></i> MESSAGES
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div class="A-commande-partie1__centrale">
            <div class="A-commande-partie1__titre-marge">
                <h1 class="A-commande-partie1__titre text-white p-3 ms-4 mb-0">Gestion des Messages</h1>
            </div>

            <div class="A-commande-partie1__contour">
                <?php if (!empty($messages)): ?>
                    <table class="table table-striped custom-table text-center align-middle">
                        <thead class="thead">
                            <tr>
                                <th>nom</th>
                                <th>Prénom</th>
                                <th>date</th>
                                <th>E-mail</th>
                                <th>Voir le message</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($messages as $message): ?>
                                <tr>
                                    <td><?= htmlspecialchars($message['user_name']) ?></td>
                                    <td><?= htmlspecialchars($message['user_first_name']) ?></td>
                                    <td> <?= (new DateTime($message['message_sent_at']))->format('d/m/Y H:i') ?></td>
                                    <td><?= htmlspecialchars($message['user_email']) ?></td>
                                    <td>
                                        <button class="A-commande-partie1__btn btn btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#orderModal<?= $message['message_id'] ?>">
                                            Détails
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                            <i class="icone-suprimée bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="mt-3 ms-3">Aucun message.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Veut tu vraiment suprimée ce produit
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_message_id" value="<?= $message['message_id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- MODALS -->
    <?php foreach ($messages as $message): ?>
        <div class="modal fade" id="orderModal<?= $message['message_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content rounded-5">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            Message de <?= htmlspecialchars($message['user_first_name']) ?>
                            <?= htmlspecialchars($message['user_name']) ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p><strong>Objet :</strong> <?= htmlspecialchars($message['message_subject']) ?></p>
                        <hr>
                        <p><strong>Message :</strong><br>
                            <?= nl2br(htmlspecialchars($message['message_body'])) ?>
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>