<?php

// ---------- IMPORTS ----------
use App\Models\Database;
use App\Controllers\AdminController;
use App\Controllers\CartController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CategoryProductController;
use App\Controllers\OrderController;
use App\Controllers\StripeController;

// ---------- CONNEXION À LA BASE ----------
$db = Database::createInstancePDO();
if (!$db) {
    die("Erreur : impossible de se connecter à la base de données."); // stoppe le script si pas de DB
}

// ---------- RÉCUPÉRATION DE L'URL ----------
$url = $_GET['url'] ?? '01_home'; // si aucune URL, on prend la page d'accueil
$page = explode('/', $url)[0];    // on prend seulement la première partie de l'URL

// ---------- ROUTEUR PRINCIPAL ----------
switch ($page) {

    // ---------- HOME ----------
    case '01_home':
        $controller = new HomeController(); // instancie le contrôleur de la page d'accueil
        $controller->index();               // appelle la méthode pour afficher la page
        break;

    // ---------- PRODUITS ----------
    case '02_produits':
        $controller = new CategoryProductController(); // contrôleur des catégories & produits
        $id = (int) ($_GET['id'] ?? 0);               // récupère l'id de la catégorie si présent
        $controller->showCategoryWithProducts($id);   // affiche la catégorie et ses produits
        break;

    case '04_click_and_collect':
        $orderController = new OrderController($db); // contrôleur pour gérer les commandes
        $orderController->submitPickupTime();        // gère le POST pour choisir un créneau

        // récupère les données pour afficher la page
        $data = $orderController->showClickAndCollectPage();
        $categories = $data['categories'];
        $cartItems = $data['cartItems'];
        $timeslots = $data['timeslots'];

        require_once __DIR__ . "/../src/Views/04_click_and_collect.php"; // vue
        break;

    // ---------- A PROPOS ----------
    case '03_a_propos':
        require_once __DIR__ . "/../src/Views/03_a_propos.php"; // page statique
        break;

    // ---------- CONTACT ----------
    case '05_contact':
        $controller = new ContactController(); // contrôleur du formulaire contact
        $controller->send();                  // gère l'envoi du message
        break;

    // ---------- PROFIL ----------
    case '06_profil':
        $controller = new UserController();
        $controller->profil(); // affiche la page profil et gère toutes les infos
        break;

    case 'login':
        $controller = new UserController();
        $controller->login();  // formulaire de connexion
        break;

    case 'register':
        $controller = new UserController();
        $controller->register(); // formulaire d'inscription
        break;

    // ---------- SOUS PAGES ----------
    case 'register_success':
        require_once __DIR__ . "/../src/Views/register_success.php"; // page de succès inscription
        break;

    case 'logout':
        $controller = new UserController();
        $controller->logout(); // supprime la session et redirige vers login
        break;

    // ---------- PANIER ----------
    case 'cart_add':
        $cartController = new CartController($db); // contrôleur du panier

        // récupère les données POST
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        // ajoute le produit si id valide
        if ($productId > 0) {
            $cartController->addToCart($productId, $quantity);
        }

        // redirection après ajout
        header('Location: ?url=04_click_and_collect');
        exit;

    case 'cart_remove':
        $cartController = new CartController($db);
        $cartController->removeItem((int) ($_POST['cart_item_id'] ?? 0)); // supprime l'article
        header('Location: ?url=04_click_and_collect');
        exit;

    case 'cart_update_all':
        $cartController = new CartController($db);
        $quantities = $_POST['quantities'] ?? [];
        $unitPrices = $_POST['unit_prices'] ?? [];

        // met à jour tous les articles du panier
        foreach ($quantities as $cartItemId => $quantity) {
            $unitPrice = (float) ($unitPrices[$cartItemId] ?? 0);
            $cartController->updateItem((int) $cartItemId, (int) $quantity, $unitPrice);
        }

        header('Location: ?url=04_click_and_collect');
        exit;

    case 'cart_decrease_quantity':
        $cartController = new CartController($db);
        $cartController->decreaseQuantity((int) ($_POST['cart_item_id'] ?? 0)); // diminue la quantité
        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '?url=04_click_and_collect'));
        exit;

        // ---------- COMMANDES ----------
    case 'checkout':
        $orderController->handleCheckoutFromPost(); // création de la commande via POST
        break;

    case 'order_details':
        $details = $orderController->handleOrderDetailsFromGet(); // détails de la commande
        require_once __DIR__ . "/../src/Views/06_profil.php";
        break;

    // ---------- ADMIN ----------
    case 'adminCommandes':
        $adminController = new AdminController($db);
        $adminController->commandes(); // gestion commandes admin
        break;

    case 'adminUsers':
        $adminController = new AdminController($db);
        $adminController->users(); // gestion utilisateurs
        break;

    case 'searchUsers':
        $controller = new AdminController($db);
        $controller->searchUsers();
        break;

    case 'adminProducts':
        $adminController = new AdminController($db);
        $adminController->produits($db); // gestion produits
        break;

    case 'toggleProductStock':
        $controller = new AdminController($db);
        $controller->toggleProductStock();
        break;

    case 'adminMessages':
        $adminController = new AdminController($db);
        $adminController->messages(); // gestion messages
        break;

    // ---------- STRIPE ----------
    case 'checkout_stripe':
        $stripeController = new StripeController($db);
        $stripeController->checkoutStripe(); // redirection vers Stripe
        break;

    case 'checkout_success':
        $stripeController = new StripeController($db);
        $orderId = (int) ($_GET['order_id'] ?? 0);

        // vérifie que l'ID commande est valide
        if ($orderId <= 0) {
            $_SESSION['error'] = "Commande introuvable.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // récupère les infos pour la vue
        $data = $stripeController->handleCheckoutSuccess($orderId);
        $order = $data['order'];
        $user = $data['user'];

        require_once __DIR__ . "/../src/Views/checkout_success.php";
        break;

    case 'stripe_webhook':
        $stripeController = new StripeController($db);
        $stripeController->stripeWebhook(); // webhook Stripe pour valider le paiement
        break;

    // ---------- PAGE 404 ----------
    default:
        require_once __DIR__ . "/../src/Views/page404.php"; // page introuvable
        break;
}
