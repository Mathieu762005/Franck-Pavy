<?php
// Appelle t'es controlleurs
use App\Controllers\AdminController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\CategoryProductController;

// Appelle t'as logique de connexion a la base de donnés
use App\Models\Database;

// si le param url est présent on prend sa valeur, sinon on donne la valeur home
$url = $_GET['url'] ?? '01_home';

// je transforme $url en un tableau à l'aide de explode()
$arrayUrl = explode('/', $url);

// je récupère la page demandée index 0
$page = $arrayUrl[0];

switch ($page) {
    case '01_home': /* le nom de t'as page */
        $objController = new HomeController(); /* appelle ton bon controlleur */
        $objController->index();  /* appelle la method de ton controlleur */
        break;

    case 'login': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->login();  /* appelle la method de ton controlleur */
        break;

    case '06_profil': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->profil();  /* appelle la method de ton controlleur */
        break;

    case 'register_success': /* le nom de t'as page */
        require_once __DIR__ . "/../src/Views/register_success.php";
        break;

    case 'register': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->register();  /* appelle la method de ton controlleur */
        break;

    case '05_contact': /* le nom de t'as page */
        $objController = new ContactController(); /* appelle ton bon controlleur */
        $objController->send();  /* appelle la method de ton controlleur */
        break;

    case '03_a_propos': /* le nom de t'as page */
        require_once __DIR__ . "/../src/Views/03_a_propos.php";
        break;

    case '04_click_and_collect': /* le nom de t'as page */
        require_once __DIR__ . "/../src/Views/04_click_and_collect.php";
        break;

    case 'logout': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->logout();  /* appelle la method de ton controlleur */
        break;

    case '05_contact': /* le nom de t'as page */
        require_once __DIR__ . "/../src/Views/05_contact.php";
        break;

    case '06_profil': /* le nom de t'as page */
        $objController = new UserController(); /* appelle ton bon controlleur */
        $objController->profil();  /* appelle la method de ton controlleur */
        break;

    case 'adminCommandes': /* le nom de t'as page */
        $objController = new AdminController(); /* appelle ton bon controlleur */
        $objController->commandes();  /* appelle la method de ton controlleur */
        break;

    case 'adminUsers': /* le nom de t'as page */
        $objController = new AdminController(); /* appelle ton bon controlleur */
        $objController->users();  /* appelle la method de ton controlleur */
        break;

    case 'adminProducts': /* le nom de t'as page */
        $objController = new AdminController(); /* appelle ton bon controlleur */
        $objController->produits();  /* appelle la method de ton controlleur */
        break;

    case 'adminMessages': /* le nom de t'as page */
        $objController = new AdminController(); /* appelle ton bon controlleur */
        $objController->messages();  /* appelle la method de ton controlleur */
        break;

    case '02_produits': /* le nom de t'as page */
        $objController = new CategoryProductController();
        $id = $_GET['id'] ?? null;
        $objController->showCategoryWithProducts((int) $id);
        break;

    default:
        // aucun cas reconnu = on charge la 404
        require_once __DIR__ . "/../src/Views/page404.php";
}