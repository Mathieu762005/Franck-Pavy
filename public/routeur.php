<?php
// Appelle tes contrôleurs
use App\Controllers\AdminController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CategoryProductController;
use App\Controllers\CartItemController;

// Appelle ta logique de connexion à la base de données
use App\Models\Database;

// si le param url est présent on prend sa valeur, sinon on donne la valeur home
$url = $_GET['url'] ?? '01_home';

// je transforme $url en un tableau à l'aide de explode()
$arrayUrl = explode('/', $url);

// je récupère la page demandée index 0
$page = $arrayUrl[0];

switch ($page) {
    case '01_home':
        $objController = new HomeController();
        $objController->index();
        break;

    case '02_produits':
        $objController = new CategoryProductController();
        $id = $_GET['id'] ?? null;
        $objController->showCategoryWithProducts((int) $id);
        break;

    case '03_a_propos':
        require_once __DIR__ . "/../src/Views/03_a_propos.php";
        break;

    case '05_contact':
        $objController = new ContactController();
        $objController->send();
        break;

    case '04_click_and_collect':
        $objController = new CategoryProductController();
        $objController->showClickAndCollect();
        break;

    case '06_profil':
        $objController = new UserController();
        $objController->profil();
        break;

    case 'login':
        $objController = new UserController();
        $objController->login();
        break;

    case 'register':
        $objController = new UserController();
        $objController->register();
        break;

    case 'register_success':
        require_once __DIR__ . "/../src/Views/register_success.php";
        break;

    case 'logout':
        $objController = new UserController();
        $objController->logout();
        break;

    case 'adminCommandes':
        $objController = new AdminController();
        $objController->commandes();
        break;

    case 'adminUsers':
        $objController = new AdminController();
        $objController->users();
        break;

    case 'adminProducts':
        $objController = new AdminController();
        $objController->produits();
        break;

    case 'adminMessages':
        $objController = new AdminController();
        $objController->messages();
        break;

    default:
        require_once __DIR__ . "/../src/Views/page404.php";
}
