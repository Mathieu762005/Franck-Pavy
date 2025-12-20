<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin/Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/commandeAdminec.css">
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
                <h1 class="A-commande-partie1__titre text-white p-3 ms-4 mb-0">Gestion des produits</h1>
            </div>

            <div class="A-commande-partie1__contour">
                <?php if (!empty($produits)): ?>
                    <table class="table table-striped custom-table text-center align-middle">
                        <thead class="thead">
                            <tr>
                                <th>Nom</th>
                                <th>Stock</th>
                                <th>Catégorie</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($produits as $produit): ?>
                                <tr>
                                    <td><?= htmlspecialchars($produit['product_name']) ?></td>
                                    <td>
                                        <input
                                            type="checkbox"
                                            class="btn-check stock-toggle"
                                            id="stock<?= $produit['product_id'] ?>"
                                            data-id="<?= $produit['product_id'] ?>"
                                            <?= ((int)$produit['product_available'] > 0) ? 'checked' : '' ?>
                                            autocomplete="off">
                                        <label
                                            class="stock-btn btn <?= ((int)$produit['product_available'] > 0) ? 'btn-success' : 'btn-danger' ?>"
                                            for="stock<?= $produit['product_id'] ?>">
                                            <?= ((int)$produit['product_available'] > 0) ? 'En stock' : 'Rupture' ?>
                                        </label>
                                    </td>
                                    <td><?= htmlspecialchars($produit['category_name']) ?></td>
                                    <td>
                                        <!-- EDIT -->
                                        <button class="btn btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProduit<?= $produit['product_id'] ?>">
                                            <i class="icone-modifier bi bi-pencil-fill"></i>
                                        </button>

                                        <!-- DELETE -->
                                        <button class="btn btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteProduit<?= $produit['product_id'] ?>">
                                            <i class="icone-suprimée bi bi-trash3-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Aucun produit.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>


    <!-- Modal De Suppression -->
    <?php foreach ($produits as $produit): ?>
        <div class="modal fade" id="deleteProduit<?= $produit['product_id'] ?>" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer la suppression</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Veux tu vraiment supprimé : <?= htmlspecialchars($produit['product_name']) ?> ? qui appartient a la categorie : <?= htmlspecialchars($produit['category_name']) ?> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="delete_product_id" value="<?= $produit['product_id'] ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <?php foreach ($produits as $produit): ?>
        <div class="modal fade" id="editProduit<?= $produit['product_id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg border-0 rounded-4">
                    <form method="POST" enctype="multipart/form-data" class="p-">
                        <div class="modal-header bg-dark text-white rounded-top-4">
                            <h5 class="modal-title">
                                <i class="bi bi-pencil-square me-2"></i> Modifier le produit
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Champs cachés -->
                            <input type="hidden" name="product_id" value="<?= $produit['product_id'] ?>">
                            <input type="hidden" name="current_image" value="<?= htmlspecialchars($produit['product_image'] ?? '') ?>">

                            <!-- NOM / SOUS-TITRE -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Nom</label>
                                    <input type="text" name="product_name" class="form-control rounded-3 shadow-sm"
                                        value="<?= htmlspecialchars($produit['product_name']) ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Sous-titre</label>
                                    <input type="text" name="product_subtitle" class="form-control rounded-3 shadow-sm"
                                        value="<?= htmlspecialchars($produit['product_subtitle'] ?? '') ?>">
                                </div>
                            </div>

                            <!-- DESCRIPTION -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Description complète</label>
                                <textarea name="product_description" class="form-control rounded-3 shadow-sm"
                                    style="min-height: 150px; resize: vertical;"
                                    placeholder="Décris le produit en détail..."><?= htmlspecialchars($produit['product_description'] ?? '') ?></textarea>
                            </div>

                            <!-- IMAGE -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">Image du produit</label>
                                <input type="file" name="product_image" id="productImageInput<?= $produit['product_id'] ?>" accept="image/*" hidden>
                                <label for="productImageInput<?= $produit['product_id'] ?>"
                                    class="cadre-image d-flex align-items-center justify-content-center rounded-3 border border-2 border-secondary p-3 bg-light"
                                    style="cursor:pointer; min-height:200px; transition:0.3s;">
                                    <?php if (!empty($produit['product_image'])): ?>
                                        <img id="currentImage<?= $produit['product_id'] ?>"
                                            src="/assets/image/<?= rawurlencode($produit['product_image']) ?>"
                                            alt="Image produit" class="img-fluid rounded shadow-sm" style="max-height:180px;">
                                    <?php endif; ?>
                                    <img id="previewImage<?= $produit['product_id'] ?>" src="" alt="Preview"
                                        class="img-fluid rounded shadow-sm" style="max-height:180px; display:none;">
                                    <?php if (empty($produit['product_image'])): ?>
                                        <span id="placeholder<?= $produit['product_id'] ?>" class="text-muted">
                                            <i class="bi bi-image me-2"></i> Cliquez pour ajouter une image
                                        </span>
                                    <?php endif; ?>
                                </label>
                            </div>

                            <!-- PRIX / STOCK / CATEGORIE -->
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Prix (€)</label>
                                    <input type="number" name="product_price" class="form-control rounded-3 shadow-sm"
                                        step="0.01" value="<?= htmlspecialchars($produit['product_price'] ?? 0) ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Stock</label>
                                    <select name="product_available" class="form-select rounded-3 shadow-sm" required>
                                        <option value="1" <?= ($produit['product_available'] ?? 0) == 1 ? 'selected' : '' ?>>En stock</option>
                                        <option value="0" <?= ($produit['product_available'] ?? 0) == 0 ? 'selected' : '' ?>>Rupture</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Catégorie</label>
                                    <select name="category_id" class="form-select rounded-3 shadow-sm">
                                        <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['category_id'] ?>"
                                                <?= ($cat['category_id'] == ($produit['category_id'] ?? 0)) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($cat['category_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">
                                Annuler
                            </button>
                            <button type="submit" name="edit_product" class="btn btn-success rounded-3 px-4 shadow-sm">
                                <i class="btn-edit bi bi-check-circle me-2"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
            <?php foreach ($produits as $produit): ?>
                document.getElementById("productImageInput<?= $produit['product_id'] ?>")
                    .addEventListener("change", function(event) {

                        const preview = document.getElementById("previewImage<?= $produit['product_id'] ?>");
                        const current = document.getElementById("currentImage<?= $produit['product_id'] ?>");
                        const placeholder = document.getElementById("placeholder<?= $produit['product_id'] ?>");
                        const file = event.target.files[0];

                        if (!file) {
                            preview.style.display = "none";
                            if (current) current.style.display = "block";
                            if (placeholder) placeholder.style.display = "block";
                            return;
                        }

                        const img = new Image();
                        img.src = URL.createObjectURL(file);

                        img.onload = function() {
                            if (img.height > img.width) {
                                alert("❌ Image verticale interdite.\nMerci d’utiliser une image horizontale.");
                                event.target.value = "";
                                preview.style.display = "none";
                                if (current) current.style.display = "block";
                                if (placeholder) placeholder.style.display = "block";
                                return;
                            }

                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                                preview.style.display = "block";
                                if (current) current.style.display = "none";
                                if (placeholder) placeholder.style.display = "none";
                            };
                            reader.readAsDataURL(file);
                        };
                    });
            <?php endforeach; ?>
        </script>

    <?php endforeach; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // On sélectionne TOUS les éléments qui ont la classe "stock-toggle"
        document.querySelectorAll('.stock-toggle').forEach(toggle => {

            // Pour chaque toggle, on écoute le changement (coché / décoché)
            toggle.addEventListener('change', function() {

                // On récupère l'id du produit depuis l'attribut data-id
                const productId = this.dataset.id;

                // Si la checkbox est cochée → stock = 1
                // Sinon → stock = 0
                const newStock = this.checked ? 1 : 0;

                // On récupère le label associé à cette checkbox
                // (grâce à l'attribut for="id_de_la_checkbox")
                const label = document.querySelector(`label[for="${this.id}"]`);

                // ====== MISE À JOUR VISUELLE ======

                // Si le produit est en stock
                if (this.checked) {
                    // On change le texte du bouton
                    label.textContent = 'En stock';

                    // On enlève la couleur rouge
                    label.classList.remove('btn-danger');

                    // On ajoute la couleur verte
                    label.classList.add('btn-success');
                }
                // Sinon (rupture de stock)
                else {
                    // On change le texte
                    label.textContent = 'Rupture';

                    // On enlève la couleur verte
                    label.classList.remove('btn-success');

                    // On ajoute la couleur rouge
                    label.classList.add('btn-danger');
                }

                // ====== ENVOI AU SERVEUR (AJAX) ======

                // On envoie les nouvelles données au serveur sans recharger la page
                fetch('index.php?url=toggleProductStock', {
                    method: 'POST', // Méthode POST
                    headers: {
                        'Content-Type': 'application/json' // On envoie du JSON
                    },
                    body: JSON.stringify({
                        product_id: productId, // ID du produit
                        product_available: newStock // Nouveau stock (0 ou 1)
                    })
                });
            });
        });
    </script>
</body>

</html>