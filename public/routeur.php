<?php

use App\Controllers\AdminController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CategoryProductController;
use App\Controllers\CartController;
use App\Controllers\OrderController;
use App\Models\Database;

// Connexion à la BDD
$db = Database::createInstancePDO();
if (!$db) {
    die("Erreur : impossible de se connecter à la base de données.");
}

// Récupération de l'URL
$url = $_GET['url'] ?? '01_home';
$page = explode('/', $url)[0];

// ROUTEUR
switch ($page) {


    case 'stripe_webhook':
        $orderController = new OrderController($db);
        $orderController->stripeWebhook(); // crée la méthode dans OrderController
        break;

    // ---------- HOME ----------
    case '01_home':
        $controller = new HomeController();
        $controller->index();
        break;

    // ---------- PRODUITS ----------
    case '02_produits':
        $controller = new CategoryProductController();
        $id = (int) ($_GET['id'] ?? 0);
        $controller->showCategoryWithProducts($id);
        break;

    case '04_click_and_collect':
        // Instancie le contrôleur
        $orderController = new OrderController($db);

        // --- TRAITEMENT FORMULAIRE SI POST ---
        $orderController->submitPickupTime();

        // Récupérer les catégories
        $categoryController = new CategoryProductController();
        $categories = $categoryController->showClickAndCollect();

        // Récupérer le panier si connecté
        $cartItems = [];
        if (isset($_SESSION['user']['id'])) {
            $cartController = new CartController($db);
            $cartItems = $cartController->viewCart();
        }

        // Récupérer les créneaux via OrderController
        $timeslots = $orderController->showForm();

        require_once __DIR__ . "/../src/Views/04_click_and_collect.php";
        break;

    case 'checkout_stripe':
        $orderController = new OrderController($db);
        $orderController->checkoutStripe();
        break;

    case 'checkout_success':
        $orderId = (int) ($_GET['order_id'] ?? 0);
        $orderController = new OrderController($db);
        $order = $orderController->getOrderDetails($orderId); // ['order' => ..., 'items' => ...]

        if ($order && !empty($order['order'])) {
            // Préparer l'heure pour affichage via la fonction du contrôleur
            $order['display_pickup_time'] = $orderController->getDisplayPickupTime($order['order']);
        } else {
            // Gestion si la commande est introuvable
            $_SESSION['error'] =    "Commande introuvable.";
            header("Location: index.php?url=04_click_and_collect");
            exit;
        }

        // Récupérer le user
        $user = $_SESSION['user'] ?? null;

        require_once __DIR__ . "/../src/Views/checkout_success.php";
        break;

    // ---------- A PROPOS ----------
    case '03_a_propos':
        require_once __DIR__ . "/../src/Views/03_a_propos.php";
        break;

    // ---------- CONTACT ----------
    case '05_contact':
        $controller = new ContactController();
        $controller->send();
        break;

    // ---------- PROFIL ----------
// ---------- PROFIL ----------
    case '06_profil':
        // Vérifier si user connecté
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            header('Location: ?url=login');
            exit;
        }

        // Charger les modèles
        $userModel = new \App\Models\User($db);
        $orderController = new OrderController($db);

        // Infos du user
        $user = $userModel->getByUserId($userId);

        // Liste des commandes
        $userOrders = $orderController->getUserOrders($userId);

        // Récupération d'une commande si cliquée
        $orderId = $_GET['order_id'] ?? null;
        $orderDetails = null;

        if ($orderId) {
            $orderDetails = $orderController->getOrderDetails((int) $orderId);
        }

        require_once __DIR__ . "/../src/Views/06_profil.php";
        break;

    case 'login':
        $controller = new UserController();
        $controller->login();
        break;

    case 'register':
        $controller = new UserController();
        $controller->register();
        break;

    case 'register_success':
        require_once __DIR__ . "/../src/Views/register_success.php";
        break;

    case 'logout':
        $controller = new UserController();
        $controller->logout();
        break;

    // ---------- PANIER ----------
    case 'cart_add':
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            http_response_code(401);
            exit;
        }

        $cartController = new CartController($db);
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);

        if ($productId > 0) {
            $cartController->addToCart($userId, $productId, $quantity);
        }

        // Si AJAX, renvoyer le HTML du panier
        if (
            !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            $cartItems = $cartController->viewCart();
            include __DIR__ . "/../src/Views/cart_table_partial.php"; // Contenu du tableau du panier
            exit;
        }

        header('Location: ?url=04_click_and_collect');
        exit;

    case 'cart_remove':
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId)
            die("Erreur : vous devez être connecté pour modifier le panier.");

        $cartController = new CartController($db);
        $cartController->removeItem((int) ($_POST['cart_item_id'] ?? 0));
        header('Location: ?url=04_click_and_collect');
        exit;

    case 'cart_update_all':
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId)
            die("Erreur : vous devez être connecté pour modifier le panier.");

        $cartController = new CartController($db);
        $quantities = $_POST['quantities'] ?? [];
        $unitPrices = $_POST['unit_prices'] ?? [];

        foreach ($quantities as $cartItemId => $quantity) {
            $unitPrice = (float) ($unitPrices[$cartItemId] ?? 0);
            $cartController->updateItem((int) $cartItemId, (int) $quantity, $unitPrice);
        }

        header('Location: ?url=04_click_and_collect');
        exit;

    case 'cart_decrease_quantity':
        $controller = new CartController($db);
        $controller->decreaseQuantity($_POST['cart_item_id']);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;

    // ---------- COMMANDES ----------
    case 'checkout':
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId)
            die("Erreur : vous devez être connecté pour passer commande.");

        $orderController = new OrderController($db);
        $pickupTime = $_POST['pickup_time'] ?? date('H:i:s'); // récupère l'heure choisie
        $orderId = $orderController->checkout($userId, $pickupTime);

        if ($orderId) {
            header('Location: ?url=order_details&id=' . $orderId);
        } else {
            die("Erreur : impossible de créer la commande (panier vide).");
        }
        exit;

    case 'order_details':
        $orderController = new OrderController($db);
        $orderId = (int) ($_GET['id'] ?? 0);
        $details = $orderController->getOrderDetails($orderId);
        require_once __DIR__ . "/../src/Views/06_profil.php";
        break;

    // ---------- ADMIN ----------
    case 'adminCommandes':
        $adminController = new AdminController($db);

        // Si formulaire envoyé pour changer le statut
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
            $adminController->updateOrderStatus((int) $_POST['order_id'], $_POST['status']);
            header('Location: ?url=adminCommandes');
            exit;
        }

        $adminController->commandes();
        break;

    case 'adminUpdateStatus':
        $adminController = new AdminController($db);
        $adminController->handleStatusUpdate();
        break;

    case 'adminUsers':
        $adminController = new AdminController($db);
        $adminController->users();
        break;

    case 'adminProducts':
        $adminController = new AdminController($db);
        $adminController->produits();
        break;

    case 'adminMessages':
        $adminController = new AdminController($db);
        $adminController->messages();
        break;

    // ---------- PAGE 404 ----------
    default:
        require_once __DIR__ . "/../src/Views/page404.php";
        break;
}